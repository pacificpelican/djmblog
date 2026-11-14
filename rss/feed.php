<?php

function rss_text($value) {
  $escaped = htmlspecialchars((string)$value, ENT_QUOTES | ENT_XML1 | ENT_SUBSTITUTE, 'UTF-8');
  return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x{FFFE}\x{FFFF}]/u', '', $escaped);
}

function rss_site_url($server) {
  $scheme = !empty($server['HTTPS']) && $server['HTTPS'] !== 'off' ? 'https' : 'http';
  $host = $server['HTTP_HOST'] ?? '';
  if (!preg_match('/\A(?:[a-z0-9.-]+|\[[a-f0-9:]+\])(?::[0-9]{1,5})?\z/i', $host)) {
    throw new RuntimeException('Invalid RSS request host');
  }
  $path = str_replace('\\', '/', dirname(dirname($server['SCRIPT_NAME'])));
  return $scheme . '://' . $host . rtrim($path, '/');
}

function rss_absolute_url($url, $pageUrl) {
  if (preg_match('~^[a-z][a-z0-9+.-]*:~i', $url)) {
    return $url;
  }
  $page = parse_url($pageUrl);
  if (substr($url, 0, 2) === '//') {
    return $page['scheme'] . ':' . $url;
  }
  if ($url === '' || $url[0] === '#') {
    return $pageUrl . $url;
  }
  $origin = $page['scheme'] . '://' . $page['host'] . (isset($page['port']) ? ':' . $page['port'] : '');
  if ($url[0] === '?') {
    return $origin . $page['path'] . $url;
  }
  $parts = preg_split('/([?#].*)/s', $url, 2, PREG_SPLIT_DELIM_CAPTURE);
  $directory = substr($page['path'], -1) === '/' ? $page['path'] : dirname($page['path']) . '/';
  $path = $url[0] === '/' ? $parts[0] : $directory . $parts[0];
  $segments = array();
  foreach (explode('/', $path) as $segment) {
    if ($segment === '..') {
      array_pop($segments);
    } elseif ($segment !== '.' && $segment !== '') {
      $segments[] = $segment;
    }
  }
  return $origin . '/' . implode('/', $segments) . ($segments && substr($parts[0], -1) === '/' ? '/' : '') . ($parts[1] ?? '');
}

function rss_content($html, $pageUrl) {
  $html = clean_html($html);
  if (!class_exists('DOMDocument') || $html === '') {
    return $html;
  }
  $doc = new DOMDocument();
  $previous = libxml_use_internal_errors(true);
  $doc->loadHTML('<?xml encoding="utf-8" ?><div id="rss-root">' . $html . '</div>');
  libxml_clear_errors();
  libxml_use_internal_errors($previous);
  $root = $doc->getElementById('rss-root');
  if (!$root) {
    throw new RuntimeException('Unable to prepare RSS post content');
  }
  foreach (array('a' => 'href', 'img' => 'src') as $tag => $attribute) {
    foreach ($root->getElementsByTagName($tag) as $element) {
      if ($element->hasAttribute($attribute)) {
        $element->setAttribute($attribute, rss_absolute_url($element->getAttribute($attribute), $pageUrl));
      }
    }
  }
  $content = '';
  foreach ($root->childNodes as $child) {
    $content .= $doc->saveHTML($child);
  }
  return $content;
}

function rss_feed($posts, $siteUrl) {
  $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  $xml .= '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom"><channel>';
  $xml .= '<title>djmblog posts</title><link>' . rss_text($siteUrl . '/posts/') . '</link>';
  $xml .= '<description>The latest 20 posts from djmblog, with full content.</description>';
  $xml .= '<atom:link href="' . rss_text($siteUrl . '/rss/') . '" rel="self" type="application/rss+xml"/>';
  foreach (array_slice($posts, 0, 20) as $post) {
    $link = $siteUrl . '/postview/?id=' . rawurlencode($post['uu_id']);
    $content = rss_text(rss_content($post['post'], $link));
    $xml .= '<item><title>' . rss_text('djmblog post ' . $post['uu_id']) . '</title>';
    $xml .= '<link>' . rss_text($link) . '</link><guid isPermaLink="true">' . rss_text($link) . '</guid>';
    $xml .= '<dc:creator>' . rss_text($post['poster']) . '</dc:creator>';
    $xml .= '<pubDate>' . gmdate('D, d M Y H:i:s +0000', (int)substr((string)$post['date_string'], 0, 10)) . '</pubDate>';
    $xml .= '<description>' . $content . '</description><content:encoded>' . $content . '</content:encoded></item>';
  }
  return $xml . "</channel></rss>\n";
}
