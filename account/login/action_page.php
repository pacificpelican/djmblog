<?php
require '../../post/connect.php';
include '../../post/postHeader.php';
csrf_check();

$content = $date_now = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //  $content = $_POST["content"];
}

// set parameters and execute
$poster = $_POST["fname"];
$raw_pw = $_POST["pwd"];

$password_hash = password_hash($raw_pw, PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT poster, password_hash, uu_id FROM Users WHERE poster = ?");
$stmt->bind_param("s", $poster);
$stmt->execute();
$result = $stmt->get_result();

$login_result = "";

$uu_id_result = '';

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "login found <br />";
    
    if (password_verify($raw_pw, $row["password_hash"])) {
      echo "<br /><b>password correct</b>" . "<br /><a href='../../post'>create post</a>";

      $uu_id_result = $row["uu_id"];

      session_regenerate_id(true);
      $_SESSION["poster"] = $row["poster"];
      $_SESSION["uu_id"] = $uu_id_result;
    }
  }
} else {
  echo "0 results found for that login/password";
}

?> 

<style>
#postPost {
  font-family: Lato, Lucida Grande, Helvetica, sans-serif;
  font-size: calc(1.7rem);
}
</style>
