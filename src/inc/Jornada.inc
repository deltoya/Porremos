<?php
class Jornada {
  private $id;
  private $goles;
  private $fecha;
  private $minutos;

  private $jdao;

  public function __construct(JornadaDAO $jdao = NULL) {
    if($jdao)
      $this->jdao = $jdao;
    else
      $this->jdao = new JornadaDAO();
  }

  public function getId() {
    return $this->id;
  }

  public function getGoles() {
    return $this->goles;
  }

  public function getFecha() {
    return $this->fecha;
  }

  public function getMinutos() {
    return $this->minutos;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setGoles($goles) {
    $this->goles = $goles;
  }

  public function setFecha($fecha) {
    $this->fecha = new DateTime($fecha);
  }

  public function setMinutos($minutos) {
    $this->minutos = $minutos;
  }

  public function save() {
     $this->jdao->save($this);
  }

  public function esCerrado() {
     return $this->fecha < new DateTime();
  }
}

?>
