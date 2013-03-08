<?php
class Image {
  
  public $id, $src, $wins, $losses, $appearances, $rating;
  private $exclude;
  
  public function Image($id, $exclude = FALSE) {
    if ($id == 'random') {
      $exclude  = is_numeric($exclude) ? "WHERE `id` <> $exclude" : '';
      $sql = "SELECT * FROM `tbl_images` $exclude ORDER BY RAND() LIMIT 1";
      $query    = mysql_query($sql) or die(mysql_error());
      $object   = mysql_fetch_object($query);
    }
    else {
      // @todo Hardcoded table name
      $query  = mysql_query("SELECT * FROM `tbl_images` WHERE `id` = $id LIMIT 1") or die(mysql_error());
      $object = mysql_fetch_object($query);
    }

    $this->id           = $object->id;
    $this->src          = $object->img;
    $this->wins         = $object->wins;
    $this->losses       = $object->losses;
    $this->appearances  = $object->wins + $object->losses;
    $this->rating       = $object->rating;
  }
}

class Message {
  public $text, $type;
  
  public function Message($text, $type = 2) {
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