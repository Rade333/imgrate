<?php
// CONFIG

// Database
// Database
$config[0] = "radefi_imgrate";
// Image table
$config[1] = "tbl_images";
// Votes table
$config[2] = "tbl_votes";

// Variables
$php_self = $_SERVER['PHP_SELF'];
$request_uri = $_SERVER['REQUEST_URI'];

// Log in
$cookie_name         = 'imgrate_auth';
$cookie_value_login  = 'success';
$cookie_value_logout = '';
$cookie_expire       = '0';
$cookie_path         = dirname($_SERVER['PHP_SELF']);
$cookie_domain       = 'rade.fi';

?>