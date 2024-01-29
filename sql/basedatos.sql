CREATE DATABASE IF NOT EXISTS cursosapi;
USE cursosapi;

DROP TABLE IF EXISTS ponentes;
CREATE TABLE `ponentes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `nombre` varchar(40) DEFAULT NULL,
    `apellidos` varchar(40) DEFAULT NULL,
    `imagen` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `tags` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `redes` text,
    CONSTRAINT pk_ponentes PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
    `apellidos` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `rol` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `confirmado` boolean DEFAULT FALSE,
    `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `token_exp` timestamp NULL DEFAULT NULL,

    CONSTRAINT pk_usuarios PRIMARY KEY(id),
    CONSTRAINT uq_email UNIQUE(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS categorias;
CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS dias;
CREATE TABLE `dias` (
 `id` int NOT NULL AUTO_INCREMENT,
 `nombre` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS horas;
CREATE TABLE `horas` (
 `id` int NOT NULL AUTO_INCREMENT,
 `hora` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `horas` (`id`, `hora`) VALUES
  (1, '10:00 - 10:55'),
  (2, '11:00 - 11:55'),
  (3, '12:00 - 12:55'),
  (4, '13:00 - 13:55'),
  (5, '16:00 - 16:55'),
  (6, '17:00 - 17:55'),
  (7, '18:00 - 18:55'),
  (8, '19:00 - 19:55');

DROP TABLE IF EXISTS eventos;
CREATE TABLE `eventos` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `descripcion` text,
   `disponibles` int DEFAULT NULL,
   `categoria_id` int NOT NULL,
   `dia_id` int NOT NULL,
   `hora_id` int NOT NULL,
   `ponente_id` int NOT NULL,
   PRIMARY KEY (`id`),
   KEY `fk_eventos_categorias_idx` (`categoria_id`),
   KEY `fk_eventos_dias1_idx` (`dia_id`),
   KEY `fk_eventos_horas1_idx` (`hora_id`),
   KEY `fk_eventos_ponentes1_idx` (`ponente_id`),
   CONSTRAINT `fk_eventos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
   CONSTRAINT `fk_eventos_dias1` FOREIGN KEY (`dia_id`) REFERENCES `dias` (`id`),
   CONSTRAINT `fk_eventos_horas1` FOREIGN KEY (`hora_id`) REFERENCES `horas` (`id`),
   CONSTRAINT `fk_eventos_ponentes1` FOREIGN KEY (`ponente_id`) REFERENCES `ponentes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;