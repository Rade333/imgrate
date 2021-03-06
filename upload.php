<?php
// INCLUDES
include 'inc.php'; 
if (isset($_POST['submit'])) {
  if (checkLogin()) {
    if ($_FILES['file']['name'] == '') {
      $message = new Message('Error: No file selected.', 1);
      $error = TRUE;
    }

    if (!$error) {
      // Connect to database
      dbConnect("radef894_imgrate");

      // Get names
      $file_name = $_FILES['file']['name'];
      $file_tmp_name = $_FILES['file']['tmp_name'];

      // Check extension
      $extension = str_replace('.', '', substr($file_name, -4));

      if ($extension == 'jpg' || $extension == 'jpeg') {
        $newImage = imagecreatefromjpeg($file_tmp_name);
      }
      elseif ($extension == 'png') {
        $newImage = imagecreatefrompng($file_tmp_name);
      }
      elseif ($extension == 'gif') {
        $newImage = imagecreatefromgif($file_tmp_name);
      }
      else {
        $message = new Message('Error: File type not supported. Upload process terminated.', 1);
        $error = TRUE;
      }

      if (!$error) {
        // Resize
        list($width, $height) = getimagesize($file_tmp_name);
        $newHeight = 150;
        $newWidth  = $width * ($newHeight / $height);
        $tmpImage  = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmpImage, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Give file new name
        $now = time();
        $suffix = '-imagerate.' . $extension;
        while (file_exists('images/' . $now . $suffix)) {
          $now++;
        }
        $newFilename = $now . $suffix;
        $newFilePath = 'images/' . $newFilename;

        imagejpeg($tmpImage, $newFilePath, 80);
        imagedestroy($newImage);
        imagedestroy($tmpImage);

        mysql_query("INSERT INTO `tbl_images` (`img`) VALUES ('$newFilename')") or die(mysql_error());

        // Set message
        $message = new Message('Image uploaded successfully!', 0);
      }
    }
  }
  else {
    $message = msgNotAuthorized();
  }
}

?>


<?php include 'inc.head.php'; ?>
    <title>Upload image | ImgRate</title>
  </head>
  <body>
    <div class="container-fluid upload text-center">
      <div class="row-fluid">
        <div class="span12">
          <header>    
            <?php include 'inc.nav.php'; ?>
          </header>
          
          <?php include 'inc.message.php'; ?>
          
          <article>
            <h1 class="title">Upload</h1>
            <p>Upload an image to be rated. Supported file formats are jpg, jpeg, png and gif.</p>
            <form action="upload.php" method="post" enctype="multipart/form-data">
              <input type="file" name="file" id="file" style="display:none">
              <div class="input-append">
                <input type="text" id="fileText">
                <a class="btn" onclick="$('input#file').click();">Browse</a>
              </div>
              <div class="control-group">
                <div class="controls">
                  <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                </div>
              </div>
            </form>
          </article>
        </div>
      </div>
      
    <?php include 'inc.footer.php'; ?>

  </body>
</html>

