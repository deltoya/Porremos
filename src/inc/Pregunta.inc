<?php
class Pregunta {
  private $id;
  private $texto;
  private $respuestas;
  private $valor;
  private $fecha;
  private $posibles;

  public function __construct() {
    $this->posibles=array();
    $this->respuestas=array();
  }

  public function getId() {
    return $this->id;
  }

  public function getTexto() {
    return htmlspecialchars($this->texto);
  }

  public function getRespuesta() {
    return $this->respuestas;
  }

  public function getValor() {
    return htmlspecialchars($this->valor);
  }

  public function getFecha() {
    return $this->fecha;
  }

  public function getPosibles() {
    return $this->posibles;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setTexto($texto) {
    $this->texto = $texto;
  }

  public function addRespuesta($respuesta) {
    $this->respuestas[] = $respuesta;
  }

  public function setValor($valor) {
    $this->valor = $valor;
  }

  public function setFecha($fecha) {
    $this->fecha = new DateTime($fecha);
  }

  public function addPosible($texto) {
    $this->posibles[] = $texto;
  }
}

?>
