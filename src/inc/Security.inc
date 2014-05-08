<?php

session_start();

require_once 'Log.php';

$conf = array('mode' => 0600, 'timeFormat' => '%x %X');
$logger = Log::singleton('file', '/var/log/porra2012/funcional.log', 'url', $conf);
$logger->log('('.$_SESSION['user_id'] .') ' . $_SERVER["REQUEST_URI"], PEAR_LOG_INFO);

if (!isset($_SESSION['user_id'])) {
  header('Location:/login.php');
  exit;
}

?>
