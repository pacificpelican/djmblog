<?php
require '../../post/connect.php';
include '../../post/postHeader.php';

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


// prepare and bind
$stmt = $conn->prepare("INSERT INTO Users (poster, password_hash, date_string, uu_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $poster, $password_hash, $date_string, $uu_id);

// set parameters and execute
$poster = $_POST["fname"];
$raw_pw = $_POST["pwd"];

$password_hash = password_hash($raw_pw, PASSWORD_DEFAULT);

$date_string = getdate()[0];
$uu_id = guidv4();
$stmt->execute();

echo "<div id='postPost'><b>Username/Password saved</b>" . "<br />";
echo $_POST["fname"] . "<br />";

echo $content . "</div>";
?> 

<style>
#postPost {
  font-family: Lato, Lucida Grande, Helvetica, sans-serif;
  font-size: calc(1.7rem);
}
</style>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
  <title>Dan McKeown . info</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./static/post.css">

  <style>
    body {
      font-family: Fira Code, Inconsolata, Hack, Anonymous Pro, monospace;
    }
    textarea#tArea {
      width: calc(10pt + 75vw);
      height: calc(10pt + 55vh);
      font-family: Hack, Anonymous Pro, monospace;;
      font-size: 2.5rem;
    }
    input#input {
      width: calc(15vw + 5pt);
      height: calc(5vw + 1vh);
    }
  </style>
</head> -->

<!-- <body>
  <div id="container">
  
    <aside id="content">
      
    </aside>
    
  </div>
</body>

</html> -->
