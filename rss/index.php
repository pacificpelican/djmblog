<?php
define('DB_OPTIONAL', true);
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR . 'connect.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'feed.php';

// connect.php buffers and emits HTML intended for normal pages, not feeds.
ob_clean();
header('X-Content-Type-Options: nosniff');
if (!$db_ok) {
  http_response_code(503);
  header('Content-Type: text/plain; charset=UTF-8');
  exit('RSS feed unavailable: database connection failed. Please try again later.');
}

try {
  $result = $conn->query('SELECT post, poster, date_string, uu_id FROM Posts ORDER BY CAST(date_string AS UNSIGNED) DESC, uu_id DESC LIMIT 20');
  if (!$result) {
    throw new RuntimeException('Unable to retrieve RSS posts: ' . $conn->error);
  }
  $xml = rss_feed($result->fetch_all(MYSQLI_ASSOC), rss_site_url($_SERVER));
} catch (Throwable $e) {
  error_log('djmblog: RSS feed failed: ' . $e->getMessage());
  http_response_code(500);
  header('Content-Type: text/plain; charset=UTF-8');
  exit('Unable to generate the RSS feed. Please try again later.');
}

header('Content-Type: application/rss+xml; charset=UTF-8');
echo $xml;
