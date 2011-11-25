<?php
require_once("Partido.inc");
require_once("Apuesta.inc");

class Comprobador {
  private $esCorrecto;
  private $esLocal;
  private $esVisitante;
  private $puntos;

  public function esAcierto() {
    return $this->esCorrecto;
  }

  public function esAciertoLocal() {
    return $this->esLocal;
  }

  public function esAciertoVisitante() {
    return $this->esVisitante;
  }

  public function getPuntos() {
    return $this->puntos;
  }

  private function __construct() {
    $this->esCorrecto = false;
    $this->esLocal = false;
    $this->esVisitante = false;
    $this->puntos = 0;
  }

  public static function comprueba(Apuesta $apuesta, Partido $partido) {
    $ret = new Comprobador();
    if ($partido->getJugado()) {
      $local = $apuesta->getLocal();
      $visitante = $apuesta->getVisitante();

      if (isset($local) && isset($visitante)) {
        if ($partido->getGolesLocal() > $partido->getGolesVisitante()) {
          $ret->esCorrecto = ($apuesta->getLocal() > $apuesta->getVisitante());
        } else if ($partido->getGolesLocal() < $partido->getGolesVisitante()) {
          $ret->esCorrecto = ($apuesta->getLocal() < $apuesta->getVisitante());
        } else {
          $ret->esCorrecto = ($apuesta->getLocal() == $apuesta->getVisitante());
        }
      }

      if ($ret->esCorrecto) {
        $ret->puntos = $partido->getPuntos();
        $ret->esLocal = ($apuesta->getLocal() == $partido->getGolesLocal());
        $ret->esVisitante = ($apuesta->getVisitante() == $partido->getGolesVisitante());

        if ($ret->esLocal && $ret->esVisitante) {
          $ret->puntos += 15;
        } else if ($ret->esLocal || $ret->esVisitante) {
          $ret->puntos += 5;
        }
      }
    }

    return $ret;
  }

}

?>
