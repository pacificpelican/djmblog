<?php
include 'connect.php';
include 'postHeader.php';

$username = $_SESSION["poster"];
$uu_id = $_SESSION["uu_id"];
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>404</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css">

  <style>
    
  </style>
</head>

<body>
  <div id="container">
  

    <h2>404 NOT FOUND</h2>

    <br />
    <br />
    
    <img id="fimg" src="./static/img/404-flamingos.jpg" alt="404" />

  </div>

  <style>
     #fimg {
      width: calc(30vw + 4rem);
      height: auto;
      border-radius: 1.3rem;
    }
    div#container {
      background: #edf1f2;
    }
  </style>
</body>

</html>
