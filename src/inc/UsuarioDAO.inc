<?php
require_once("DDBB.inc");
require_once("Usuario.inc");
require_once("Apuesta.inc");
require_once("JornadaDAO.inc");

class UsuarioDAO extends DDBB {

  public static $UsuariosPorPagina = 25;

  public function getCurrent() {
    if (isset($_SESSION['user_id'])) 
        return $this->getById($_SESSION['user_id']);
    return null;
  }

  public function save(Usuario $usuario) {
    $sql = $this->dbh->prepare("UPDATE jugadores SET " .
                               "password = :password, nick = :nick " .
                               "WHERE id = :id");

    $sql->execute(array(
         'id' => $usuario->getId(),
         'password' => $usuario->getPassword(),
         'nick' => $usuario->getNick()
       ));
  }

  public function create(Usuario $usuario) {
    $sql = $this->dbh->prepare("INSERT INTO jugadores (nombre, password, nick, referrer, email, minutos) " .
                               "VALUES ( :nombre, :password, :nick, :referrer, :email, :minutos )");

    $sql->execute(array(
         'nombre' => $usuario->getNombre(),
         'password' => $usuario->getPassword(),
         'nick' => $usuario->getNick(),
         'referrer' => $_SESSION['user_id'],
         'email' => $usuario->getEmail(),
         'minutos' => 10,
       ));
    //mail( "fvelasco@matchmind.es", "Email Subject", "Vamos que nos vamos", "From: fvelasco@matchmind.es" );
  }

  public function getApuesta($id, $partidoid) {
    $retorno = new Apuesta();
    $sql = $this->dbh->prepare("SELECT goleslocal,golesvisitante,id_jugador, id_partido FROM apuestas where id_jugador = :idjugador and id_partido = :idpartido");
    $sql->execute(array(
         'idjugador' => $id,
         'idpartido' => $partidoid
       ));
    $row = $sql->fetch();
    $retorno->setLocal($row['goleslocal']);
    $retorno->setVisitante($row['golesvisitante']);
    $retorno->setIdJugador($row['id_jugador']);
    $retorno->setIdPartido($row['id_partido']);

    return $retorno;
  }

  public function setApuesta($id, $idpartido, $local, $visitante) {
    $partidos = new PartidoDAO();
    $partido = $partidos->getById($idpartido);
    if (!$partido || $partido->esCerrado()) {
      return false;
    }

    $sql = $this->dbh->prepare("INSERT INTO apuestas (id_jugador, id_partido, goleslocal, golesvisitante) " .
                               "VALUES (:idjugador, :idpartido, :local, :visitante) " .
                               "ON DUPLICATE KEY UPDATE goleslocal = VALUES(goleslocal), golesvisitante = VALUES(golesvisitante)");

    $sql->execute(array(
         'idjugador' => $id,
         'idpartido' => $idpartido,
         'local' => $local,
         'visitante' => $visitante
       ));
  }

  public function setApuestaGolesJornada($id, $idjornada, $goles) {
    $jornadas = new JornadaDAO();
    $jornada = $jornadas->getById($idjornada);
    if (!$jornada || $jornada->esCerrado()) {
      return array ('ret'=>false, 'data'=>'La jornada no esta disponible para apostar');
    }

    $sql = $this->dbh->prepare("INSERT INTO apuestasjornadas (id_jugador, id_jornada, goles) " .
                               "VALUES (:idjugador, :idjornada, :goles) " .
                               "ON DUPLICATE KEY UPDATE goles = VALUES(goles)");

    $sql->execute(array(
         'idjugador' => $id,
         'idjornada' => $idjornada,
         'goles' => $goles,
       ));

    return array ('ret'=>true, 'data'=>'Goles actualizados');
  }

  public function getApuestaGolesJornada($id, $idjornada) {
    $jornadas = new JornadaDAO();
    $jornada = $jornadas->getById($idjornada);
    if (!$jornada) {
      return 0;
    }

    $sql = $this->dbh->prepare("SELECT goles FROM apuestasjornadas " .
                               "WHERE id_jugador = :idjugador AND id_jornada = :idjornada");

    $sql->execute(array(
         'idjugador' => $id,
         'idjornada' => $idjornada,
       ));

    $row = $sql->fetch();

    return $row['goles'];
  }

  public function getMinutosEnJornada($id, $idjornada) {
    $jornadas = new JornadaDAO();
    $jornada =  $jornadas->getById($idjornada);
    if (!$jornada) {
      return false;
    }

    $sql = $this->dbh->prepare("SELECT A.goles as apuesta, B.goles as reales, B.minutos as minutos ".
                               "FROM apuestasjornadas A, golesjornadas B " .
                               "WHERE A.id_jugador = :idjugador AND A.id_jornada = :idjornada AND A.id_jornada = B.id");

    $sql->execute(array(
         'idjugador' => $id,
         'idjornada' => $idjornada,
       ));

    $row = $sql->fetch();
 
    if ($row['apuesta'] == $row['reales']) {
      return array('minutos' => $row['minutos'], 'style' => 'color: green;');
    } else if (($row['apuesta'] + 1 == $row['reales']) || ($row['apuesta'] == $row['reales'] + 1)) {
      return array('minutos' => ceil($row['minutos']/2), 'style' => 'color: red;');
    }

    return array("minutos" => 0);

  }

  public function setApuestaLive($id, $idpartido, $local, $visitante) {
    $partidos = new PartidoDAO();
    $partido = $partidos->getById($idpartido);
    if (!$partido ||!$partido->isLive()) {
      return array ('ret'=>false, 'data'=>'El partido no esta disponible para apostar');
    }

    $minutos = $this->getCurrent()->getMinutos();

    $fecha_act = new DateTime();
    $horas_act = $fecha_act->format('G');
    $minutos_act = $fecha_act->format('i');
    $hormin_act = ($horas_act * 60) + $minutos_act + 1;

    $fecha_partido = $partido->getFecha();
    $horas_partido = $fecha_partido->format('G');
    $minutos_partido = $fecha_partido->format('i');
    $hormin_partido = ($horas_partido * 60) + $minutos_partido;

    $act = $hormin_act - $hormin_partido;

    if ($act > $minutos) {
      return array ('ret'=>false, 'data'=>'Solamente tienes '.$minutos.' minutos disponibles, y necesitas '.$act);
    }

    $sql = $this->dbh->prepare("UPDATE jugadores SET minutos = :minutos WHERE id = :idjugador");
    $sql->execute(array(
         'idjugador' => $id,
         'minutos' => $minutos - $act
       ));

    $sql = $this->dbh->prepare("INSERT INTO minutos (id_jugador, id_partido, goleslocal, golesvisitante, fecha, minutos) " .
                               "VALUES (:idjugador, :idpartido, :local, :visitante, :fecha, :minutos)");
    $sql->execute(array(
         'idjugador' => $id,
         'idpartido' => $idpartido,
         'local' => $local,
         'visitante' => $visitante,
         'fecha' => date_format(new DateTime(), "Y-m-d H:i:s"),
         'minutos' => $act,
       ));



    $sql = $this->dbh->prepare("INSERT INTO apuestas (id_jugador, id_partido, goleslocal, golesvisitante) " .
                               "VALUES (:idjugador, :idpartido, :local, :visitante) " .
                               "ON DUPLICATE KEY UPDATE goleslocal = VALUES(goleslocal), golesvisitante = VALUES(golesvisitante)");

    $sql->execute(array(
         'idjugador' => $id,
         'idpartido' => $idpartido,
         'local' => $local,
         'visitante' => $visitante
       ));

    return array ('ret'=>true, 'data'=>'Resultado cambiado, se te restan '.$act.' minutos, te quedan '.($minutos - $act), 'minutos' => ($minutos - $act));
  }

  public function getTotal() {
    $sql = $this->dbh->query("select count(*) as total from jugadores");
    $row = $sql->fetch();

    return $row['total'];
  }

  public function getRespuesta($id, $idpregunta) {
    $sql = $this->dbh->prepare("SELECT respuesta FROM soluciones where id_jugador = :idjugador and id_pregunta = :idpregunta");
    $sql->execute(array(
         'idjugador' => $id,
         'idpregunta' => $idpregunta
       ));
    $row = $sql->fetch();

    return htmlspecialchars($row['respuesta']);
  }

  public function setRespuesta($id, $idpregunta, $valor) {
    $preguntas = new PreguntaDAO();
    $pregunta = $preguntas->getById($idpregunta);
    if (!$pregunta) {
      return false;
    }

    $sql = $this->dbh->prepare("INSERT INTO soluciones (id_jugador, id_pregunta, respuesta) " .
                               "VALUES (:idjugador, :idpregunta, :respuesta) " .
                               "ON DUPLICATE KEY UPDATE respuesta = VALUES(respuesta)");

    $sql->execute(array(
         'idjugador' => $id,
         'idpregunta' => $idpregunta,
         'respuesta' => $valor
       ));
  }

  public function checkUser($username, $password) {
    $usuario = $this->getByName($username);
    if ($usuario && crypt($password, $usuario->getPassword()) == $usuario->getPassword()) {
        return $usuario->getId();
    }
    return false;
  }

  public function getByName($username) {
    $retorno = new Usuario();
    $sql = $this->dbh->prepare("SELECT * FROM jugadores WHERE nombre = ?");
    $sql->execute(array($username));
    $row = $sql->fetch();
    $retorno->setNombre($row['nombre']);
    $retorno->setPassword($row['password']);
    $retorno->setPuntos($row['puntos']);
    $retorno->setNick($row['nick']);
    $retorno->setAdmin($row['admin']);
    $retorno->setId($row['id']);
    $retorno->setMinutos($row['minutos']);
    $retorno->setEmail($row['email']);

    return $retorno;
  }

  public function getById($id) {
    $retorno = new Usuario();
    $sql = $this->dbh->prepare("SELECT * FROM jugadores where id = ?");
    $sql->execute(array($id));
    $row = $sql->fetch();
    $retorno->setNombre($row['nombre']);
    $retorno->setPassword($row['password']);
    $retorno->setPuntos($row['puntos']);
    $retorno->setNick($row['nick']);
    $retorno->setAdmin($row['admin']);
    $retorno->setId($row['id']);
    $retorno->setMinutos($row['minutos']);
    $retorno->setEmail($row['email']);

    return $retorno;
  }

  public function getAll() {
    $retorno = array();

    $sql = $this->dbh->prepare("SELECT * FROM jugadores");
    $sql->execute();
    foreach ($sql->fetchAll() as $row) {
      $aux = new Usuario();

      $aux->setNombre($row['nombre']);
      $aux->setPassword($row['password']);
      $aux->setPuntos($row['puntos']);
      $aux->setNick($row['nick']);
      $aux->setAdmin($row['admin']);
      $aux->setId($row['id']);
      $aux->setMinutos($row['minutos']);

      $retorno[] = $aux;
    }

    return $retorno;
    
  }

  public function getPosicion($jugador_id) {

    $sql = $this->dbh->query("select sum(puntos) puntos, nombre, nick, password, admin, id, minutos from
(select sum(p.puntos) as puntos, j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 1
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ((ap.goleslocal > ap.golesvisitante and p.goleslocal > p.golesvisitante) or
   (ap.goleslocal = ap.golesvisitante and p.goleslocal = p.golesvisitante) or
   (ap.goleslocal < ap.golesvisitante and p.goleslocal < p.golesvisitante))
 group by j.nombre

union

 select sum(15) as puntos , j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 2
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ap.goleslocal = p.goleslocal and p.golesvisitante = ap.golesvisitante
 group by j.nombre

union

 select sum(5) as puntos, j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 3
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ((ap.goleslocal > ap.golesvisitante and p.goleslocal > p.golesvisitante) or
   (ap.goleslocal = ap.golesvisitante and p.goleslocal = p.golesvisitante) or
   (ap.goleslocal < ap.golesvisitante and p.goleslocal < p.golesvisitante)) 
     and 
   ((ap.goleslocal = p.goleslocal and p.golesvisitante != ap.golesvisitante) or
   (ap.goleslocal != p.goleslocal and p.golesvisitante = ap.golesvisitante))
 group by j.nombre

union

 select sum(p.puntos) as puntos, j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 4
 from preguntas p, jugadores j, soluciones s, preguntas_solucionadas ps
 where s.id_jugador = j.id and s.id_pregunta = p.id and 
   s.respuesta = ps.respuesta and p.tipo=1 and p.id = ps.id_pregunta 
 group by j.nombre

union

 select 0 as puntos, j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 5
 from jugadores j
) as datos group by id order by puntos desc
  ");
    $ret = 1;
    foreach ($sql as $row) {
      if ($row['id'] == $jugador_id) {
         return $ret;
      }
      $ret++;
    }

    return -1;
  }

  public function getClasificacion($pagina=0) {
    $retorno = array();
    $base=0;
    if ($pagina && is_numeric($pagina))
      $base = $pagina * self::$UsuariosPorPagina;

    $sql = $this->dbh->prepare("select sum(puntos) puntos, nombre, nick, password, admin, id, minutos from
(select sum(p.puntos) as puntos, j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 1
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ((ap.goleslocal > ap.golesvisitante and p.goleslocal > p.golesvisitante) or
   (ap.goleslocal = ap.golesvisitante and p.goleslocal = p.golesvisitante) or
   (ap.goleslocal < ap.golesvisitante and p.goleslocal < p.golesvisitante))
 group by j.nombre

union

 select sum(15) as puntos , j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 2
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ap.goleslocal = p.goleslocal and p.golesvisitante = ap.golesvisitante
 group by j.nombre

union

 select sum(5) as puntos, j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 3
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ((ap.goleslocal > ap.golesvisitante and p.goleslocal > p.golesvisitante) or
   (ap.goleslocal = ap.golesvisitante and p.goleslocal = p.golesvisitante) or
   (ap.goleslocal < ap.golesvisitante and p.goleslocal < p.golesvisitante)) 
     and 
   ((ap.goleslocal = p.goleslocal and p.golesvisitante != ap.golesvisitante) or
   (ap.goleslocal != p.goleslocal and p.golesvisitante = ap.golesvisitante))
 group by j.nombre

union

 select sum(p.puntos) as puntos, j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 4
 from preguntas p, jugadores j, soluciones s, preguntas_solucionadas ps
 where s.id_jugador = j.id and s.id_pregunta = p.id and 
   s.respuesta = ps.respuesta and p.tipo=1 and p.id = ps.id_pregunta 
 group by j.nombre

union

 select 0 as puntos, j.nombre, j.nick, j.password, j.admin, j.id, j.minutos, 5
 from jugadores j
) as datos group by id order by puntos desc, id limit ".$base."," . self::$UsuariosPorPagina. "
  ");
    $sql->execute();
    foreach ($sql->fetchAll() as $row) {
      $aux = new Usuario();

      $aux->setNombre($row['nombre']);
      $aux->setPassword($row['password']);
      $aux->setPuntos($row['puntos']);
      $aux->setNick($row['nick']);
      $aux->setAdmin($row['admin']);
      $aux->setId($row['id']);
      $aux->setMinutos($row['minutos']);

      $retorno[] = $aux;
    }

    return $retorno;
  }

  public function getPuntosenFecha($id, DateTime $fecha) {
    $ffecha = $fecha->format("Y-m-d") . " 23:59:59";
    $sql = $this->dbh->prepare("select sum(puntos) puntos from
(select sum(p.puntos) as puntos, 1
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   p.fecha <= :fecha1 and j.id = :id1 and
   ((ap.goleslocal > ap.golesvisitante and p.goleslocal > p.golesvisitante) or
   (ap.goleslocal = ap.golesvisitante and p.goleslocal = p.golesvisitante) or
   (ap.goleslocal < ap.golesvisitante and p.goleslocal < p.golesvisitante))
 group by j.nombre

union

 select sum(15) as puntos, 2
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   p.fecha <= :fecha2 and j.id = :id2 and
   ap.goleslocal = p.goleslocal and p.golesvisitante = ap.golesvisitante
 group by j.nombre

union

 select sum(5) as puntos, 3
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   p.fecha <= :fecha3 and j.id = :id3 and
   ((ap.goleslocal > ap.golesvisitante and p.goleslocal > p.golesvisitante) or
   (ap.goleslocal = ap.golesvisitante and p.goleslocal = p.golesvisitante) or
   (ap.goleslocal < ap.golesvisitante and p.goleslocal < p.golesvisitante)) 
     and 
   ((ap.goleslocal = p.goleslocal and p.golesvisitante != ap.golesvisitante) or
   (ap.goleslocal != p.goleslocal and p.golesvisitante = ap.golesvisitante))
 group by j.nombre

union

 select sum(p.puntos) as puntos, 4
 from preguntas p, jugadores j, soluciones s, preguntas_solucionadas ps
 where s.id_jugador = j.id and s.id_pregunta = p.id and p.id = ps.id_pregunta and
   ps.fecha <= :fecha4 and j.id = :id4 and
   s.respuesta = ps.respuesta and p.tipo=1

union

 select 0 as puntos, 5
 from jugadores j
) as datos
  ");

    $sql->execute(array(
      'id1' => $id,
      'id2' => $id,
      'id3' => $id,
      'id4' => $id,
      'fecha1' => $ffecha,
      'fecha2' => $ffecha,
      'fecha3' => $ffecha,
      'fecha4' => $ffecha,
    ));

    $row = $sql->fetch();
    $retorno = $row['puntos'];

    return $retorno;
  }

  public function getCaliente() {
    $retorno = array();
      
    $sql = $this->dbh->prepare("select count(*) as puntos, j.nombre, j.nick, j.password, j.admin, j.id
       from preguntas p, jugadores j, soluciones s
       where s.id_jugador = j.id and s.id_pregunta = p.id and p.respuesta is not NULL and
       s.respuesta = p.respuesta and p.tipo=2
       group by j.nombre order by puntos desc limit 0,13");

    $sql->execute();
    foreach ($sql->fetchAll() as $row) {
      $aux = new Usuario();

      $aux->setNombre($row['nombre']);
      $aux->setPassword($row['password']);
      $aux->setPuntos($row['puntos']);
      $aux->setNick($row['nick']);
      $aux->setAdmin($row['admin']);
      $aux->setId($row['id']);

      $retorno[] = $aux;
    }

    usort($retorno, "ordenaaux");
    return $retorno;
  }

  public function getMoral() {
    $retorno = array();

    $sql = $this->dbh->prepare("select sum(puntos) puntos, nombre, nick, password, admin, id from
(select sum(p.puntos) as puntos, j.nombre, j.nick, j.password, j.admin, j.id, 1
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ((ap.goleslocal > ap.golesvisitante and p.goleslocal > p.golesvisitante) or
   (ap.goleslocal = ap.golesvisitante and p.goleslocal = p.golesvisitante) or
   (ap.goleslocal < ap.golesvisitante and p.goleslocal < p.golesvisitante))
 group by j.nombre

union

 select sum(15) as puntos , j.nombre, j.nick, j.password, j.admin, j.id, 2
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ap.goleslocal = p.goleslocal and p.golesvisitante = ap.golesvisitante
 group by j.nombre

union

 select sum(5) as puntos, j.nombre, j.nick, j.password, j.admin, j.id, 3
 from apuestas ap, jugadores j, partidos p
 where ap.id_jugador = j.id and p.id = ap.id_partido and p.jugado = '1' and
   ((ap.goleslocal > ap.golesvisitante and p.goleslocal > p.golesvisitante) or
   (ap.goleslocal = ap.golesvisitante and p.goleslocal = p.golesvisitante) or
   (ap.goleslocal < ap.golesvisitante and p.goleslocal < p.golesvisitante)) 
     and 
   ((ap.goleslocal = p.goleslocal and p.golesvisitante != ap.golesvisitante) or
   (ap.goleslocal != p.goleslocal and p.golesvisitante = ap.golesvisitante))
 group by j.nombre

union


 select 0 as puntos, j.nombre, j.nick, j.password, j.admin, j.id, 5
 from jugadores j
) as datos group by id order by puntos desc
  ");
    $sql->execute();
    foreach ($sql->fetchAll() as $row) {
      $aux = new Usuario();

      $aux->setNombre($row['nombre']);
      $aux->setPassword($row['password']);
      $aux->setPuntos($row['puntos']);
      $aux->setNick($row['nick']);
      $aux->setAdmin($row['admin']);
      $aux->setId($row['id']);

      $retorno[] = $aux;
    }

    return $retorno;
  }
}

function ordenaaux($jugadora, $jugadorb) {
  $puntosa = $jugadora->getPuntos();
  $puntosb = $jugadorb->getPuntos();

  if ($puntosa != $puntosb)
    return $puntosb - $puntosa;

  return $jugadorb->getPuntosEnFecha(new DateTime()) - $jugadora->getPuntosEnFecha(new DateTime());
}

?>
