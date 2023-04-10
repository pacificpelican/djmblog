<?php
require '../../post/connect.php';
include '../../post/postHeader.php';
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
  <div id="container">
  
  <h1>Log in</h1>
  <form action="./action_page.php" method="post">
  <label for="fname">Username:</label>
  <input type="text" id="fname" name="fname" required><br><br>
  <label for="fname">Password:</label>
  <input type="password" id="fname" name="pwd"><br><br>
   
    <br />
    <input id="input" type="submit" value="logon">
  </form>
    
  </div>
</body>

</html>
