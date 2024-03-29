CREATE DATABASE IF NOT EXISTS Apifutbol;
USE Apifutbol;

DROP TABLE IF EXISTS equipos;
CREATE TABLE `equipos` (
    `id` int NOT NULL AUTO_INCREMENT,
    `nombre` varchar(50) DEFAULT NULL,
    `ciudad` varchar(50) DEFAULT NULL,
    `division` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `color` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `redes` text,
    CONSTRAINT pk_equipos PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `apellidos` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `rol` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `confirmado` boolean DEFAULT FALSE,
    `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `token_exp` timestamp NULL DEFAULT NULL,

    CONSTRAINT pk_usuarios PRIMARY KEY(id),
    CONSTRAINT uq_email UNIQUE(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Inserción de equipos de la primera división de La Liga
INSERT INTO equipos (nombre, ciudad, division, color, redes) VALUES
    ('Real Madrid', 'Madrid', 'La Liga', 'Blanco', 'Twitter: @realmadrid, Facebook: /realmadrid'),
    ('Barcelona', 'Barcelona', 'La Liga', 'Azul y Granate', 'Twitter: @FCBarcelona, Instagram: @fcbarcelona'),
    ('Atletico de Madrid', 'Madrid', 'La Liga', 'Rojo y Blanco', 'Twitter: @Atleti, Facebook: /atleticodemadrid'),
    ('Valencia', 'Valencia', 'La Liga', 'Naranja y Negro', 'Twitter: @valenciacf, Instagram: @valenciacf'),
    ('Sevilla', 'Sevilla', 'La Liga', 'Blanco y Rojo', 'Twitter: @SevillaFC, Facebook: /SevillaFC'),
    ('Real Sociedad', 'San Sebastián', 'La Liga', 'Azul y Blanco', 'Twitter: @RealSociedad, Instagram: @realsociedad'),
    ('Villarreal', 'Villarreal', 'La Liga', 'Amarillo', 'Twitter: @VillarrealCF, Facebook: /VillarrealCF'),
    ('Athletic Bilbao', 'Bilbao', 'La Liga', 'Rojo y Blanco', 'Twitter: @AthleticClub, Instagram: @athleticclub'),
    ('Real Betis', 'Sevilla', 'La Liga', 'Verde y Blanco', 'Twitter: @RealBetis, Facebook: /RealBetisBalompie'),
    ('Levante', 'Valencia', 'La Liga', 'Granate y Azul', 'Twitter: @LevanteUD, Instagram: @levanteud'),
    ('Celta Vigo', 'Vigo', 'La Liga', 'Celeste y Blanco', 'Twitter: @RCCelta, Facebook: /rccelta'),
    ('Getafe', 'Getafe', 'La Liga', 'Azul y Blanco', 'Twitter: @GetafeCF, Instagram: @GetafeCF'),
    ('Granada', 'Granada', 'La Liga', 'Rojo y Blanco', 'Twitter: @GranadaCdeF, Facebook: /GranadaCdeF'),
    ('Espanyol', 'Barcelona', 'La Liga', 'Azul y Blanco', 'Twitter: @RCDEspanyol, Instagram: @rcdespanyol'),
    ('Alavés', 'Vitoria-Gasteiz', 'La Liga', 'Azul y Blanco', 'Twitter: @Alaves, Facebook: /alaves.oficial'),
    ('Mallorca', 'Palma de Mallorca', 'La Liga', 'Rojo y Negro', 'Twitter: @RCD_Mallorca, Instagram: @rcdmallorcaoficial'),
    ('Osasuna', 'Pamplona', 'La Liga', 'Rojo y Blanco', 'Twitter: @CAOsasuna, Facebook: /caosasuna'),
    ('Cadiz', 'Cádiz', 'La Liga', 'Amarillo y Azul', 'Twitter: @Cadiz_CF, Instagram: @cadizclubdefutbol');
