<?php
require '../../post/connect.php';
include '../../post/postHeader.php';
csrf_check();

// Destroy the session and expire its cookie
$_SESSION = array();
if (ini_get("session.use_cookies")) {
  $cp = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000, $cp['path'], $cp['domain'], $cp['secure'], $cp['httponly']);
}
session_destroy();

echo "<br /><b>LOGGED OUT</b> |  <a href='../.'>account page</a></b>";

  

