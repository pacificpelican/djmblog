<?php
require '../post/connect.php';
include '../post/postHeader.php';
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>djmblog / posts</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css">
  <link rel="alternate" type="application/rss+xml" title="djmblog posts" href="../rss/">

  <style>
    
  </style>
</head>

<body>
  <div id="container">
    <main id="fishcr">
      <h1>Latest Posts</h1>

    <?php
      $sql = "SELECT post, poster, date_string, uu_id FROM Posts ORDER BY date_string DESC LIMIT 5";
      $result = $conn->query($sql);

    //  var_dump($result);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          $filteredContent = clean_html($row["post"]);
          echo  "poster: " . h($row["poster"]) . " - post: " . $filteredContent . " | " . date("Y-m-d H:i:s", substr($row["date_string"], 0, 10)) . " | <a href='../postview/?id=" . urlencode($row["uu_id"]) . "'>" . h($row["uu_id"]) . "</a>" . "<br>";
        }
      } 
      else {
        echo "0 results";
      }
    ?>

      <a class="rss-button" href="../rss/" title="Subscribe to the latest 20 posts with full content">
        <svg class="rss-icon" viewBox="0 0 48 48" width="40" height="40" aria-hidden="true" focusable="false">
          <defs>
            <linearGradient id="rss-purple" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0" stop-color="#c893ff"/>
              <stop offset=".48" stop-color="#8a3cce"/>
              <stop offset="1" stop-color="#4c1688"/>
            </linearGradient>
            <linearGradient id="rss-shine" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0" stop-color="#fff" stop-opacity=".65"/>
              <stop offset="1" stop-color="#fff" stop-opacity="0"/>
            </linearGradient>
          </defs>
          <rect x="1" y="1" width="46" height="46" rx="11" fill="url(#rss-purple)" stroke="#4c1688"/>
          <path d="M3 19V12a9 9 0 0 1 9-9h24a9 9 0 0 1 9 9v5Q24 29 3 19Z" fill="url(#rss-shine)"/>
          <g fill="none" stroke="#fff" stroke-width="4.5">
            <path d="M12 12a24 24 0 0 1 24 24"/>
            <path d="M12 22a14 14 0 0 1 14 14"/>
          </g>
          <circle cx="13" cy="35" r="3.5" fill="#fff"/>
        </svg>
        <span>RSS feed</span>
      </a>

    </main>
    
  </div>
</body>

</html>
