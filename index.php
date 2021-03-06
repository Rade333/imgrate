<?php
// INCLUDES
include 'inc.php'; 

// ACTIONS

dbConnect($config[0]);

$do = getValue('do');

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
if ($do == 'vote' && getValue('win') && getValue('lose')) {
  // Get image id's
  $win = getValue('win');
  $lose = getValue('lose');

  // Send vote
  voteImage($win, $lose);

  // Set message
  $message = new Message("Vote recorded successfully.", 0);
}
?>


<?php include 'inc.head.php'; ?>
    <title>ImgRate</title>
  </head>
  <body>
    <div class="container-fluid front text-center">
      <div class="row-fluid">
        <div class="span12">
          <header>
            <?php include 'inc.nav.php'; ?>
          </header>
          
          <?php include 'inc.message.php'; ?>
          <?php $img = selectImages(); ?>
          
          <article>
            <h1 class="title">Rate</h1>          
            <p>Below you see two randomly selected images. Choose which you like better by clicking the image. On the Stats-page you can find the ratings of the images.</p>
          
            <ul class="thumbnails">
              <li class="span3 offset3">
                <a href="?do=vote&win=<?php print $img[0]->id; ?>&lose=<?php print $img[1]->id; ?>" title="Vote this image" class="thumbnail">
                  <img src="images/<?php print $img[0]->src; ?>" class="fixed-size">
                </a>
              </li>
              <li class="span3">
                <a href="?do=vote&win=<?php print $img[1]->id; ?>&lose=<?php print $img[0]->id; ?>" title="Vote this image" class="thumbnail">
                  <img src="images/<?php print $img[1]->src; ?>" class="fixed-size">
                </a>
              </li>
            </ul>
          </article>
        </div>
      </div>

    <?php include 'inc.footer.php'; ?>
  </body>
</html>

