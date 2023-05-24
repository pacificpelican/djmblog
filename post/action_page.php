<?php
require 'connect.php';
include 'postHeader.php';

//$currentDate = new date();
$content = $date_now = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $content = $_POST["content"];
  //$date_now = $currentDate->format('Y-m-d H:i:s');
}

function guidv4($data = null) {
  // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
  $data = $data ?? random_bytes(16);
  assert(strlen($data) == 16);

  // Set version to 0100
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
  // Set bits 6-7 to 10
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

  // Output the 36 character UUID.
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

if (isset($_SESSION["uu_id"])) 
{  //// session variable uu_id exists /////
  //$user_uu_id = $_SESSION["uu_id"];

  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO Posts (userID, poster, post, date_string, uu_id) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $userID, $poster, $post, $date_string, $uu_id);

  // set parameters and execute
  $userID = $_SESSION["uu_id"];
  $poster = $_SESSION["poster"];
  $post = $content;
  $date_string = getdate()[0];
  $uu_id = guidv4();
  $stmt->execute();

  echo "<div id='postPost'><b>Post was created</b>" . "<br />";
  // echo $_POST["fname"] . "<br />";
  // //echo $_POST["pwd"] . "<br />";
  echo $content . "</div>";

}  
else 
{
  echo "<div id='postPost'><b>Post was NOT created</b>" . "<br />";
  // echo $_POST["fname"] . "<br />";
  // echo $_POST["pwd"] . "<br />";
  echo $content . "</div>";
}


?> 

<style>
#postPost {
  font-family: Lato, Lucida Grande, Helvetica, sans-serif;
  font-size: calc(1.7rem);
}
</style>

