<?php
require '../post/connect.php';
//  include '../post/postHeader.php';
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>djmblog / post id <?php echo h($_GET["id"] ?? ""); ?></title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css" />

  <style>
    #postHeader {
      font-family: Futura, display;
      color: white;
      background: gray;
      padding-inline-start: calc(1vw);
    }
    #postHeader a {
      color: white;
    }
  </style>
</head>

<body>
  <div id="container">
    <header id="postHeader">
      <h1><a href="#">djmblog</a> posts</h1>

    </header>

  
    <main id="fishcr">

    <?php
      $theUUID = isset($_GET["id"]) ? (string)$_GET["id"] : "";
      $stmt = $conn->prepare("SELECT post, poster, date_string, uu_id FROM Posts WHERE uu_id = ?");
      $stmt->bind_param("s", $theUUID);
      $stmt->execute();
      $result = $stmt->get_result();

      $stmt_comments = $conn->prepare("SELECT post, poster, date_string, uu_id FROM Comments WHERE postID = ? ORDER BY date_string ASC");
      $stmt_comments->bind_param("s", $theUUID);
      $stmt_comments->execute();
      $result_comments = $stmt_comments->get_result();

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          $filteredContent = clean_html($row["post"]);
          echo  "poster: " . h($row["poster"]) . " <br /><main class='post'>" . $filteredContent . "</main><br /><span class='postInfo'> Date: " . date("Y-m-d H:i:s", substr($row["date_string"], 0, 10)) . " | Post ID: " . h($row["uu_id"]) . "</span><br>";

          if ($result_comments->num_rows > 0) {
            // output data of each row
            while($row_comments = $result_comments->fetch_assoc()) {
              $filteredCommentContent = clean_html($row_comments["post"]);
              echo "<hr class='commentSpacer' /><aside class='comment'>";
              echo  "commenter: " . h($row_comments["poster"]) . " <br /><main class='comment'>" . $filteredCommentContent . "</main><br /><span class='commentInfo'> Date: " . date("Y-m-d H:i:s", substr($row_comments["date_string"], 0, 10)) . " | Post ID: " . h($row_comments["uu_id"]) . "</span><br>";
              echo "</aside>";
            }
          } else {
            // no comments
          }

        }
        if (isset($_SESSION["uu_id"])) {
          echo "<br /><form action='./action_page.php' method='post'>" . csrf_field();
          echo "<textarea id='tArea' name='content' required></textarea><br />";
          echo "<input type='hidden' id='postId' name='postID' value='" . h($theUUID) . "'>";
          echo "<input id='input' type='submit' value='Post' /></form>";
        }
      } else {
        echo "0 results";
      }
    ?>

    </main>
    
  </div>
</body>

</html>
