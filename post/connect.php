<?php
// Buffer output so headers/status codes can still be set after the DB messages below
ob_start();
// Start the session, trying custom writable locations before the host default
function start_session_with_fallback() {
  if (session_status() === PHP_SESSION_ACTIVE) {
    return true;
  }

  $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
  session_set_cookie_params(array('path' => '/', 'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax'));

  $home = !empty($_SERVER['DOCUMENT_ROOT']) ? dirname($_SERVER['DOCUMENT_ROOT']) : dirname(__DIR__);
  $candidates = array(
    $home . DIRECTORY_SEPARATOR . 'tmp_sessions',     // above web root (preferred)
    __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.sessions', // local fallback (denied via .htaccess)
    sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'djmblog_sessions',
  );

  foreach ($candidates as $dir) {
    if (!is_dir($dir)) {
      @mkdir($dir, 0700, true);
    }
    if (is_dir($dir) && is_writable($dir)) {
      if (strpos($dir, '.sessions') !== false && !file_exists($dir . DIRECTORY_SEPARATOR . '.htaccess')) {
        @file_put_contents($dir . DIRECTORY_SEPARATOR . '.htaccess', "Require all denied\nDeny from all\n");
      }
      session_save_path($dir);
      if (@session_start()) {
        return true;
      }
    }
  }

  // Last resort: PHP's configured default
  session_save_path('');
  return @session_start();
}

if (!start_session_with_fallback()) {
  error_log('djmblog: unable to start a session');
}

// CSRF protection: one random token per session, embedded in every state-changing form
function csrf_token() {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function csrf_field() {
  return "<input type='hidden' name='csrf_token' value='" . csrf_token() . "'>";
}

// HTML-escape for output
function h($s) {
  return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Allowlist HTML sanitizer for user-authored post/comment bodies
function clean_html($html) {
  $html = (string)$html;
  if (!class_exists('DOMDocument')) {
    return h($html);
  }
  $allowed = array(
    'p' => array(), 'br' => array(), 'hr' => array(), 'b' => array(), 'strong' => array(),
    'i' => array(), 'em' => array(), 'u' => array(), 's' => array(), 'del' => array(),
    'ul' => array(), 'ol' => array(), 'li' => array(), 'blockquote' => array(),
    'code' => array(), 'pre' => array(), 'span' => array(), 'div' => array(),
    'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
    'a' => array('href', 'title'), 'img' => array('src', 'alt', 'title'),
  );
  $dropWithContent = array('script', 'style', 'iframe', 'object', 'embed', 'applet', 'noscript',
    'template', 'form', 'textarea', 'select', 'svg', 'math', 'link', 'meta', 'base', 'frame', 'frameset');

  $doc = new DOMDocument();
  $prev = libxml_use_internal_errors(true);
  $doc->loadHTML('<?xml encoding="utf-8" ?><div id="djm-root">' . $html . '</div>', LIBXML_NOERROR | LIBXML_NOWARNING);
  libxml_clear_errors();
  libxml_use_internal_errors($prev);
  $root = $doc->getElementById('djm-root');
  if (!$root) {
    return h($html);
  }

  $walk = function ($node) use (&$walk, $allowed, $dropWithContent) {
    foreach (iterator_to_array($node->childNodes) as $child) {
      if ($child->nodeType === XML_TEXT_NODE) {
        continue;
      }
      if ($child->nodeType !== XML_ELEMENT_NODE) {
        $node->removeChild($child);
        continue;
      }
      $name = strtolower($child->nodeName);
      if (in_array($name, $dropWithContent, true)) {
        $node->removeChild($child);
        continue;
      }
      $walk($child);
      if (!isset($allowed[$name])) {
        // unknown tag: keep its (already cleaned) children, drop the tag itself
        while ($child->firstChild) {
          $node->insertBefore($child->firstChild, $child);
        }
        $node->removeChild($child);
        continue;
      }
      foreach (iterator_to_array($child->attributes) as $attr) {
        $an = strtolower($attr->nodeName);
        if (!in_array($an, $allowed[$name], true)) {
          $child->removeAttributeNode($attr);
          continue;
        }
        if ($an === 'href' || $an === 'src') {
          $url = preg_replace('/[\x00-\x20\x7f]+/', '', $attr->value);
          $ok = ($an === 'href')
            ? preg_match('~^(https?:|mailto:|/|\./|\.\./|#|\?)~i', $url) || !preg_match('~^[a-z][a-z0-9+.-]*:~i', $url)
            : preg_match('~^(https?:|/|\./|\.\./)~i', $url) || !preg_match('~^[a-z][a-z0-9+.-]*:~i', $url);
          if (!$ok) {
            $child->removeAttributeNode($attr);
          }
        }
      }
      if ($name === 'a' && $child->hasAttribute('href')) {
        $child->setAttribute('rel', 'nofollow noopener noreferrer');
      }
    }
  };
  $walk($root);

  $out = '';
  foreach ($root->childNodes as $child) {
    $out .= $doc->saveHTML($child);
  }
  return $out;
}

// Requires a POST with a valid token; otherwise stops with 403/405
function csrf_check() {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method not allowed');
  }
  $sent = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
  if (empty($_SESSION['csrf_token']) || !is_string($sent) || !hash_equals($_SESSION['csrf_token'], $sent)) {
    http_response_code(403);
    exit('Invalid or missing security token. Go back, reload the page and try again.');
  }
}

// Throttling: generous per-IP caps meant only to stop bots, not people.
// Counters live in small files next to the session files, so no DB schema change is needed.
define('LOGIN_MAX_FAILS', 100);        // failed logins per window
define('LOGIN_WINDOW_SECONDS', 900);
define('REGISTER_MAX', 50);            // accounts created per window
define('REGISTER_WINDOW_SECONDS', 3600);

function throttle_file($bucket) {
  $dir = session_save_path();
  if ($dir === '' || !is_dir($dir) || !is_writable($dir)) {
    $dir = sys_get_temp_dir();
  }
  $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
  return rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . 'djmrl_' . $bucket . '_' . hash('sha256', $ip);
}

function throttle_count($bucket, $window) {
  $f = throttle_file($bucket);
  if (!is_file($f)) {
    return 0;
  }
  $d = json_decode((string)@file_get_contents($f), true);
  if (!is_array($d) || !isset($d['start'], $d['count']) || time() - $d['start'] > $window) {
    return 0;
  }
  return (int)$d['count'];
}

function throttle_hit($bucket, $window) {
  $f = throttle_file($bucket);
  $h = @fopen($f, 'c+');
  if (!$h) {
    return;
  }
  flock($h, LOCK_EX);
  $d = json_decode((string)stream_get_contents($h), true);
  if (!is_array($d) || !isset($d['start'], $d['count']) || time() - $d['start'] > $window) {
    $d = array('start' => time(), 'count' => 0);
  }
  $d['count']++;
  ftruncate($h, 0);
  rewind($h);
  fwrite($h, json_encode($d));
  flock($h, LOCK_UN);
  fclose($h);

  // occasionally prune counter files older than the longest window
  if (mt_rand(1, 50) === 1) {
    foreach ((array)glob(dirname($f) . DIRECTORY_SEPARATOR . 'djmrl_*') as $old) {
      if (is_file($old) && time() - filemtime($old) > max(LOGIN_WINDOW_SECONDS, REGISTER_WINDOW_SECONDS)) {
        @unlink($old);
      }
    }
  }
}

function login_is_throttled() { return throttle_count('login', LOGIN_WINDOW_SECONDS) >= LOGIN_MAX_FAILS; }
function login_record_failure() { throttle_hit('login', LOGIN_WINDOW_SECONDS); }
function register_is_throttled() { return throttle_count('register', REGISTER_WINDOW_SECONDS) >= REGISTER_MAX; }
function register_record() { throttle_hit('register', REGISTER_WINDOW_SECONDS); }

$servername = "localhost";
$username = "djmblog";
$password = "1111111";
$dbname = "djmblog";

// Set to false to close registration (existing users can still log in)
$open_registration = true;

// Optional override so real settings can stay out of git: create post/db.local.php
// containing <?php $servername = '...'; $username = '...'; $password = '...'; $dbname = '...';
// and optionally $open_registration = false;
if (file_exists(__DIR__ . '/db.local.php')) {
  include __DIR__ . '/db.local.php';
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
echo "<section id='dbInfo'>";
if ($conn->connect_error) {
  echo("Database connection failed: " . $conn->connect_error);
}
else {
//  echo ("Database connected successfully -- 📼 " . $conn->connection_status);
}
echo "</section>";
?> 


<style>
  #dbInfo {
    font-family: Futura, display;
    color: yellow;
    background: gray;
  }
</style>
