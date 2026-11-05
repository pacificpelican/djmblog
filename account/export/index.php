<?php
require '../../post/connect.php';

if (!isset($_SESSION["uu_id"])) {
  http_response_code(403);
  echo "<p>Please <a href='../login'>log in</a> to export your data.</p>";
  exit;
}

$exportName = $_SESSION["poster"] ?? "";
$userID = $_SESSION["uu_id"];
$now = time();
$fileTitle = "djmblog_content_export_" . gmdate("Ymd_His", $now);

$stmt = $conn->prepare("SELECT post, date_string, uu_id FROM Posts WHERE userID = ? ORDER BY date_string DESC");
$stmt->bind_param("s", $userID);
$stmt->execute();
$posts = $stmt->get_result();

$stmt2 = $conn->prepare("SELECT postID, post, date_string, uu_id FROM Comments WHERE userID = ? ORDER BY date_string DESC");
$stmt2->bind_param("s", $userID);
$stmt2->execute();
$comments = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- the browser uses the page title as the suggested file name when saving as PDF -->
  <title><?php echo h($fileTitle); ?></title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../static/post.css">

  <style>
    #dbInfo { display: none; }
    #exportDoc { max-width: 48rem; padding: 1rem 2vw; line-height: 1.5; }
    #exportDoc h1 { margin-bottom: .2em; }
    #exportDoc .meta { color: #555; margin-top: 0; }
    #exportDoc .item { margin: 1.2em 0; page-break-inside: avoid; }
    #exportDoc .item .info { color: #555; font-size: .85rem; }
    #exportDoc footer { margin-top: 3em; padding-top: 1em; border-top: 1px solid #999; text-align: center; font-size: .95rem; }
    #exportDoc footer img { height: 4rem; width: auto; display: block; margin: 0 auto .4em; }
    #exportBar { padding: .6rem 2vw; background: #eee; }
    @media print { #exportBar { display: none; } }
  </style>
</head>

<body>
  <div id="exportBar">
    <button type="button" onclick="window.print()">🖨️ Print / save as PDF</button>
    <a href="../">back to account</a>
    <small>(in the print dialog, choose "Save as PDF" as the destination)</small>
  </div>

  <div id="exportDoc">
    <h1><?php echo h($exportName); ?></h1>
    <p class="meta">This is an export of your djmblog content (posts and comments).<br />
    Exported on <?php echo h(gmdate("Y-m-d H:i:s", $now)); ?> UTC</p>

    <h2>Posts</h2>
    <?php
    if ($posts->num_rows > 0) {
      while ($row = $posts->fetch_assoc()) {
        echo "<div class='item'><div class='info'>" . h(gmdate("Y-m-d H:i:s", (int)substr($row["date_string"], 0, 10))) . " UTC | Post ID: " . h($row["uu_id"]) . "</div>" . clean_html($row["post"]) . "</div>";
      }
    } else {
      echo "<p>No posts.</p>";
    }
    ?>

    <h2>Comments</h2>
    <?php
    if ($comments->num_rows > 0) {
      while ($row = $comments->fetch_assoc()) {
        echo "<div class='item'><div class='info'>" . h(gmdate("Y-m-d H:i:s", (int)substr($row["date_string"], 0, 10))) . " UTC | On post ID: " . h($row["postID"]) . "</div>" . clean_html($row["post"]) . "</div>";
      }
    } else {
      echo "<p>No comments.</p>";
    }
    ?>

    <footer>
      <img src="../../static/img/djmblog-logo.svg" alt="djmblog logo" />
      Created on <a href="https://github.com/pacificpelican/djmblog">djmblog</a>
    </footer>
  </div>

  <script>
    // open the print dialog automatically; the page stays usable if it is cancelled
    window.addEventListener('load', function () { window.print(); });
  </script>
</body>

</html>
