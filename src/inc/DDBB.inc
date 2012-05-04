<?php

class DDBB {
  protected $dbh;

  function __construct() {
    $this->dbh = new PDO('mysql:host=localhost;dbname=porra2012', 'porra2012', 'porra2012', array(
                     PDO::ATTR_PERSISTENT => true
                ));
  }
}

?>
