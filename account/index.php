<?php
require '../post/connect.php';
include '../post/postHeader.php';

$username = $_SESSION["poster"] ?? null;
$uu_id = $_SESSION["uu_id"] ?? null;
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dan McKeown . info</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css">

  <style>
    
  </style>
</head>

<body>
  <header>
    <a href='./register'>register</a>
</header>
  <div id="container">
    <h1> Account Page </h1>


  <section id="userInfoName">
  <?php 
    echo ($username=="") ? "<a href='./login'>login</a>" :   "<span>Username:</span>" . "<span id='uname'>" . $username . "<br /><a href='./logout'>log out</a>"; 
  ?>
  </section>
  <br /><br />

  <section id="userContent">
  <?php
  if  (isset($_SESSION["uu_id"])) {
    include './userContent.php';
  }
  ?>
  </section>

  <br /><br />
  <?php
  include '../post/accountFooter.php';
  ?>

  </div>
</body>

</html>
