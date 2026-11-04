<?php
require '../post/connect.php';
include '../post/postHeader.php';

$username = $_SESSION["poster"] ?? null;
$uu_id = $_SESSION["uu_id"] ?? null;
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>djmblog</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css">

  <style>
    form.inlineLogout { display: inline; margin: 0; }
    form.inlineLogout button {
      background: none; border: 0; padding: 0; margin: 0;
      font: inherit; color: LinkText; text-decoration: underline; cursor: pointer;
    }
  </style>
</head>

<body>
  <header>
    <?php if ($open_registration) { echo "<a href='./register'>register</a>"; } ?>
</header>
  <div id="container">
    <h1> Account Page </h1>


  <section id="userInfoName">
  <?php 
    echo ($username=="") ? "<a href='./login'>login</a>" :   "<span>Username:</span>" . "<span id='uname'>" . h($username) . "<br /><form class='inlineLogout' action='./logout/' method='post'>" . csrf_field() . "<button type='submit'>log out</button></form>"; 
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
