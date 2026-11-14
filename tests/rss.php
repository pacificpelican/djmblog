<?php
session_start(array('save_path' => sys_get_temp_dir()));
define('DB_OPTIONAL', true);
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR . 'connect.php';
ob_end_clean();
session_destroy();
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'rss' . DIRECTORY_SEPARATOR . 'feed.php';

function check($condition, $message) {
  if (!$condition) {
    throw new RuntimeException($message);
  }
}

$siteUrl = rss_site_url(array('HTTPS' => 'on', 'HTTP_HOST' => 'example.com:8443', 'SCRIPT_NAME' => '/blog/rss/index.php'));
check($siteUrl === 'https://example.com:8443/blog', 'Subdirectory installation and HTTPS port');
check(rss_site_url(array('HTTP_HOST' => 'localhost', 'SCRIPT_NAME' => '/rss/index.php')) === 'http://localhost', 'Root installation');
try {
  rss_site_url(array('HTTP_HOST' => "example.com\r\nInvalid: header", 'SCRIPT_NAME' => '/rss/index.php'));
  throw new RuntimeException('Invalid host must be rejected');
} catch (RuntimeException $e) {
  check($e->getMessage() === 'Invalid RSS request host', 'Invalid host rejection');
}
$posts = array();
for ($i = 25; $i > 0; $i--) {
  $posts[] = array(
    'uu_id' => 'post &' . $i,
    'poster' => 'A & B',
    'date_string' => (string)(1700000000 + $i),
    'post' => '<p>' . str_repeat('Full content & text. ', 200) . "]]> \x01</p>"
      . '<a href="../posts/?x=1&amp;y=2">Posts</a><img src="../static/photo.png" alt="Photo">'
      . '<script>alert(1)</script><img src="javascript:alert(1)" onerror="alert(1)">',
  );
}
$doc = new DOMDocument();
check($doc->loadXML(rss_feed($posts, $siteUrl)), 'Feed must be well-formed XML');
$xpath = new DOMXPath($doc);
$xpath->registerNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
$xpath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');
$xpath->registerNamespace('atom', 'http://www.w3.org/2005/Atom');
check($xpath->evaluate('count(/rss/channel/item)') === 20.0, 'At most 20 posts');
check($xpath->evaluate('string(/rss/channel/item[1]/title)') === 'djmblog post post &25', 'Newest post first');
check($xpath->evaluate('string(/rss/channel/item[20]/title)') === 'djmblog post post &6', 'Oldest included post');
check($xpath->evaluate('string(/rss/channel/item[1]/dc:creator)') === 'A & B', 'Author escaping');
check($xpath->evaluate('string(/rss/channel/atom:link/@href)') === $siteUrl . '/rss/', 'Feed self URL');
$link = $siteUrl . '/postview/?id=post%20%2625';
check($xpath->evaluate('string(/rss/channel/item[1]/link)') === $link, 'Absolute encoded post link');
check($xpath->evaluate('string(/rss/channel/item[1]/guid)') === $link, 'Stable permalink GUID');
check($xpath->evaluate('string(/rss/channel/item[1]/pubDate)') === 'Tue, 14 Nov 2023 22:13:45 +0000', 'UTC RSS date');
$body = $xpath->evaluate('string(/rss/channel/item[1]/content:encoded)');
check(strpos($body, str_repeat('Full content &amp; text. ', 200)) !== false, 'Full formatted content is not truncated');
check(strpos($body, ']]&gt;') !== false && strpos($body, "\x01") === false, 'XML delimiters and controls');
check(strpos($body, $siteUrl . '/static/photo.png') !== false, 'Absolute image URL');
check(strpos($body, $siteUrl . '/posts/?x=1&amp;y=2') !== false, 'Absolute link URL');
check(strpos($body, '<script') === false && strpos($body, 'javascript:') === false && strpos($body, 'onerror') === false, 'Unsafe HTML removed');
check($xpath->evaluate('string(/rss/channel/item[1]/description)') === $body, 'Full content for basic RSS readers');
check(rss_absolute_url('#section', $link) === $link . '#section', 'Fragment links retain post ID');
check(rss_absolute_url('//cdn.example.com/image.png', $link) === 'https://cdn.example.com/image.png', 'Protocol-relative image');
check(rss_absolute_url('?page=2', $link) === $siteUrl . '/postview/?page=2', 'Query-relative link');
check(rss_absolute_url('photo.png', $link) === $siteUrl . '/postview/photo.png', 'Relative path stays in post directory');
check(rss_absolute_url('/', $link) === 'https://example.com:8443/', 'Root-relative URL');
check(rss_absolute_url('mailto:hello@example.com', $link) === 'mailto:hello@example.com', 'Mail links are preserved');
$empty = new DOMDocument();
check($empty->loadXML(rss_feed(array(), $siteUrl)), 'Empty feed is valid XML');
check($empty->getElementsByTagName('item')->length === 0, 'Empty feed has no items');
$single = new DOMDocument();
check($single->loadXML(rss_feed(array($posts[0]), $siteUrl)), 'Small feed is valid XML');
check($single->getElementsByTagName('item')->length === 1, 'Fewer than 20 posts are included without padding');
echo "RSS tests passed\n";
