<?php
// FUNCTIONS

// Connect to database
function dbConnect($database) {
  $link = mysql_connect("localhost", "root", "root") or die(mysql_error());
  mysql_select_db($database) or die(mysql_error());
}

function checkLogin() {
  if ($_COOKIE['imgrate_auth'] == 'success') {
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

?>