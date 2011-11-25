<?php
require_once("DDBB.inc");
require_once("Bandera.inc");
require_once("Comprobador.inc");

class BanderaDAO extends DDBB {

  public function getFlag($pais) {

    $sql = $this->dbh->prepare("SELECT * from banderas where pais = ?");
    $sql->execute(array($pais));
    $row = $sql->fetch();
    if (!$row)
      return $pais;

    return $row['flag'];
  }

}

?>
