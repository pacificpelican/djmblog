<?php
include './post/connect.php';
include './post/postHeader.php';

$username = $_SESSION["poster"] ?? null;
$uu_id = $_SESSION["uu_id"] ?? null;
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>djmblog posts</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css">

  <style>
    
  </style>
</head>

<body>
  <div id="container">
  

  <h2><a href="./posts">Posts</a></h2>

  <aside id="logo">
    <a href="https://github.com/pacificpelican/djmblog"><img id="logo" src="./static/img/pacificpelican_DJM_blog_logo_no_text_black_and_white_1aae8f91-7370-4b39-a681-5431d06fdd8c.webp" alt="logo" /></a>
  </aside>
    
  </div>
</body>

</html>
