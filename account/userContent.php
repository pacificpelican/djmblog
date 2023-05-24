<?php
// require '../post/connect.php';
// include '../post/postHeader.php';
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>djmblog / posts</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css">

  <style>
    
  </style>
</head>

<body>
  <div id="container">
    <main id="fishcr">
      <h1>Your Posts</h1>

    <?php
    if  (isset($_SESSION["uu_id"])) {
      $theUUID = $_SESSION["uu_id"];
      $sql = "SELECT post, poster, date_string, uu_id FROM Posts WHERE userID='" . $theUUID . "' ORDER BY date_string DESC";
      //  var_dump($sql);
      $result = $conn->query($sql);

    //  var_dump($result);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo  "poster: " . $row["poster"] . " - post: " . $row["post"]. " | " . date("Y-m-d H:i:s", substr($row["date_string"], 0, 10)) . " | <a href='../postview/?id=" . $row["uu_id"] . "'>" . $row["uu_id"] . "</a>" . " <a href='../permalink/?id=" . $row["uu_id"] . "'>" . "📌" . "</a>" . " | <a href='../account/deletepost/?id=" . $row["uu_id"] . "'>" . "delete" . "</a>" . "<br>";
        }
      } 
      else {
        echo "0 results";
      }
    }
    else {
      echo "INVALID";
    }
    ?>

    </main>

    <main id="comment_town">
      <h1>Your Comments</h1>

    <?php
    if  (isset($_SESSION["uu_id"])) {
      $theUUID = $_SESSION["uu_id"];
      $sql = "SELECT postID, post, poster, date_string, uu_id FROM Comments WHERE userID='" . $theUUID . "' ORDER BY date_string DESC";
      //  var_dump($sql);
      $result = $conn->query($sql);

    //  var_dump($result);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo  "commenter: " . $row["poster"] . " - post: " . $row["post"]. " | " . date("Y-m-d H:i:s", substr($row["date_string"], 0, 10)) . " | <a href='../postview/?id=" . $row["postID"] . "'>" . " <a href='../postview/?id=" . $row["postID"] . "'>" . $row["postID"] . "</a>" . " <a href='../permalink/?id=" . $row["postID"] . "'>" .  "📌" . "</a>" . " | <a href='../account/deletecomment/?id=" . $row["uu_id"] . "'>" . "delete" . "</a>" . "<br>";
        }
      } 
      else {
        echo "0 results";
      }
    }
    else {
      echo "INVALID";
    }
    ?>

    </main>
    
  </div>
</body>

</html>
