CREATE TABLE IF NOT EXISTS estado_funcionario ( 
    id_estado_funcionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS servicio (
    id_servicio INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS funcionario (
    id_funcionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    cedula VARCHAR(8) NOT NULL UNIQUE,
    nacionalidad VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    departamento VARCHAR(50) NOT NULL,
    localidad VARCHAR(50) NOT NULL,
    direccion VARCHAR(50) NOT NULL,
    numero_puerta VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    cargo VARCHAR(50) NOT NULL,
    fecha_ingreso DATE NOT NULL,
    pass VARCHAR(255) NOT NULL,
    id_estado_funcionario INT NOT NULL,
    FOREIGN KEY (id_estado_funcionario) REFERENCES estado_funcionario(id_estado_funcionario)
);

CREATE TABLE IF NOT EXISTS telefono_funcionario (
    id_telefono INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    telefono VARCHAR(20) NOT NULL,
    id_funcionario INT NOT NULL,
    UNIQUE (id_funcionario, telefono),
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS copiloto (
    id_copiloto VARCHAR(10) NOT NULL PRIMARY KEY,
    especialidad VARCHAR(50) NOT NULL,
    id_funcionario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS conductor (
    id_conductor VARCHAR(10) NOT NULL PRIMARY KEY,
    vencimiento_carnet DATE NOT NULL,
    categoria_carnet VARCHAR(20) NOT NULL,
    id_funcionario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS administrativo (
    id_administrativo VARCHAR(10) NOT NULL PRIMARY KEY,
    permisos VARCHAR(50) NOT NULL,
    id_funcionario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS super_usuario (
    id_super_usuario VARCHAR(10) NOT NULL PRIMARY KEY,
    id_funcionario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);


CREATE TABLE IF NOT EXISTS estado_documento (
    id_estado_documento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS accion (
    id_accion INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS documento (
    id_documento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    archivo VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_estado_documento INT NOT NULL DEFAULT 1,
    id_categoria INT NOT NULL,
    id_servicio INT NOT NULL,
    FOREIGN KEY (id_estado_documento) REFERENCES estado_documento(id_estado_documento),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria),
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio)
);

CREATE TABLE IF NOT EXISTS qr (
    id_qr INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(64) NOT NULL UNIQUE,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_documento INT NOT NULL UNIQUE,
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)

);

CREATE TABLE IF NOT EXISTS administra_documento (
    id_administra_documento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_accion INT NOT NULL,
    id_administrativo VARCHAR(10) NOT NULL,
    id_documento INT NOT NULL,
    FOREIGN KEY (id_accion) REFERENCES accion(id_accion),
    FOREIGN KEY (id_administrativo) REFERENCES administrativo(id_administrativo),
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)
);

CREATE TABLE IF NOT EXISTS estado_ruta (
    id_estado_ruta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS ruta (
    id_ruta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    origen VARCHAR(50) NOT NULL,
    destino VARCHAR(50) NOT NULL,
    id_estado_ruta INT NOT NULL,
    FOREIGN KEY (id_estado_ruta) REFERENCES estado_ruta(id_estado_ruta)
);

CREATE TABLE IF NOT EXISTS ubicacion (
    id_ubicacion INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS ubicacion_ruta (
    id_ubicacion_ruta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    orden TINYINT NOT NULL,
    id_ruta INT NOT NULL,
    id_ubicacion INT NOT NULL,
    UNIQUE (id_ruta, orden),
    FOREIGN KEY (id_ruta) REFERENCES ruta(id_ruta),
    FOREIGN KEY (id_ubicacion) REFERENCES ubicacion(id_ubicacion)
);

CREATE TABLE IF NOT EXISTS estado_ambulancia (
    id_estado_ambulancia INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS ambulancia (
    id_ambulancia INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    matricula VARCHAR(10) NOT NULL UNIQUE,
    marca varchar(50) NOT NULL,
    modelo varchar(50) NOT NULL,
    anio YEAR NOT NULL,
    descripcion TEXT NOT NULL,
    id_estado_ambulancia INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_ambulancia) REFERENCES estado_ambulancia(id_estado_ambulancia)
);

CREATE TABLE IF NOT EXISTS acompaniante (
    id_acompaniante INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(8) NOT NULL UNIQUE,
    nombre varchar(50) NOT NULL,
    apellido varchar(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS estado_paciente (
    id_estado_paciente INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS paciente (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(8) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    telefono VARCHAR(20) NOT NULL UNIQUE,
    direccion VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    id_estado_paciente INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_paciente) REFERENCES estado_paciente(id_estado_paciente)
);

CREATE TABLE IF NOT EXISTS paciente_acompaniante (
    id_paciente_acompaniante INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_paciente INT NOT NULL,
    cedula_acompaniante VARCHAR(8) NOT NULL,
    FOREIGN KEY (cedula_acompaniante) REFERENCES acompaniante(cedula),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente)
);

CREATE TABLE IF NOT EXISTS estado_encuesta (
    id_estado_encuesta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS encuesta (
    id_encuesta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL UNIQUE,
    contenido JSON NOT NULL,
    id_estado_encuesta INT NOT NULL,
    id_servicio INT NOT NULL,
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio),
    FOREIGN KEY (id_estado_encuesta) REFERENCES estado_encuesta(id_estado_encuesta)
);

CREATE TABLE IF NOT EXISTS respuesta_encuesta (
    id_respuesta_encuesta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_encuesta INT NOT NULL,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cedula VARCHAR(8) NULL,
    respuestas JSON NOT NULL,
    FOREIGN KEY (id_encuesta) REFERENCES encuesta(id_encuesta)
);

CREATE TABLE IF NOT EXISTS estado_muestra (
    id_estado_muestra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS muestra (
    id_muestra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL,
    tipo varchar(50) NOT NULL,
    descripcion varchar(100) NOT NULL,
    id_paciente INT NOT NULL,
    id_estado_muestra INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_estado_muestra) REFERENCES estado_muestra(id_estado_muestra)
);

CREATE TABLE IF NOT EXISTS estado_traslado (
    id_estado_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS estado_solicitud (
    id_estado_solicitud INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS solicitud_traslado (
    id_solicitud INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_administrativo varchar(10) NOT NULL,
    id_estado_solicitud INT NOT NULL,
    FOREIGN KEY (id_administrativo) REFERENCES administrativo(id_administrativo) ON UPDATE CASCADE,
    FOREIGN KEY (id_estado_solicitud) REFERENCES estado_solicitud(id_estado_solicitud)
);

CREATE TABLE IF NOT EXISTS traslado (
    id_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    hora_inicio DATETIME NULL,
    hora_fin DATETIME NULL,
    id_solicitud INT NOT NULL,
    id_copiloto varchar(10) NOT NULL,
    id_conductor varchar(10) NOT NULL,
    id_ambulancia INT NOT NULL,
    id_ruta INT NOT NULL,
    id_paciente INT NOT NULL,
    id_servicio INT NOT NULL,
    FOREIGN KEY (id_solicitud) REFERENCES solicitud_traslado(id_solicitud),
    FOREIGN KEY (id_copiloto) REFERENCES copiloto(id_copiloto) ON UPDATE CASCADE,
    FOREIGN KEY (id_conductor) REFERENCES conductor(id_conductor) ON UPDATE CASCADE,
    FOREIGN KEY (id_ambulancia) REFERENCES ambulancia(id_ambulancia) ON UPDATE CASCADE,
    FOREIGN KEY (id_ruta) REFERENCES ruta(id_ruta),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio)
);

CREATE TABLE IF NOT EXISTS muestra_traslado (
    id_muestra_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_muestra INT NOT NULL,
    id_traslado INT NOT NULL,
    UNIQUE (id_muestra, id_traslado),
    FOREIGN KEY (id_muestra) REFERENCES muestra(id_muestra),
    FOREIGN KEY (id_traslado) REFERENCES traslado(id_traslado)
);

CREATE TABLE IF NOT EXISTS historial_traslado (
    id_historial_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    descripcion VARCHAR(255) NOT NULL,
    id_traslado INT NOT NULL,
    id_estado_traslado INT NOT NULL,
    FOREIGN KEY (id_traslado) REFERENCES traslado(id_traslado),
    FOREIGN KEY (id_estado_traslado) REFERENCES estado_traslado(id_estado_traslado)
);

CREATE TABLE IF NOT EXISTS estado_elemento (
    id_estado_elemento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS elemento (
    id_elemento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    tipo varchar(50) NOT NULL,
    subtipo varchar(50) NOT NULL,
    descripcion varchar(255) NOT NULL,
    id_estado_elemento INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_elemento) REFERENCES estado_elemento(id_estado_elemento)
);

CREATE TABLE IF NOT EXISTS elemento_traslado (
    id_elemento_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_traslado INT NOT NULL,
    id_elemento INT NOT NULL,
    cantidad INT NOT NULL,
    UNIQUE (id_traslado, id_elemento),
    FOREIGN KEY (id_traslado) REFERENCES traslado(id_traslado),
    FOREIGN KEY (id_elemento) REFERENCES elemento(id_elemento)
);

INSERT INTO accion (id_accion, nombre) VALUES
(1, 'Cargar'),
(2, 'Activar'),
(3, 'Desactivar'),
(4, 'Modificar');

INSERT INTO estado_ruta (nombre) VALUES
('Activa'),
('Inactiva');

INSERT INTO estado_muestra (nombre) VALUES
('Activa'),
('Inactiva');

INSERT INTO estado_ambulancia (nombre) VALUES
('Activa'),
('Inactiva'),
('Inválida'),
('Mantenimiento');

INSERT INTO servicio (nombre) VALUES
('Documento'),
('Traslado');

INSERT INTO estado_funcionario (nombre) VALUES
('Activo'),
('Licencia médica'),
('Licencia anual'),
('Seguro de paro'),
('Suspendido'),
('Inactivo'),
('Jubilado');

INSERT INTO estado_documento (nombre) VALUES
('Activo'),
('Inactivo'),
('Eliminado');

INSERT INTO estado_elemento (nombre) VALUES
('Activo'),
('Inactivo'),
('Eliminado');

INSERT INTO estado_paciente (nombre) VALUES
('Activo'),
('Inactivo'),
('Fallecido');

INSERT INTO estado_encuesta (nombre) VALUES
('Activo'),
('Inactivo');

INSERT INTO estado_solicitud (nombre) VALUES
('Pendiente'),
('Aceptada'),
('Rechazada');

INSERT INTO estado_traslado (nombre) VALUES
('Pendiente'),
('En curso'),
('En pausa'),
('Finalizado');

INSERT INTO funcionario (id_funcionario, nombre, apellido, cedula, nacionalidad, fecha_nacimiento, departamento, localidad, direccion, numero_puerta, email, cargo, fecha_ingreso, pass, id_estado_funcionario)
VALUES (1, 'Administrador', 'Super User', '00000000', 'Uruguayo/a', '1010-10-10', 'Montevideo', 'Montevideo', 'Av. Italia s/n - Montevideo', '11600', 'atencionalusuario@hc.edu.uy', 'SU', '1010-10-10', '$2y$10$0KvyaZEqJ.1h0BMhpTbWde62FEnA1XCidlAdJJXzMbW4FRlGYfX6W', 1),
(2, 'Ejemplo', 'Uno', '00000001', 'Argentino/a', '1990-10-10', 'Montevideo', 'Montevideo', 'Calle Falsa 001', '001', 'exampleuno@email.com', 'FA', '2010-10-10', '$2y$10$shcLVAjGUlXXcqyN3CIrue5MhvpyCsCI2EOXVHZkhlwdluQlqTiXq', 1),
(3, 'Ejemplo', 'Dos', '00000002', 'Uruguayo/a', '2000-10-10', 'Montevideo', 'Montevideo', 'Calle Falsa 002', '001 Bis', 'exampledos@email.com', 'FA', '2020-10-10', '$2y$10$2J279HMUZUY4Nc81t5HsoezRmHeGgnrUgLnepCCO/6sgCV/AMoZi6', 1),
(4, 'Ejemplo', 'Tres', '00000003', 'Uruguayo/a', '1990-10-10', 'Canelones', 'Salinas', 'Calle Falsa 003', '002', 'exampletres@email.com', 'DR', '2010-10-10', '$2y$10$RpMKIvf80mfbEXB7FpSF6uQlpe82ExcS.pSem9jVx23eR02XPdK9O', 1),
(5, 'Ejemplo', 'Cinco', '00000005', 'Uruguayo/a', '1990-10-10', 'Durazno', 'Durazno', 'Calle Falsa 005', '003 Bis', 'examplecinco@email.com', 'CO', '2010-10-10', '$2y$10$tOdSIUbtDD/ca77wluKfbuPCH/yvxyYqeTq3jM2.VpwLUnCinfGcC', 1),
(6, 'Ejemplo', 'Cuatro', '00000004', 'Uruguayo/a', '2000-10-10', 'Montevideo', 'Cerro Porteño', 'Calle Falsa 004', '666 Bis', 'examplecuatro@email.com', 'DR', '2020-10-10', '$2y$10$cZzbZqQOHeYcd/r2S4mTVeYd41tVPY5z/CvHkUqEZMqimb6Palc5G', 1),
(7, 'Ejemplo', 'Seis', '00000006', 'Chino/a', '2000-10-10', 'Montevideo', 'Casavalle', 'Calle Falsa 006', '007 Bis', 'exampleseis@email.com', 'CO', '2020-10-10', '$2y$10$o/MH1Br4Cv4N44muWl9KBumc0bR5QSAZv7QgaAgl8kQkmJnxP5mYC', 1);

INSERT INTO administrativo (id_administrativo, permisos, id_funcionario) VALUES
('FA00000001', 'high', 2),
('FA00000002', 'mid', 3);

INSERT INTO conductor (id_conductor, vencimiento_carnet, categoria_carnet, id_funcionario) VALUES
('DR00000003', '2030-10-10', 'F', 4),
('DR00000004', '2032-10-10', 'C', 6);

INSERT INTO copiloto (id_copiloto, especialidad, id_funcionario) VALUES
('CO00000005', 'Trauma', 5),
('CO00000006', 'Primeros Auxilios', 7);

INSERT INTO telefono_funcionario (id_telefono, telefono, id_funcionario) VALUES
(1, '+59899090901', 2),
(2, '+59899090902', 3),
(3, '+59899090903', 4),
(4, '+59899090905', 5),
(5, '+59899090666', 6),
(6, '+59899090906', 7);

INSERT INTO super_usuario (id_super_usuario, id_funcionario) VALUES
("SU00000001", 1);

INSERT INTO categoria (nombre) VALUES
('Protocolos'),
('Procedimientos'),
('Manuales'),
('Instructivos'),
('Normativas'),
('Formularios'),
('Circulares y Comunicados'),
('Guías Clínicas'),
('Capacitación'),
('Documentación Técnica'),
('Mantenimiento'),
('Calidad'),
('Seguridad'),
('Recursos Humanos'),
('Otros');

INSERT INTO accion(nombre) VALUES('Eliminar');

INSERT INTO documento (id_documento, nombre, archivo, fecha_creacion, id_estado_documento, id_categoria, id_servicio) VALUES
(1, 'Analisis de sangre', '/uploads/documents/archivo1.pdf', '2026-08-10 11:45:04', 1, 4, 1),
(2, 'Carnet de salud', '/uploads/documents/archivo2.pdf', '2026-08-10 11:45:21', 1, 3, 1),
(3, 'Dialisis', '/uploads/documents/archivo3.pdf', '2026-08-10 11:45:33', 1, 2, 1);

INSERT INTO qr (id_qr, token, fecha_creacion, id_documento) VALUES
(1, '6db3c9490a327205037d517872176966b8df32ed4a61a6d1ea186299b86c38f1', '2026-08-10 11:45:04', 1),
(2, '9516422c1593a0767ba9786e664acf226f24bc0912d3325d1ca568f8106886c9', '2026-08-10 11:45:21', 2),
(3, '797c3d782dddcb7dfcc27a47978a46a820311fc9dde5c9844c5da7f742658598', '2026-08-10 11:45:33', 3);

INSERT INTO administra_documento (id_administra_documento, id_accion, fecha_hora, id_administrativo, id_documento) VALUES
(1, 1, '2026-08-10 11:45:04', 'FA00000001', 1),
(2, 1, '2026-08-10 11:45:21', 'FA00000001', 2),
(3, 1, '2026-08-10 11:45:33', 'FA00000001', 3),
(4, 3, '2026-08-10 11:46:07', 'FA00000001', 1),
(5, 2, '2026-08-10 11:46:10', 'FA00000001', 1);

INSERT INTO encuesta (id_encuesta, titulo, contenido, id_estado_encuesta, id_servicio) VALUES
(1, 'Descarga de documentos', '{\"title\":\"Descarga de documentos\",\"questions\":[{\"title\":\"Que tan satisfecho está con el servicio\",\"type\":\"select\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Que tan satisfecho esta con el acceso a los documentos\",\"type\":\"select\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Que tan satisfecho esta con el personal de atención\",\"type\":\"checkbox\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}}]}', 1, 1),
(2, 'Servicio de traslado', '{\"title\":\"Servicio de traslado\",\"questions\":[{\"title\":\"Que tan satisfecho está con la atención\",\"type\":\"select\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Que tan satisfecho está con el servicio\",\"type\":\"select\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Indique que tan satisfactoria fue la experiencia\",\"type\":\"checkbox\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}}]}', 1, 2),
(3, 'Encuesta de documento', '{\"title\":\"Encuesta de documento\",\"questions\":[{\"title\":\"Que tan satisfecho está con el servicio\",\"type\":\"select\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Que tan satisfecho esta con el acceso a los documentos\",\"type\":\"select\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Pregunta de prueba\",\"type\":\"checkbox\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}}]}', 2, 1),
(4, 'Encuesta de traslado', '{\"title\":\"Encuesta de traslado\",\"questions\":[{\"title\":\"Que tan satisfecho está con la atención\",\"type\":\"select\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Que tan satisfecho está con el servicio\",\"type\":\"select\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Pregunta de prueba\",\"type\":\"checkbox\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Normal\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}}]}', 2, 2);

INSERT INTO paciente (id_paciente, cedula, nombre, apellido, fecha_nacimiento, telefono, direccion, email, id_estado_paciente) VALUES
(1, '23441242', 'Jhon', 'Dúran', '2008-08-08', '+59892784324', 'Calle Falsa 123', 'jhon234@gmail.com', 1),
(2, '23441241', 'Paolo', 'Guerrero', '2008-08-08', '+59892784325', 'Calle Falsa 1 4123', 'paolo2344@gmail.com', 1),
(3, '23441247', 'Xavier', 'Hernandéz', '2006-06-23', '+59892784321', 'Calle Falsa 2 1451', 'xavi232344@gmail.com', 1);

INSERT INTO elemento (id_elemento, codigo, nombre, tipo, subtipo, descripcion, id_estado_elemento) VALUES
(1, 'M001', 'Alcohol Rectificado', 'Biológico', 'Medicamentos e insumos de origen biológico', 'Botella de 1000ml', 1),
(2, 'I0001', 'Guantes de nitrilo', 'No Biológico', 'Equipamiento médico', 'Caja de 100 unidades', 1),
(3, 'H0001', 'Desfibrilador', 'No Biológico', 'Instrumental médico', 'Desfibrilador portátil', 1),
(4, 'I0002', 'Gasas rectificadoras', 'Biológico', 'Medicamentos e insumos de origen biológico', 'Caja de paquetes de gasas rectificadoras', 1);

INSERT INTO acompaniante (id_acompaniante, cedula, nombre, apellido) VALUES
(1, '50987462', 'Diego', 'Maradona'),
(2, '50959837', 'Franz', 'Beckenbauer'),
(3, '50950383', 'Alfredo', 'Di Stéfano');

INSERT INTO muestra (id_muestra, codigo, tipo, descripcion, id_paciente, id_estado_muestra) VALUES
(1, 'A2349185', 'Material dermatológico', 'Perú es good', 1, 1),
(2, 'A4908959', 'Orina', 'Muetra de prueba', 2, 1),
(3, 'A4902416', 'Tejidos', 'Muestra de prueba', 3, 1);