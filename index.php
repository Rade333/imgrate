<?php
// INCLUDES
include 'inc.php'; 

// ACTIONS

dbConnect($config[0]);

$do = $_GET['do'];

// Do: Log out
if ($do == 'logout') {
  // do logout
  if (checkLogin()) {
    setcookie($cookie_name, $cookie_value_logout, $cookie_expire, $cookie_path, $cookie_domain, 0);
    header('Location: ' . $request_uri);
  }
  else {
    $message = new Message('You are now logged out.', 0);
  }
}

// Do: Vote
if ($do == 'vote') {
  // Get images
  $win = $_GET['win'];
  $lose = $_GET['lose'];

  // Send vote
  voteImage($win, $lose);

  // Set message
  $message = new Message("Vote recorded successfully.", 0);
}

// Do: confirmDelete
if ($do == 'confirmDelete') {
  // Call function and set message
  $message = new Message(confirmDelete(getValue('id')));
}

// Do: Delete
if ($do == 'delete') {
  // Call function and set message
  $message = new Message(deleteImage(getValue('id')));
}
?>


<?php include 'inc.head.php'; ?>
    <title>ImgRate</title>
  </head>
  <body>
    <div class="container-fluid front text-center">
      <div class="row-fluid">
        <div class="span12">
        <?php include 'inc.nav.php'; ?>
        <?php include 'inc.message.php'; ?>
        
          <?php $img = selectImages(); ?>
          <h1 class="title">Rate</h1>
          <p>Below you see two randomly selected images. Choose which you like better by clicking the image. On the Stats-page you can find the ratings of the images.</p>
          <div class="images">
            <div class="images region">
            <div class="img first">
              <a href="?do=vote&win=<?php print $img[0]->id; ?>&lose=<?php print $img[1]->id; ?>" title="Vote this image">
                <img src="images/<?php print $img[0]->src; ?>" />
              </a>
            </div>
            <div class="img last">
              <a href="?do=vote&win=<?php print $img[1]->id; ?>&lose=<?php print $img[0]->id; ?>" title="Vote this image">
                <img src="images/<?php print $img[1]->src; ?>" />
              </a>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include 'inc.footer.php'; ?>
  </body>
</html>

