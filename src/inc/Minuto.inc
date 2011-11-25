<?php
class Minuto {
  private $id;
  private $id_jugador;
  private $id_partido;
  private $local;
  private $visitante;
  private $goles_local;
  private $goles_visitante;
  private $fecha;
  private $minutos;

  private $mdao;

  public function __construct(MinutoDAO $mdao = NULL) {
    if($mdao)
      $this->mdao = $mdao;
    else
      $this->mdao = new MinutoDAO();
  }

  public function getId() {
    return $this->id;
  }

  public function getIdJugador() {
    return $this->id_jugador;
  }

  public function getIdPartido() {
    return $this->id_partido;
  }

  public function getLocal() {
    return $this->local;
  }

  public function getVisitante() {
    return $this->visitante;
  }

  public function getGolesLocal() {
    return $this->goles_local;
  }

  public function getGolesVisitante() {
    return $this->goles_visitante;
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

  public function setIdJugador($id) {
    $this->id_jugador = $id;
  }

  public function setIdPartido($id) {
    $this->id_partido = $id;
  }

  public function setLocal($local) {
    $this->local = $local;
  }

  public function setVisitante($local) {
    $this->visitante = $local;
  }

  public function setGolesLocal($goles) {
    $this->goles_local = $goles;
  }

  public function setGolesVisitante($goles) {
    $this->goles_visitante = $goles;
  }

  public function setFecha($fecha) {
    $this->fecha = new DateTime($fecha);
  }

  public function setMinutos($minutos) {
    $this->minutos = $minutos;
  }

  public function save() {
     $this->mdao->save($this);
  }
}

?>
