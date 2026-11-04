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
    form.inlineDelete { display: inline; margin: 0; }
    form.inlineDelete button {
      background: none; border: 0; padding: 0; margin: 0;
      font: inherit; color: LinkText; text-decoration: underline; cursor: pointer;
    }
  </style>
</head>

<body>
  <div id="container">
    <main id="fishcr">
      <h1>Your Posts</h1>

    <?php
    if  (isset($_SESSION["uu_id"])) {
      $theUUID = $_SESSION["uu_id"];
      $stmt = $conn->prepare("SELECT post, poster, date_string, uu_id FROM Posts WHERE userID = ? ORDER BY date_string DESC");
      $stmt->bind_param("s", $theUUID);
      $stmt->execute();
      $result = $stmt->get_result();

    //  var_dump($result);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo  "poster: " . $row["poster"] . " - post: " . $row["post"]. " | " . date("Y-m-d H:i:s", substr($row["date_string"], 0, 10)) . " | <a href='../postview/?id=" . $row["uu_id"] . "'>" . $row["uu_id"] . "</a>" . " <a href='../permalink/?id=" . $row["uu_id"] . "'>" . "📌" . "</a>" . " | <form class='inlineDelete' action='../account/deletepost/' method='post'>" . csrf_field() . "<input type='hidden' name='id' value='" . htmlspecialchars($row["uu_id"], ENT_QUOTES) . "'><button type='submit'>delete</button></form><br>";
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
      $stmt = $conn->prepare("SELECT postID, post, poster, date_string, uu_id FROM Comments WHERE userID = ? ORDER BY date_string DESC");
      $stmt->bind_param("s", $theUUID);
      $stmt->execute();
      $result = $stmt->get_result();

    //  var_dump($result);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo  "commenter: " . $row["poster"] . " - post: " . $row["post"]. " | " . date("Y-m-d H:i:s", substr($row["date_string"], 0, 10)) . " | <a href='../postview/?id=" . $row["postID"] . "'>" . " <a href='../postview/?id=" . $row["postID"] . "'>" . $row["postID"] . "</a>" . " <a href='../permalink/?id=" . $row["postID"] . "'>" .  "📌" . "</a>" . " | <form class='inlineDelete' action='../account/deletecomment/' method='post'>" . csrf_field() . "<input type='hidden' name='id' value='" . htmlspecialchars($row["uu_id"], ENT_QUOTES) . "'><button type='submit'>delete</button></form><br>";
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
