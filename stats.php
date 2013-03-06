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
    <div class="container-fluid text-center">
    <?php include 'inc.nav.php'; ?>
    <h1 class="title">Stats</h1>
    <div class="description">The rating of the images are calculated using the <a href="http://en.wikipedia.org/wiki/Elo_rating_system" title="Elo rating system">Elo rating system</a>.</div>
    <div class="scores">
      <?php print showStats(); ?>
      <div class="clearfix"></div>
    </div>
    
    <?php include 'inc.footer.php'; ?>
  </body>
</html>

