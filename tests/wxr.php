<?php
require __DIR__ . '/../account/wp-export/wxr.php';

function check($condition, $message) {
  if (!$condition) {
    throw new RuntimeException($message);
  }
}

$posts = array(
  array('uu_id' => 'own-id', 'post' => "<p>Fish & chips ]]> \x01</p>", 'date_string' => '1700000000'),
);
$comments = array(
  array('uu_id' => 'comment-1', 'postID' => 'own-id', 'post' => 'Own & comment', 'date_string' => '1700000001'),
  array('uu_id' => 'comment-2', 'postID' => 'other-id', 'post' => '<b>Other post comment</b>', 'date_string' => '1700000002'),
  array('uu_id' => 'comment-3', 'postID' => 'other-id', 'post' => 'Another comment', 'date_string' => '1700000003'),
);
$xml = wxr_export('user-id', 'A & B', $posts, $comments, 1700000100);
$doc = new DOMDocument();
check($doc->loadXML($xml), 'Export must be well-formed XML');
$xpath = new DOMXPath($doc);
$xpath->registerNamespace('wp', 'http://wordpress.org/export/1.2/');
$xpath->registerNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
$xpath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');
check($xpath->evaluate('string(/rss/channel/wp:wxr_version)') === '1.2', 'WXR version');
check($xpath->evaluate('string(/rss/channel/wp:author/wp:author_login)') === 'A & B', 'Author escaping');
check($xpath->evaluate('count(/rss/channel/item)') === 2.0, 'One post and one placeholder');
check($xpath->evaluate('string(/rss/channel/item[1]/content:encoded)') === '<p>Fish & chips ]]> </p>', 'Post content and XML control characters');
check($xpath->evaluate('string(/rss/channel/item[1]/wp:post_date_gmt)') === '2023-11-14 22:13:20', 'Post UTC date');
check($xpath->evaluate('string(/rss/channel/item[1]/wp:status)') === 'publish', 'Post status');
check($xpath->evaluate('count(/rss/channel/item[1]/wp:comment)') === 1.0, 'Comment on own post');
check($xpath->evaluate('string(/rss/channel/item[1]/wp:comment/wp:comment_content)') === 'Own & comment', 'Comment content');
check($xpath->evaluate('string(/rss/channel/item[2]/wp:status)') === 'draft', 'Placeholder is a draft');
check($xpath->evaluate('count(/rss/channel/item[2]/wp:comment)') === 2.0, 'Comments grouped by original post');
check($xpath->evaluate('string(/rss/channel/item[2]/wp:comment[1]/wp:comment_content)') === '<b>Other post comment</b>', 'HTML comment preserved');
check($xpath->evaluate('string(/rss/channel/item[1]/guid)') !== $xpath->evaluate('string(/rss/channel/item[2]/guid)'), 'Unique item GUIDs');

$empty = new DOMDocument();
check($empty->loadXML(wxr_export('user-id', 'user', array(), array(), 1700000100)), 'Empty export must be valid XML');
check($empty->getElementsByTagName('item')->length === 0, 'Empty export has no posts');
echo "WXR tests passed\n";
