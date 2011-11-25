/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `apuestas`
--

DROP TABLE IF EXISTS `apuestas`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `apuestas` (
  `id_jugador` int(11) NOT NULL,
  `id_partido` int(11) NOT NULL,
  `goleslocal` int(11) default NULL,
  `golesvisitante` int(11) default NULL,
  PRIMARY KEY  (`id_jugador`,`id_partido`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `apuestasjornadas`
--

DROP TABLE IF EXISTS `apuestasjornadas`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `apuestasjornadas` (
  `id_jugador` int(11) NOT NULL,
  `id_jornada` int(11) NOT NULL,
  `goles` int(11) NOT NULL,
  PRIMARY KEY  (`id_jugador`,`id_jornada`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `banderas`
--

DROP TABLE IF EXISTS `banderas`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `banderas` (
  `pais` varchar(25) NOT NULL,
  `flag` varchar(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `comentarios`
--

DROP TABLE IF EXISTS `comentarios`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL auto_increment,
  `idusuario` int(11) NOT NULL,
  `texto` varchar(1000) NOT NULL,
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=512 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `golesjornadas`
--

DROP TABLE IF EXISTS `golesjornadas`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `golesjornadas` (
  `id` int(11) NOT NULL auto_increment,
  `fecha` date NOT NULL,
  `goles` int(11) default NULL,
  `minutos` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `grupo_jugador`
--

DROP TABLE IF EXISTS `grupo_jugador`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grupo_jugador` (
  `id_grupo` int(11) NOT NULL,
  `id_jugador` int(11) NOT NULL,
  PRIMARY KEY  (`id_grupo`,`id_jugador`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grupos` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(20) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `jugadores`
--

DROP TABLE IF EXISTS `jugadores`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `jugadores` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(25) NOT NULL,
  `password` varchar(50) NOT NULL,
  `puntos` int(11) NOT NULL,
  `nick` varchar(25) NOT NULL,
  `admin` tinyint(1) NOT NULL default '0',
  `minutos` int(11) NOT NULL,
  `referrer` int(11) NOT NULL,
  `email` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;
INSERT INTO `test`.`jugadores` VALUES  (1,'admin','$1$rA8E/XOH$8HEWY8HW2nPDxHIcQ.jRW.',0,'admin',1);

--
-- Table structure for table `minutos`
--

DROP TABLE IF EXISTS `minutos`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `minutos` (
  `id` int(11) NOT NULL auto_increment,
  `id_jugador` int(11) NOT NULL,
  `id_partido` int(11) NOT NULL,
  `goleslocal` int(11) NOT NULL,
  `golesvisitante` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `minutos` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=291 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `partidos`
--

DROP TABLE IF EXISTS `partidos`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `partidos` (
  `id` int(11) NOT NULL auto_increment,
  `local` varchar(25) NOT NULL,
  `visitante` varchar(25) NOT NULL,
  `jugado` tinyint(1) NOT NULL default '0',
  `goleslocal` int(11) NOT NULL default '0',
  `golesvisitante` int(11) NOT NULL default '0',
  `fecha` datetime default NULL,
  `puntos` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `preguntas`
--

DROP TABLE IF EXISTS `preguntas`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `texto` varchar(50) NOT NULL,
  `respuesta` varchar(20) default NULL,
  `tipo` int(11) NOT NULL,
  `puntos` int(11) NOT NULL,
  `fecha` date default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `preguntas2`
--

DROP TABLE IF EXISTS `preguntas2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `preguntas2` (
  `id` int(11) NOT NULL,
  `texto` varchar(50) NOT NULL,
  `respuesta` varchar(20) default NULL,
  `tipo` int(11) NOT NULL,
  `puntos` int(11) NOT NULL,
  `fecha` date default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `respuestas`
--

DROP TABLE IF EXISTS `respuestas`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL,
  `respuesta` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `soluciones`
--

DROP TABLE IF EXISTS `soluciones`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `soluciones` (
  `id_jugador` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `respuesta` varchar(20) NOT NULL,
  PRIMARY KEY  (`id_jugador`,`id_pregunta`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
