<?php
require '../../post/connect.php';
include '../../post/postHeader.php';
csrf_check();

//$currentDate = new date();
$content = $date_now = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //  $content = $_POST["content"];
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


// set parameters and execute
$poster = isset($_POST["fname"]) ? trim((string)$_POST["fname"]) : "";
$raw_pw = isset($_POST["pwd"]) ? (string)$_POST["pwd"] : "";

if (register_is_throttled()) {
  http_response_code(429);
  header('Retry-After: ' . REGISTER_WINDOW_SECONDS);
  echo "<div id='postPost'><b>Too many accounts created from your connection.</b><br />Please try again later.</div>";
} elseif ($poster === "" || $raw_pw === "" || mb_strlen($poster) > 90) {
  echo "<div id='postPost'><b>Please provide a username (up to 90 characters) and a password.</b><br /><a href='./'>try again</a></div>";
} else {
  $check = $conn->prepare("SELECT 1 FROM Users WHERE poster = ?");
  $check->bind_param("s", $poster);
  $check->execute();

  if ($check->get_result()->num_rows > 0) {
    echo "<div id='postPost'><b>That username is already taken.</b><br /><a href='./'>try again</a></div>";
  } else {
    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO Users (poster, password_hash, date_string, uu_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $poster, $password_hash, $date_string, $uu_id);

    $password_hash = password_hash($raw_pw, PASSWORD_DEFAULT);
    $date_string = getdate()[0];
    $uu_id = guidv4();
    $stmt->execute();
    register_record();

    echo "<div id='postPost'><b>Username/Password saved</b>" . "<br />";
    echo h($poster) . "</div>";
  }
}
?> 

<style>
#postPost {
  font-family: Lato, Lucida Grande, Helvetica, sans-serif;
  font-size: calc(1.7rem);
}
</style>

