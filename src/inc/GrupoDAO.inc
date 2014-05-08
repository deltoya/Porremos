<?php
require_once("DDBB.inc");
require_once("Usuario.inc");
require_once("Grupo.inc");
require_once("Apuesta.inc");
require_once("JornadaDAO.inc");

class GrupoDAO extends DDBB {

  public function save(Grupo $grupo) {
    $sql = $this->dbh->prepare("UPDATE grupos SET " .
                               "nombre = :nombre, descripcion = :descripcion " .
                               "WHERE id = :id");

    $sql->execute(array(
         'id' => $grupo->getId(),
         'descripcion' => $grupo->getDescripcion(),
         'nombre' => $grupo->getNombre()
       ));
  }

  public function create(Grupo $grupo, $id) {
    $sql = $this->dbh->prepare("INSERT INTO grupos (nombre, descripcion) " .
                               "VALUES ( :nombre, :descripcion )");

    $sql->execute(array(
         'nombre' => $grupo->getNombre(),
         'descripcion' => $grupo->getDescripcion(),
       ));

     $sql = $this->dbh->prepare("INSERT INTO grupo_jugador " .
                               "VALUES ( LAST_INSERT_ID(), ? )");
     $sql->execute(array($id));
  }

  public function getById($id) {
    $retorno = new Grupo();
    $sql = $this->dbh->prepare("SELECT * FROM grupos where id = ?");
    $sql->execute(array($id));
    $row = $sql->fetch();
    $retorno->setNombre($row['nombre']);
    $retorno->setDescripcion($row['descripcion']);
    $retorno->setId($row['id']);

    return $retorno;
  }

  public function getByUserId($userId) {
    $retorno = array();

    $sql = $this->dbh->prepare("SELECT g.* FROM grupos g, grupo_jugador j WHERE g.id=j.id_grupo AND j.id_jugador=?");
    $sql->execute(array($userId));
    foreach ($sql->fetchAll() as $row) {
      $aux = new Grupo();

      $aux->setNombre($row['nombre']);
      $aux->setDescripcion($row['descripcion']);
      $aux->setId($row['id']);

      $retorno[] = $aux;
    }

    return $retorno;
    
  }

  public function removeUser($groupId, $userId) {
    $sql = $this->dbh->prepare("delete from grupo_jugador where id_grupo = ? and id_jugador = ? ");
    $sql->execute(array($groupId, $userId));
  }

  public function addUser($groupId, $email) {
    $sql = $this->dbh->prepare("SELECT id from jugadores where email=?");
    $sql->execute(array($email));

    if ($row = $sql->fetch())
    {
      $sql = $this->dbh->prepare("INSERT INTO grupo_jugador values ( ?,?)");
      $sql->execute(array($groupId, $row['id']));

      return true;
    }

    return false;
  }

  public function getClasificacion($groupId) {
    $retorno = array();
    $base=0;

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
) as datos, grupo_jugador
 where grupo_jugador.id_grupo=? and grupo_jugador.id_jugador=datos.id
 group by id order by puntos desc, id asc");

    $sql->execute(array($groupId));
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
}
?>
