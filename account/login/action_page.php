<?php
require '../../post/connect.php';
include '../../post/postHeader.php';
csrf_check();

$content = $date_now = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //  $content = $_POST["content"];
}

// set parameters and execute
$poster = isset($_POST["fname"]) ? (string)$_POST["fname"] : "";
$raw_pw = isset($_POST["pwd"]) ? (string)$_POST["pwd"] : "";

$login_ok = false;

if (login_is_throttled()) {
  http_response_code(429);
  header('Retry-After: ' . LOGIN_WINDOW_SECONDS);
  echo "<div id='postPost'><b>Too many failed login attempts.</b><br />Please wait a few minutes and try again.</div>";
} else {
  $stmt = $conn->prepare("SELECT poster, password_hash, uu_id FROM Users WHERE poster = ?");
  $stmt->bind_param("s", $poster);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  // Always run one hash verification so unknown users and wrong passwords take similar time
  $hash = $row ? $row["password_hash"] : '$2y$12$I9sFdvqlmlI0VExEsxjZDORU57Rku.0IHU.sQgdl2m2z7RsvGhjTm';

  if (password_verify($raw_pw, $hash) && $row) {
    $login_ok = true;
    session_regenerate_id(true);
    $_SESSION["poster"] = $row["poster"];
    $_SESSION["uu_id"] = $row["uu_id"];
    echo "<div id='postPost'><b>Logged in</b><br /><a href='../../post'>create post</a></div>";
  } else {
    login_record_failure();
    echo "<div id='postPost'><b>Login failed:</b> incorrect username or password.<br /><a href='./'>try again</a></div>";
  }
}

?> 

<style>
#postPost {
  font-family: Lato, Lucida Grande, Helvetica, sans-serif;
  font-size: calc(1.7rem);
}
</style>
