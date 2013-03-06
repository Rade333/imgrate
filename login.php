<?php
// INCLUDES
include 'inc.php'; 
if (checkLogin()) {
  $message = new Message('You are now logged in!', 0);
}
else {
  if (isset($_POST['submit'])) {
    $user = strtolower($_POST['user']);
    $pass = $_POST['pass'];

    if ($user == '' || $pass == '') {
      $message = new Message('Error: Missing log in information', 1);
    }
    else {
      if ($user == 'admin' && $pass == 'password') {
        setcookie($cookie_name, $cookie_value_login, $cookie_expire, $cookie_path, $cookie_domain, 0);
        header('Location: ' . $php_self);
        // $message = new Message('Logged in successfully!', 0);
      }
      else {
        $message = new Message('Error: Incorrect log in information', 1);
      }
    }
  }
}

?>


<?php include 'inc.head.php'; ?>
    <title>Log in | ImgRate</title>
  </head>
  <body>
    <div class="container-fluid login text-center">
      <?php include 'inc.nav.php'; ?>
      <?php include 'inc.message.php'; ?>
      <h1 class="title">Log in</h1>
      <?php if (checkLogin()) { ?>
        <div class="description">You are already logged in.</div>
      <?php } else  { ?>
        <div class="description">Enter your username and password in the field below to log in and enable admin features.</div>
        <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post">
          <label for="user">Username:</label> <input type="text" name="user" class="text user" /><br />
          <label for="pass">Password:</label> <input type="password" name="pass" class="text pass" /><br />
          <input type="submit" name="submit" value="Log in" />
        </form>
      <?php } ?>
    </div>
    <?php include 'inc.footer.php'; ?>

  </body>
</html>

