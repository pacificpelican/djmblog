<?php
// Buffer output so headers/status codes can still be set after the DB messages below
ob_start();
// Start the session, trying custom writable locations before the host default
function start_session_with_fallback() {
  if (session_status() === PHP_SESSION_ACTIVE) {
    return true;
  }

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

$servername = "localhost";
$username = "djmblog";
$password = "1111111";
$dbname = "djmblog";

// Optional override so real credentials can stay out of git: create post/db.local.php
// containing <?php $servername = '...'; $username = '...'; $password = '...'; $dbname = '...';
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
