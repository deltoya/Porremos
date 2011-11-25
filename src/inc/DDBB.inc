<?php

class DDBB {
  protected $dbh;

  function __construct() {
    $this->dbh = new PDO('mysql:host=localhost;dbname=porra2010', 'porra2010', 'porra2010', array(
                     PDO::ATTR_PERSISTENT => true
                ));
  }
}

?>
