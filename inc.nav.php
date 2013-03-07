<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <nav>     
        <ul class="nav center-list">
          <li <?php print currentPage('index'); ?>><a href="index.php">Rate</a></li>
          <?php if (checkLogin()): ?>
          <li <?php print currentPage('upload'); ?>><a href="upload.php">Upload</a></li>
          <?php endif; ?>
          <li <?php print currentPage('stats'); ?>><a href="stats.php">Stats</a></li>
          <?php if (!checkLogin()): ?>
          <li <?php print currentPage('login'); ?>><a href="login.php">Log in</a></li>
          <?php endif; ?>
          <?php if (checkLogin()): ?>
          <li><a href="index.php?do=logout">Log out</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>

