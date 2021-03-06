<?php
// FUNCTIONS

// Connect to database
function dbConnect($database) {
  $link = mysql_connect("localhost", "root", "root") or die(mysql_error());
  mysql_select_db($database) or die(mysql_error());
}

function checkLogin() {
  if (isset($_COOKIE['imgrate_auth']) && $_COOKIE['imgrate_auth'] == 'success') {
    return TRUE;
  }
  else {
    return FALSE;
  }
}

function setMessage($array) {
  list($text, $type) = $array;
  return new Message($text, $type);
}

function msgNotAuthorized() {
  return new Message('Error: Not authorized to perform action.', 1);
}

// Selects randomly two records from database
function selectImages() {
  $img1 = new Image('random');
  $img2 = new Image('random', $img1->id);
  $img = array($img1, $img2);
  return $img;
}

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

// Save users vote
function voteImage($win, $lose) {
  global $config;

  // Set scores
  setScores("wins", $win);
  setScores("losses", $lose);

  // Set new ratings
  setRatings($win, $lose);

  // Record vote
  mysql_query("INSERT INTO `$config[2]` (`win`, `lose`) VALUES ($win, $lose)") or die(mysql_error());
}

// Save the scores of a vote in database
function setScores($game, $id) {
  global $config;

  // Set win or loss
  $query  = mysql_query("SELECT `$game` FROM `$config[1]` WHERE `id`=$id LIMIT 1") or die(mysql_error());
  $result = mysql_fetch_assoc($query);
  $score  = $result[$game] + 1;
  mysql_query("UPDATE `$config[1]` SET `$game` = $score WHERE `id` = $id") or die(mysql_error());
}

// Calculate and save new ratings
function setRatings($win, $lose) {
  global $config;

  // Set constant
  $K = 32;

  // Winner is always A, loser is B
  $Wa = 1;
  $Wb = 0;

  // Get winners current rating
  $query  = mysql_query("SELECT `id`, `rating` FROM `$config[1]` WHERE `id` = $win LIMIT 1") or die(mysql_error());
  $result = mysql_fetch_assoc($query);
  $Ra     = $result['rating'];

  // Get losers current rating
  $query  = mysql_query("SELECT `id`, `rating` FROM `$config[1]` WHERE `id` = $lose LIMIT 1") or die(mysql_error());
  $result = mysql_fetch_assoc($query);
  $Rb     = $result['rating'];

  // Probabilities of winning
  $Ea = 1 / (1 + pow(10, (($Rb - $Ra) / 400)));
  $Eb = 1 / (1 + pow(10, (($Ra - $Rb) / 400)));

  // Print values (for testing)
  // print 'Ra: ' . round($Ra, 2) . '<br />';
  // print 'Rb: ' . round($Rb, 2) . '<br />';
  // print 'Ea: ' . round($Ea, 2) . '<br />';
  // print 'Eb: ' . round($Eb, 2) . '<br />';

  // Calculate new ratings
  $Ra = $Ra + $K * ($Wa - $Ea);
  $Rb = $Rb + $K * ($Wb - $Eb);

  // Save new ratings in database
  mysql_query("UPDATE `$config[1]` SET `rating` = $Ra WHERE `id` = $win LIMIT 1") or die(mysql_error());
  mysql_query("UPDATE `$config[1]` SET `rating` = $Rb WHERE `id` = $lose LIMIT 1") or die(mysql_error());
}


function getValue($field) {
  return isset($_GET[$field]) ? $_GET[$field] : FALSE;
}

// Shows current statistics
function showStats() {
  global $config;
  $imgs = dbGetImages("SELECT `id` FROM `$config[1]` ORDER BY `rating` DESC");
  $count = count($imgs);

  $i = 1;
  print '<ul class="thumbnails center-list">';
  foreach ($imgs as $img) {
    if ($i == 4) {
      print '</ul><ul class="thumbnails center-list">';
    }
    ?>
      <li class="score score-<?php print $i; ?>">
        <div class="thumbnail">
          <img src="images/<?php print $img->src; ?>" alt="">
          <?php if (checkLogin()): ?>
            <p class="delete"><a href="?do=confirmDelete&id=<?php print $img->id; ?>">Delete</a></p>
          <?php endif; ?>
          <p class="rating"><strong>Rating: <?php print $img->rating; ?></strong><br><?php print $img->wins + $img->losses; ?> appearances</p>
        </div>
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

function currentPage($page) {
  $curPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

  return ($page == $curPage) ? 'class="active"' : '';
}

?>