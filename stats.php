<?php
// INCLUDES
include 'inc.php'; 

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
    <div class="container-fluid text-center">
      <div class="row-fluid">
        <div class="span12">
          <?php include 'inc.nav.php'; ?>
          <?php include 'inc.message.php'; ?>
          <header>
            <h1 class="title">Stats</h1>
          </header>
          <p>The rating of the images are calculated using the <a href="http://en.wikipedia.org/wiki/Elo_rating_system" title="Elo rating system">Elo rating system</a>.</p>
          <div class="scores">
            <?php print showStats(); ?>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    
    <?php include 'inc.footer.php'; ?>
  </body>
</html>

