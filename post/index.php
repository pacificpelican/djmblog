<?php
include 'connect.php';
include 'postHeader.php';

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
  

  <form action="./action_page.php" method="post">
  <label for="fname">Username:</label>
  <input type="text" id="fname" name="fname" value="<?php echo $username; ?>" /> <br><br>

    <textarea id="tArea" name="content" required></textarea>
    <br />
    <input id="input" type="submit" value="Post" />
  </form>
    
  </div>
</body>

</html>
