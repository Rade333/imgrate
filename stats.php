<?php
// INCLUDES
include 'inc.php'; 

// ACTIONS

dbConnect($config[0]);

$do = getValue('do');

// Do: confirmDelete
if ($do == 'confirmDelete' && getValue('id')) {
  if (checkLogin()) {
    // Call function and set message
    $message = setMessage(confirmDelete(getValue('id')));
  }
  else {
    $message = msgNotAuthorized();
  }
}

// Do: Delete
if ($do == 'delete' && getValue('id')) {
  if (checkLogin()) {
    // Call function and set message
    $message = setMessage(deleteImage(getValue('id')));
  }
  else {
    $message = msgNotAuthorized();
  }
}

?>


<?php include 'inc.head.php'; ?>
    <title>Stats | ImgRate</title>
  </head>
  <body>
    <div class="container-fluid text-center">
      <div class="row-fluid">
        <div class="span12">
          <header>
            <?php include 'inc.nav.php'; ?>
          </header>
          
          <?php include 'inc.message.php'; ?>
          
          <article>
            <h1 class="title">Stats</h1>
            <p>The rating of the images are calculated using the <a href="http://en.wikipedia.org/wiki/Elo_rating_system" title="Elo rating system">Elo rating system</a>.</p>
          
            <div class="scores">
              <?php print showStats(); ?>
              <div class="clearfix"></div>
            </div>
          </article>
        </div>
      </div>
    
    <?php include 'inc.footer.php'; ?>
  </body>
</html>

