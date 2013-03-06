<?php
// INCLUDES
include 'inc.php'; 
// CONFIG

// Database
$config[0] = "radefi_imgrate";
// Image table
$config[1] = "tbl_images";
// Votes table
$config[2] = "tbl_votes";

// FUNCTIONS

// Select images from database and create objects
function dbGetImages($query) {
  $result = mysql_query($query) or die(mysql_error());
  $i = 0;
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $img[$i] = new Image($row['id']);
    $i++;
  }
  return $img;
}

// Shows current statistics
function showStats() {
  global $config;
  $imgs = dbGetImages("SELECT `id` FROM `$config[1]` ORDER BY `rating` DESC");

  $i = 1;
  print '<ul class="scores">';
  foreach ($imgs as $img) {
    if ($i == 4) {
      print '<li class="score score-break"></li>';
    }
    ?>
      <li class="score score-<?php print $i; ?>">
        <img src="images/<?php print $img->src; ?>" />
        <?php if (checkLogin()): ?>
        <span class="delete"><a href="?do=confirmDelete&id=<?php print $img->id; ?>">Delete</a></span>
        <?php endif; ?>
        <span class="rating">Rating: <?php print $img->rating; ?></span>
        <span class="wins"><?php print $img->wins + $img->losses; ?> appearances</span>
        <!--<span class="wins"><?php print $img->wins; ?> wins</span>
        <span class="losses"><?php print $img->losses; ?> losses</span>-->
      </li>
    <?php
    $i++;
  }
  print '</ul>';
}

function confirmDelete($id) {
  $image = new Image($id);

  if (!$image->id) {
    $text = 'Error: File could not be found.';
    $type = 1;
  }
  else {
    $text = 'Are you sure you want to remove image <em>' . $image->src . '</em>?<br />This action cannot be undone.';
    $text .= '<br /><a href="?do=delete&id=' . $image->id . '">Delete</a> | <a href="?">Cancel</a>';
    $type = 2;
  }
  return array($text, $type);
}

function deleteImage($id) {
  global $config;

  // Create instance
  $image = new Image($id);

  if (!$image->id) {
    $text = 'Error: Image could not be deleted.';
    $type = 1;
  }
  else {
    // Delete from database
    mysql_query("DELETE FROM `$config[1]` WHERE `id` = $id") or die(mysql_error());

    // Delete from server
    unlink('images/' . $image->src);

    $text = 'Image <em>' . $image->src . '</em> was successfully deleted.';
    $type = 0;
  }
  return array($text, $type);
}

function getValue($field) {
  return $_GET[$field];
}

// ACTIONS

dbConnect($config[0]);

$do = $_GET['do'];

// Do: confirmDelete
if ($do == 'confirmDelete') {
  if (checkLogin()) {
    // Call function and set message
    $message = setMessage(confirmDelete(getValue('id')));
  }
  else {
    $message = msgNotAuthorized();
  }
}

// Do: Delete
if ($do == 'delete') {
  if (checkLogin()) {
    // Call function and set message
    $message = setMessage(deleteImage(getValue('id')));
  }
  else {
    $message = msgNotAuthorized();
  }
}

// Do:
?>


<?php include 'inc.head.php'; ?>
    <title>Stats | ImgRate</title>
  </head>
  <body>
    <?php include 'inc.message.php'; ?>
    <div class="container">
    <?php include 'inc.nav.php'; ?>
    <h1 class="title">Stats</h1>
    <div class="description">The rating of the images are calculated using the <a href="http://en.wikipedia.org/wiki/Elo_rating_system" title="Elo rating system">Elo rating system</a>.</div>
    <div class="scores">
      <?php print showStats(); ?>
      <div class="clear"></div>
    </div>
    
    <?php include 'inc.footer.php'; ?>
  </body>
</html>

