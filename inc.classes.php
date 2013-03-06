<?php
class Image {
  var $id, $src, $wins, $losses, $rating;
  function Image($id) {
    $query        = mysql_query("SELECT * FROM `tbl_images` WHERE `id` = $id LIMIT 1") or die(mysql_error());
    $row          = mysql_fetch_assoc($query);
    $this->id     = $row['id'];
    $this->src    = $row['img'];
    $this->wins   = $row['wins'];
    $this->losses = $row['losses'];
    $this->rating = $row['rating'];
  }
}

class Message {
  var $text, $type;
  function Message($text, $type = 2) {
    $this->text = $text;
    if ($type == 0) {
      $this->type = 'alert-success';
    }
    elseif ($type == 1) {
      $this->type = 'alert-error';
    }
    else {
      $this->type = '';
    }
  }
}

?>