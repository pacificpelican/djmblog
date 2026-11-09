<?php

// XML 1.0 does not permit most control characters, even as character references.
function wxr_text($value) {
  $escaped = htmlspecialchars((string)$value, ENT_QUOTES | ENT_XML1 | ENT_SUBSTITUTE, 'UTF-8');
  return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x{FFFE}\x{FFFF}]/u', '', $escaped);
}

function wxr_date($dateString) {
  return gmdate('Y-m-d H:i:s', (int)substr((string)$dateString, 0, 10));
}

function wxr_export($userID, $username, $posts, $comments, $now) {
  $items = array();
  foreach ($posts as $post) {
    $items[(string)$post['uu_id']] = array('post' => $post, 'comments' => array());
  }
  foreach ($comments as $comment) {
    $postID = (string)$comment['postID'];
    if (!isset($items[$postID])) {
      $items[$postID] = array('post' => null, 'comments' => array());
    }
    $items[$postID]['comments'][] = $comment;
  }

  $author = wxr_text($username);
  $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  $xml .= "<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:wp=\"http://wordpress.org/export/1.2/\">\n<channel>\n";
  $xml .= "<title>djmblog export</title><link></link><description>User content export</description>\n";
  $xml .= '<pubDate>' . gmdate('D, d M Y H:i:s +0000', $now) . "</pubDate>\n";
  $xml .= "<wp:wxr_version>1.2</wp:wxr_version><wp:base_site_url></wp:base_site_url><wp:base_blog_url></wp:base_blog_url>\n";
  $xml .= "<wp:author><wp:author_id>1</wp:author_id><wp:author_login>$author</wp:author_login><wp:author_email></wp:author_email><wp:author_display_name>$author</wp:author_display_name><wp:author_first_name></wp:author_first_name><wp:author_last_name></wp:author_last_name></wp:author>\n";

  $itemID = 0;
  $commentID = 0;
  foreach ($items as $sourceID => $item) {
    $itemID++;
    $post = $item['post'];
    $placeholder = $post === null;
    $title = ($placeholder ? 'Comments on djmblog post ' : 'djmblog post ') . $sourceID;
    $body = $placeholder
      ? 'This draft represents a post that was not included in this export. The comments below were written by the exporting user. Original post ID: ' . $sourceID
      : $post['post'];
    $date = wxr_date($placeholder ? $item['comments'][0]['date_string'] : $post['date_string']);
    $guid = 'urn:djmblog:' . $userID . ':' . ($placeholder ? 'comment-parent:' : 'post:') . $sourceID;
    $status = $placeholder ? 'draft' : 'publish';

    $xml .= "<item>\n<title>" . wxr_text($title) . "</title>\n";
    $xml .= '<guid isPermaLink="false">' . wxr_text($guid) . "</guid>\n";
    $xml .= "<dc:creator>$author</dc:creator><content:encoded>" . wxr_text($body) . "</content:encoded>\n";
    $xml .= "<wp:post_id>$itemID</wp:post_id><wp:post_date>$date</wp:post_date><wp:post_date_gmt>$date</wp:post_date_gmt>\n";
    $xml .= "<wp:status>$status</wp:status><wp:post_type>post</wp:post_type><wp:post_parent>0</wp:post_parent><wp:comment_status>open</wp:comment_status><wp:ping_status>closed</wp:ping_status>\n";
    foreach ($item['comments'] as $comment) {
      $commentID++;
      $commentDate = wxr_date($comment['date_string']);
      $xml .= "<wp:comment><wp:comment_id>$commentID</wp:comment_id><wp:comment_author>$author</wp:comment_author>";
      $xml .= "<wp:comment_author_email></wp:comment_author_email><wp:comment_author_url></wp:comment_author_url><wp:comment_author_IP></wp:comment_author_IP>";
      $xml .= "<wp:comment_date>$commentDate</wp:comment_date><wp:comment_date_gmt>$commentDate</wp:comment_date_gmt>";
      $xml .= '<wp:comment_content>' . wxr_text($comment['post']) . '</wp:comment_content>';
      $xml .= "<wp:comment_approved>1</wp:comment_approved><wp:comment_type></wp:comment_type><wp:comment_parent>0</wp:comment_parent><wp:comment_user_id>0</wp:comment_user_id></wp:comment>\n";
    }
    $xml .= "</item>\n";
  }
  return $xml . "</channel>\n</rss>\n";
}
