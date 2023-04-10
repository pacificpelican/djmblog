<?php
require '../../post/connect.php';
include '../../post/postHeader.php';

//$currentDate = new date();
$content = $date_now = "";

if (isset($_SESSION["uu_id"])) 
{  
  $theUUID = $_GET["id"];
      $sql = "DELETE FROM Posts WHERE userID='" . $_SESSION["uu_id"] . "' AND uu_id='" . "$theUUID" . "'";
      
      $result = $conn->query($sql);
  

  echo "<div id='postPost'><b>Post was deleted</b>" . "<br />";
  echo "<i><a href='../../postview/?id=" . $_GET["id"] . "'>Back to post</a></i>" . "<br />";
  // echo $_POST["fname"] . "<br />";
  // //echo $_POST["pwd"] . "<br />";
  echo $content . "</div>";

}  
else 
{
  echo "<div id='postPost'><b>Post was  NOT deleted</b>" . "<br />";
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

