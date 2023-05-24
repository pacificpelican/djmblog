<?php
require '../post/connect.php';
include '../post/postHeader.php';
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
      <h1>Latest Posts</h1>

    <?php
      $sql = "SELECT post, poster, date_string, uu_id FROM Posts ORDER BY date_string DESC LIMIT 5";
      $result = $conn->query($sql);

    //  var_dump($result);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          $filteredContent = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $row["post"]);
          echo  "poster: " . $row["poster"] . " - post: " . $filteredContent . " | " . date("Y-m-d H:i:s", substr($row["date_string"], 0, 10)) . " | <a href='../postview/?id=" . $row["uu_id"] . "'>" . $row["uu_id"] . "</a>" . "<br>";
        }
      } 
      else {
        echo "0 results";
      }
    ?>

    </main>
    
  </div>
</body>

</html>
