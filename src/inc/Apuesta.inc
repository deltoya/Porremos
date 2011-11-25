<?php
class Apuesta {
  private $id_jugador;
  private $id_partido;
  private $local;
  private $visitante;

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

  public function setIdJugador($id) {
    $this->id_jugador = $id;
  }

  public function setIdPartido($id) {
    $this->id_partido = $id;
  }

  public function setLocal($local) {
    $this->local = $local;
  }

  public function setVisitante($visitante) {
    $this->visitante = $visitante;
  }

}

?>
