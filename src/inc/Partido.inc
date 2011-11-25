<?php
class Partido {
  private $id;
  private $local;
  private $visitante;
  private $jugado;
  private $goleslocal;
  private $golesvisitante;
  private $fecha;
  private $puntos;

  private $pdao;

  public function __construct(PartidoDAO $pdao = NULL) {
    if($pdao)
      $this->pdao = $pdao;
    else
      $this->pdao = new PartidoDAO();
  }

  public function esCerrado() {
    $horas = $this->fecha->format('G');
    $minutos = $this->fecha->format('i');
    return $this->fecha->format('U') - ( $horas * 3600 ) - ( $minutos * 60 ) < time();
  }

  public function isLive() {
    return (time() > $this->fecha->format('U')) && ( $this->fecha->format('U') + ( 45 * 60) > time());
  }

  public function getId() {
    return $this->id;
  }

  public function getLocal() {
    return $this->local;
  }

  public function getVisitante() {
    return $this->visitante;
  }

  public function getJugado() {
    return $this->jugado;
  }

  public function getGolesLocal() {
    return $this->goleslocal;
  }

  public function getGolesVisitante() {
    return $this->golesvisitante;
  }

  public function getFecha() {
    return $this->fecha;
  }

  public function getPuntos() {
    return $this->puntos;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setLocal($local) {
    $this->local = $local;
  }

  public function setVisitante($visitante) {
    $this->visitante = $visitante;
  }

  public function setJugado($jugado) {
    $this->jugado = $jugado;
  }

  public function setGolesLocal($golesLocal) {
    $this->goleslocal = $golesLocal;
  }

  public function setGolesVisitante($golesVisitante) {
    $this->golesvisitante = $golesVisitante;
  }

  public function setFecha($fecha) {
    $this->fecha = new DateTime($fecha);
  }

  public function setPuntos($puntos) {
    $this->puntos = $puntos;
  }

  public function save() {
     $this->pdao->save($this);
  }
}

?>
