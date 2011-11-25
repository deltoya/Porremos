<?php
class Comentario {
  private $id;
  private $idjugador;
  private $fecha;
  private $texto;

  public function getId() {
    return $this->id;
  }

  public function getIdUsuario() {
    return $this->idjugador;
  }

  public function getTexto() {
    return $this->limpia($this->texto);
  }

  public function getFecha() {
    return $this->fecha;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setIdUsuario($id) {
    $this->idjugador = $id;
  }

  public function setTexto($texto) {
    $this->texto = $texto;
  }

  public function setFecha($fecha) {
    $this->fecha = new DateTime($fecha);
  }

  private function limpia($cad) {
    return htmlspecialchars($cad);
  }
}

?>
