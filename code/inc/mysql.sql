/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `ugcs` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ugcs`;

CREATE TABLE IF NOT EXISTS `cliente` (
  `id` char(13) NOT NULL DEFAULT '',
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `correo` varchar(100) NOT NULL DEFAULT '',
  `direcc` varchar(250) NOT NULL DEFAULT '',
  `telefono` varchar(10) NOT NULL DEFAULT '',
  `estado` tinyint(1) NOT NULL DEFAULT 0,
  `fecact` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `index2` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `facdet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `facid` int(11) unsigned NOT NULL DEFAULT 0,
  `iteid` int(11) unsigned NOT NULL DEFAULT 0,
  `cantid` double(10,2) unsigned NOT NULL DEFAULT 0.00,
  `prcvta` double(10,2) unsigned NOT NULL DEFAULT 0.00,
  `totnet` double(10,2) unsigned NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `index2` (`facid`,`id`) USING BTREE,
  CONSTRAINT `FK_facdet_factura` FOREIGN KEY (`facid`) REFERENCES `factura` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `factura` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codcli` char(13) NOT NULL DEFAULT '',
  `ordid` int(11) NOT NULL DEFAULT 0,
  `codtec` char(10) NOT NULL DEFAULT '',
  `totbru` double(10,2) NOT NULL DEFAULT 0.00,
  `totiva` double(10,2) NOT NULL DEFAULT 0.00,
  `totnet` double(10,2) NOT NULL DEFAULT 0.00,
  `estado` tinyint(1) NOT NULL DEFAULT 0,
  `obsini` text DEFAULT NULL,
  `obsfin` text DEFAULT NULL,
  `fecemi` date DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_factura_cliente` (`codcli`),
  KEY `FK_factura_tecnico` (`codtec`),
  CONSTRAINT `FK_factura_cliente` FOREIGN KEY (`codcli`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_factura_tecnico` FOREIGN KEY (`codtec`) REFERENCES `tecnico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '0 Producto, 1 Servicio',
  `refere` varchar(25) NOT NULL DEFAULT '',
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `marca` varchar(30) NOT NULL DEFAULT '',
  `cantid` double(10,2) unsigned NOT NULL DEFAULT 0.00,
  `costo` double(10,2) unsigned NOT NULL DEFAULT 0.00,
  `prcvta` double(10,2) unsigned NOT NULL DEFAULT 0.00,
  `estado` tinyint(1) NOT NULL DEFAULT 0,
  `fecact` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='productos/servicios' `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `orddet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordid` int(11) unsigned NOT NULL DEFAULT 0,
  `tipdet` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '0 Observ, 1 Ingreso Rep, 2 Ingreso Serv',
  `fecdet` datetime DEFAULT NULL,
  `iteid` int(11) unsigned NOT NULL DEFAULT 0,
  `prcvta` double(10,2) unsigned NOT NULL DEFAULT 0.00,
  `cantid` double(10,2) unsigned NOT NULL DEFAULT 0.00,
  `estado` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `observ` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_orddet_orden` (`ordid`),
  CONSTRAINT `FK_orddet_orden` FOREIGN KEY (`ordid`) REFERENCES `orden` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `orden` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codcli` char(13) NOT NULL DEFAULT '',
  `codtec` char(10) NOT NULL DEFAULT '',
  `marca` varchar(50) NOT NULL DEFAULT '',
  `modelo` varchar(50) NOT NULL DEFAULT '',
  `imei` varchar(15) NOT NULL DEFAULT '',
  `observ` text NOT NULL DEFAULT '',
  `fecing` datetime DEFAULT NULL,
  `fecfac` datetime DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_orden_cliente` (`codcli`),
  KEY `FK_orden_tecnico` (`codtec`),
  CONSTRAINT `FK_orden_cliente` FOREIGN KEY (`codcli`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_orden_tecnico` FOREIGN KEY (`codtec`) REFERENCES `tecnico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `ordlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordid` int(11) unsigned NOT NULL,
  `feclog` datetime DEFAULT NULL,
  `observ` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_ordlog_orden` (`ordid`),
  CONSTRAINT `FK_ordlog_orden` FOREIGN KEY (`ordid`) REFERENCES `orden` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `registro` (
  `userid` char(10) NOT NULL DEFAULT '' COMMENT 'codigo de user',
  `fecexp` char(15) NOT NULL DEFAULT '0' COMMENT 'fecha unix',
  `token` text DEFAULT NULL,
  `ipnum` char(15) NOT NULL DEFAULT '' COMMENT 'numero de ip',
  `fecact` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`userid`),
  CONSTRAINT `FK_registro_users` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `tecnico` (
  `id` char(10) NOT NULL DEFAULT '',
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `correo` varchar(100) NOT NULL DEFAULT '',
  `direcc` varchar(250) NOT NULL DEFAULT '',
  `telefono` varchar(10) NOT NULL DEFAULT '',
  `estado` tinyint(4) NOT NULL DEFAULT 0,
  `fecact` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

CREATE TABLE IF NOT EXISTS `users` (
  `userid` char(10) NOT NULL DEFAULT '' COMMENT 'codigo',
  `password` varchar(100) NOT NULL DEFAULT '' COMMENT 'contraseña',
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `correo` varchar(150) NOT NULL DEFAULT '',
  `tipusr` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '0 Admin, 1 Tecnico, 2 Caja',
  `estado` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 Activo, 9 Inactivo',
  `fecact` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'fecha',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 `PAGE_COMPRESSED`='ON';

INSERT INTO `users` (`userid`, `password`, `nombre`, `correo`, `tipusr`, `estado`, `fecact`) VALUES
	('admin', '$2y$10$fKL9Vq4BjXS/BdFG.rKALODBCXBgattCseZ3vm/vStZ45FHmqs4yO', 'Administrador', 'admin@algo.com', 0, 0, '2025-06-24 21:32:39'),
	('cajero', '$2y$10$2Higsn85bK2sEQat2VUfh.093EcrQcNmY/LWSzb587jr5iPS01OPG', 'Cajero', 'cajero@algo.com', 2, 0, '2025-06-24 21:32:43'),
	('tecnico1', '$2y$10$2Higsn85bK2sEQat2VUfh.093EcrQcNmY/LWSzb587jr5iPS01OPG', 'Tecnico', 'tecnico@algo.com', 1, 0, '2025-06-24 21:32:43');


/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
