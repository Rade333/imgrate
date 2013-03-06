<div class="nav">
  <a href="index.php" <?php print currentPage('index'); ?>>Rate</a> | 
  <?php if (checkLogin()): ?>
  <a href="upload.php" <?php print currentPage('upload'); ?>>Upload</a> |
  <?php endif; ?>
  <a href="stats.php" <?php print currentPage('stats'); ?>>Stats</a> |
  <?php if (!checkLogin()): ?>
  <a href="login.php" <?php print currentPage('login'); ?>>Log in</a>
  <?php endif; ?>
  <?php if (checkLogin()): ?>
  <a href="index.php?do=logout">Log out</a>
  <?php endif; ?>
</div>

