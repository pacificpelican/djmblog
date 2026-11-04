<?php
require '../../post/connect.php';
include '../../post/postHeader.php';
csrf_check();

//$currentDate = new date();
$content = $date_now = "";

if (isset($_SESSION["uu_id"])) 
{  
  $theUUID = isset($_POST["id"]) ? $_POST["id"] : "";
      $stmt = $conn->prepare("DELETE FROM Comments WHERE userID = ? AND uu_id = ?");
      $stmt->bind_param("ss", $_SESSION["uu_id"], $theUUID);
      $stmt->execute();
  

  echo "<div id='postPost'><b>Comment was deleted</b>" . "<br />";
  echo "<i><a href='../../account'>Back to account</a></i>" . "<br />";
  // echo h($_POST["fname"]) . "<br />";
  // //echo $_POST["pwd"] . "<br />";
  echo clean_html($content) . "</div>";

}  
else 
{
  echo "<div id='postPost'><b>Comment was NOT deleted</b>" . "<br />";
  // echo h($_POST["fname"]) . "<br />";
  // echo $_POST["pwd"] . "<br />";
  echo clean_html($content) . "</div>";
}


?> 

<style>
#postPost {
  font-family: Lato, Lucida Grande, Helvetica, sans-serif;
  font-size: calc(1.7rem);
}
</style>

