<?php
require '../../post/connect.php';
include '../../post/postHeader.php';
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>djmblog / account / register</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css">

  <style>
    
  </style>
</head>

<body>
  <div id="container">
  
  <h1>Register</h1>
  <form action="./action_page.php" method="post">
  <label for="fname">Username:</label>
  <input type="text" id="fname" name="fname" required><br><br>
  <label for="fname">Password:</label>
  <input type="password" id="fname" name="pwd" required><br><br>
   
    <br />
    <input id="input" type="submit" value="Create Account">
  </form>
    
  </div>
</body>

</html>
