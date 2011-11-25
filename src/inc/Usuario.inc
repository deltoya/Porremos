<?php

require_once('UsuarioDAO.inc');

class Usuario {
  private $id;
  private $nombre;
  private $password;
  private $puntos;
  private $nick;
  private $minutos;
  private $admin;
  private $email;

  private $udao;

  public function __construct(UsuarioDAO $udao = NULL) {
    if($udao)
      $this->udao = $udao;
    else
      $this->udao = new UsuarioDAO();
  }

  public function getId() {
    return $this->id;
  }

  public function getNombre() {
    return $this->nombre;
  }

  public function getPassword() {
    return $this->password;
  }

  public function getPuntos() {
    return $this->puntos;
  }

  public function getNick() {
    return htmlspecialchars($this->nick);
  }

  public function getMinutos() {
    return $this->minutos;
  }

  public function getEmail() {
    return $this->email;
  }

  public function isAdmin() {
    return $this->admin;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setNombre($nombre) {
    $this->nombre = $nombre;
  }

  public function setNick($nick) {
    $this->nick = $nick;
  }

  public function setMinutos($minutos) {
    $this->minutos = $minutos;
  }

  public function setPassword($password) {
    $this->password = $password;
  }

  public function setPuntos($puntos) {
    $this->puntos = $puntos;
  }

  public function setAdmin($admin) {
    $this->admin = $admin;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

///////////////////////////
/// Metodos funcionales ///
///////////////////////////

  public function getApuesta($id_apuesta) {
     return $this->udao->getApuesta($this->id, $id_apuesta);
  }

  public function setApuesta($id_apuesta, $local, $visitante) {
     $this->udao->setApuesta($this->id, $id_apuesta, $local, $visitante);
  }

  public function setApuestaLive($id_apuesta, $local, $visitante) {
     return $this->udao->setApuestaLive($this->id, $id_apuesta, $local, $visitante);
  }

  public function setApuestaGolesJornada($id_jornada, $goles) {
     return $this->udao->setApuestaGolesJornada($this->id, $id_jornada, $goles);
  }

  public function getRespuesta($id_pregunta) {
     return $this->udao->getRespuesta($this->id, $id_pregunta);
  }

  public function setRespuesta($id_pregunta, $respuesta) {
     $this->udao->setRespuesta($this->id, $id_pregunta, $respuesta);
  }

  public function save() {
     $this->udao->save($this);
  }

  public function getApuestaGolesJornada($id_jornada) {
     return $this->udao->getApuestaGolesJornada($this->id, $id_jornada);
  }

  public function getPuntosenFecha(DateTime $fecha) {
     return $this->udao->getPuntosenFecha($this->getId(), $fecha);
  }

  public function getMinutosEnJornada($id_jornada) {
     return $this->udao->getMinutosEnJornada($this->id, $id_jornada);
  }

  public function getPosicion() {
     return $this->udao->getPosicion($this->getId());
  }

}

?>
