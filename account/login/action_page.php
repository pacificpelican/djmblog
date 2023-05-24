<?php
require '../../post/connect.php';
include '../../post/postHeader.php';

//$currentDate = new date();
$content = $date_now = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $content = $_POST["content"];
  //$date_now = $currentDate->format('Y-m-d H:i:s');
}


// set parameters and execute
$poster = $_POST["fname"];
$raw_pw = $_POST["pwd"];

$password_hash = password_hash($raw_pw, PASSWORD_DEFAULT);

//  $sql = "SELECT poster, uu_id FROM Users WHERE password_hash='" . "$password_hash" . "'"; 
$sql = "SELECT poster, password_hash, uu_id FROM Users WHERE poster='" . "$poster" . "'"; 

$result = $conn->query($sql);

$login_result = "";

$uu_id_result = '';

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    //  echo  "poster: " . $row["poster"] .  " | " . $row["uu_id"] . "<br>";
    echo "login found <br />";
  //  echo "password entered, hashed: " . $password_hash . "<br />  |   password in database: " . $row["password_hash"];

    //if ($row["password_hash"] == $password_hash) {
    if (password_verify($raw_pw, $row["password_hash"])) {
      echo "<br /><b>password correct</b>" . "<br /><a href='../../post'>create post</a>";

      $uu_id_result = $row["uu_id"];

      $_SESSION["poster"] = $_POST["fname"];
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
