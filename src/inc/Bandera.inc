<?php
class Bandera {
  private $pais;
  private $flag;

  public function __construct() {
  }

  public function getPais() {
    return $this->pais;
  }

  public function getFlag() {
    return $this->flag;
  }

  public function setPais($pais) {
    $this->pais = $pais;
  }

  public function setFlag($flag) {
    $this->flag = $flag;
  }
}

?>
