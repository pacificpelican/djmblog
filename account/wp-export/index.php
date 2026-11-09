<?php
require '../../post/connect.php';
require 'wxr.php';

if (!isset($_SESSION['uu_id'])) {
  http_response_code(403);
  echo "<p>Please <a href='../login'>log in</a> to export your data.</p>";
  exit;
}

$userID = $_SESSION['uu_id'];
$username = $_SESSION['poster'] ?? '';

try {
  $stmt = $conn->prepare('SELECT post, date_string, uu_id FROM Posts WHERE userID = ? ORDER BY date_string DESC');
  if (!$stmt || !$stmt->bind_param('s', $userID) || !$stmt->execute() || !($posts = $stmt->get_result())) {
    throw new RuntimeException('Unable to retrieve posts for WordPress export');
  }
  $stmt2 = $conn->prepare('SELECT postID, post, date_string, uu_id FROM Comments WHERE userID = ? ORDER BY date_string DESC');
  if (!$stmt2 || !$stmt2->bind_param('s', $userID) || !$stmt2->execute() || !($comments = $stmt2->get_result())) {
    throw new RuntimeException('Unable to retrieve comments for WordPress export');
  }
  $now = time();
  $xml = wxr_export($userID, $username, $posts->fetch_all(MYSQLI_ASSOC), $comments->fetch_all(MYSQLI_ASSOC), $now);
} catch (Throwable $e) {
  error_log('djmblog: WordPress export failed: ' . $e->getMessage());
  http_response_code(500);
  echo '<p>Unable to export your content. Please try again later.</p>';
  exit;
}

// connect.php starts an output buffer and emits a database status element.
ob_clean();
header('Content-Type: application/xml; charset=UTF-8');
header('Content-Disposition: attachment; filename="djmblog_wordpress_export_' . gmdate('Ymd_His', $now) . '.xml"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
echo $xml;
