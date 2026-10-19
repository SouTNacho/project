-- Ejecutar UNA VEZ en phpMyAdmin sobre la base de datos byp.
CREATE TABLE IF NOT EXISTS `mensaje_contacto` (
  `id_mensaje` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `correo` VARCHAR(254) NOT NULL,
  `mensaje` TEXT NOT NULL,
  `consentimiento` TINYINT(1) NOT NULL,
  `fecha_consentimiento` DATETIME NOT NULL,
  `fecha_envio` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mensaje`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
