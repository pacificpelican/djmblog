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
    
  </div>
</body>

</html>
