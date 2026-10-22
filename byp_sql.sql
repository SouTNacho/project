CREATE TABLE IF NOT EXISTS estado_funcionario ( 
    id_estado_funcionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS servicio (
    id_servicio INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS estado_categoria (
    id_estado_categoria INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    id_estado_categoria INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_categoria) REFERENCES estado_categoria(id_estado_categoria)
);

CREATE TABLE IF NOT EXISTS estado_tipo_muestra (
    id_estado_tipo_muestra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS tipo_muestra (
    id_tipo_muestra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    id_estado_tipo_muestra INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_tipo_muestra) REFERENCES estado_tipo_muestra(id_estado_tipo_muestra)
);

CREATE TABLE IF NOT EXISTS estado_subtipo_elemento (
    id_estado_subtipo_elemento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS subtipo_elemento (
    id_subtipo_elemento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    tipo INT NOT NULL,
    UNIQUE(nombre, tipo),
    id_estado_subtipo_elemento INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_subtipo_elemento) REFERENCES estado_subtipo_elemento(id_estado_subtipo_elemento)
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
    fecha_ingreso DATE NOT NULL,
    id_estado_funcionario INT NOT NULL DEFAULT 1,
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
    id_copiloto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NULL UNIQUE,
    especialidad VARCHAR(50) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    id_funcionario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS conductor (
    id_conductor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NULL UNIQUE,
    vencimiento_carnet DATE NOT NULL,
    categoria_carnet VARCHAR(20) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    id_funcionario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS administrativo (
    id_administrativo INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NULL UNIQUE,
    permisos VARCHAR(50) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    id_funcionario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS estado_super_usuario ( 
    id_estado_super_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS super_usuario (
    id_super_usuario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(10) NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    permisos VARCHAR(10) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    id_estado_super_usuario INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_super_usuario) REFERENCES estado_super_usuario(id_estado_super_usuario)
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
    archivo VARCHAR(255) NOT NULL,
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
    codigo_administrativo VARCHAR(10) NOT NULL,
    id_documento INT NOT NULL,
    FOREIGN KEY (id_accion) REFERENCES accion(id_accion),
    FOREIGN KEY (codigo_administrativo) REFERENCES administrativo(codigo) ON UPDATE CASCADE,
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)
);
CREATE TABLE IF NOT EXISTS estado_ruta (
    id_estado_ruta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS estado_ubicacion (
    id_estado_ubicacion INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS ubicacion (
    id_ubicacion INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    departamento VARCHAR(50) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    latitud DECIMAL(10,7) NOT NULL,
    longitud DECIMAL(10,7) NOT NULL,
    id_estado_ubicacion INT NOT NULL DEFAULT 1,
    UNIQUE (latitud, longitud),
    FOREIGN KEY (id_estado_ubicacion) REFERENCES estado_ubicacion(id_estado_ubicacion)
);

CREATE TABLE IF NOT EXISTS ruta (
    id_ruta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    id_ubicacion_origen INT NOT NULL,
    id_ubicacion_intermedia INT NULL,
    id_ubicacion_destino INT NOT NULL,
    id_estado_ruta INT NOT NULL DEFAULT 1,
    UNIQUE (id_ubicacion_origen, id_ubicacion_intermedia, id_ubicacion_destino),
    FOREIGN KEY (id_ubicacion_origen) REFERENCES ubicacion(id_ubicacion),
    FOREIGN KEY (id_ubicacion_intermedia) REFERENCES ubicacion(id_ubicacion),
    FOREIGN KEY (id_ubicacion_destino) REFERENCES ubicacion(id_ubicacion),
    FOREIGN KEY (id_estado_ruta) REFERENCES estado_ruta(id_estado_ruta)
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
    anio YEAR NOT NULL CHECK (anio >= 1900),
    descripcion TEXT NOT NULL,
    id_estado_ambulancia INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_ambulancia) REFERENCES estado_ambulancia(id_estado_ambulancia)
);

CREATE TABLE IF NOT EXISTS estado_acompaniante (
    id_estado_acompaniante INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS acompaniante (
    id_acompaniante INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(8) NOT NULL UNIQUE,
    nombre varchar(50) NOT NULL,
    apellido varchar(50) NOT NULL,
    id_estado_acompaniante INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_acompaniante) REFERENCES estado_acompaniante(id_estado_acompaniante)
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
    telefono VARCHAR(20) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    id_estado_paciente INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado_paciente) REFERENCES estado_paciente(id_estado_paciente)
);

CREATE TABLE IF NOT EXISTS paciente_acompaniante (
    id_paciente_acompaniante INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_paciente INT NOT NULL,
    id_acompaniante INT NOT NULL,
    FOREIGN KEY (id_acompaniante) REFERENCES acompaniante(id_acompaniante),
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
    respuestas JSON NOT NULL,
    FOREIGN KEY (id_encuesta) REFERENCES encuesta(id_encuesta)
);

CREATE TABLE IF NOT EXISTS estado_muestra (
    id_estado_muestra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS muestra (
    id_muestra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL UNIQUE,
    descripcion varchar(100) NOT NULL,
    id_tipo_muestra INT NOT NULL,
    id_paciente INT NOT NULL,
    id_estado_muestra INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_tipo_muestra) REFERENCES tipo_muestra(id_tipo_muestra),
    FOREIGN KEY (id_estado_muestra) REFERENCES estado_muestra(id_estado_muestra)
);

CREATE TABLE IF NOT EXISTS estado_traslado (
    id_estado_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS traslado (
    id_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    hora_inicio_estimada DATETIME NOT NULL,
    duracion_estimada_minutos INT UNSIGNED NOT NULL,
    hora_inicio_real DATETIME NULL,
    hora_fin_real DATETIME NULL,
    codigo_administrativo VARCHAR(10) NOT NULL,
    codigo_copiloto VARCHAR(10) NULL,
    codigo_conductor VARCHAR(10) NOT NULL,
    id_ambulancia INT NOT NULL,
    id_ruta INT NOT NULL,
    id_paciente INT NULL,
    id_servicio INT NOT NULL DEFAULT 2,
    id_estado_traslado INT NOT NULL DEFAULT 1,
    FOREIGN KEY (codigo_administrativo) REFERENCES administrativo(codigo) ON UPDATE CASCADE,
    FOREIGN KEY (codigo_copiloto) REFERENCES copiloto(codigo) ON UPDATE CASCADE,
    FOREIGN KEY (codigo_conductor) REFERENCES conductor(codigo) ON UPDATE CASCADE,
    FOREIGN KEY (id_ambulancia) REFERENCES ambulancia(id_ambulancia),
    FOREIGN KEY (id_ruta) REFERENCES ruta(id_ruta),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio),
    FOREIGN KEY (id_estado_traslado) REFERENCES estado_traslado(id_estado_traslado)
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
    codigo VARCHAR(10) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    tipo varchar(50) NOT NULL,
    descripcion varchar(255) NOT NULL,
    id_subtipo_elemento INT NOT NULL,
    id_estado_elemento INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_subtipo_elemento) REFERENCES subtipo_elemento(id_subtipo_elemento),
    FOREIGN KEY (id_estado_elemento) REFERENCES estado_elemento(id_estado_elemento)
);

CREATE TABLE IF NOT EXISTS elemento_traslado (
    id_elemento_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_traslado INT NOT NULL,
    id_elemento INT NOT NULL,
    cantidad INT NOT NULL CHECK (cantidad >= 1),
    UNIQUE (id_traslado, id_elemento),
    FOREIGN KEY (id_traslado) REFERENCES traslado(id_traslado),
    FOREIGN KEY (id_elemento) REFERENCES elemento(id_elemento)
);

CREATE TABLE IF NOT EXISTS pendiente (
    id_pendiente INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    hora_inicio DATETIME NOT NULL,
    hora_fin DATETIME NOT NULL,
    id_traslado INT NOT NULL,
    codigo_copiloto VARCHAR(10) NULL,
    codigo_conductor VARCHAR(10) NULL,
    id_ambulancia INT NULL,
    id_paciente INT NULL,
    id_acompaniante INT NULL,
    id_muestra INT NULL,
    FOREIGN KEY (id_traslado) REFERENCES traslado(id_traslado),
    FOREIGN KEY (codigo_copiloto) REFERENCES copiloto(codigo) ON UPDATE CASCADE,
    FOREIGN KEY (codigo_conductor) REFERENCES conductor(codigo) ON UPDATE CASCADE,
    FOREIGN KEY (id_ambulancia) REFERENCES ambulancia(id_ambulancia),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_acompaniante) REFERENCES acompaniante(id_acompaniante),
    FOREIGN KEY (id_muestra) REFERENCES muestra(id_muestra)
);

CREATE TABLE IF NOT EXISTS mensaje_contacto (
    id_mensaje INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    respondido BOOLEAN NOT NULL DEFAULT false,
    fecha_envio DATETIME NOT NULL DEFAULT current_timestamp()
);

CREATE TABLE IF NOT EXISTS ubicacion_traslado (
    id_ubicacion_traslado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    latitud DECIMAL(10,7) NOT NULL,
    longitud DECIMAL(10,7) NOT NULL,
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_traslado INT NOT NULL,
    FOREIGN KEY (id_traslado) REFERENCES traslado(id_traslado)
);

INSERT INTO accion (id_accion, nombre) VALUES
(1, 'Cargar'),
(2, 'Activar'),
(3, 'Desactivar'),
(4, 'Modificar'),
(5, 'Eliminar');

INSERT INTO estado_ruta (nombre) VALUES
('Activa'),
('Inactiva'),
('Eliminada');

INSERT INTO estado_muestra (nombre) VALUES
('Activa'),
('Inactiva'),
('Eliminada'),
('Descartada');

INSERT INTO estado_ambulancia (nombre) VALUES
('Activa'),
('Inactiva'),
('Eliminada'),
('Mantenimiento');

INSERT INTO servicio (nombre) VALUES
('Documento'),
('Traslado');

INSERT INTO estado_funcionario (nombre) VALUES
('Activo'),
('Inactivo'),
('Eliminado'),
('Licencia anual'),
('Seguro de paro'),
('Suspendido'),
('Licencia médica'),
('Jubilado');

INSERT INTO estado_super_usuario (nombre) VALUES
('Activo'),
('Inactivo'),
('Eliminado');

INSERT INTO estado_categoria (nombre) VALUES
('Activa'),
('Inactiva');

INSERT INTO estado_tipo_muestra (nombre) VALUES
('Activo'),
('Inactivo');

INSERT INTO estado_subtipo_elemento (nombre) VALUES
('Activo'),
('Inactivo');

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
('Eliminado'),
('Fallecido');

INSERT INTO estado_acompaniante (nombre) VALUES
('Activo'),
('Inactivo'),
('Eliminado');

INSERT INTO estado_encuesta (nombre) VALUES
('Activo'),
('Inactivo');

INSERT INTO estado_traslado (nombre) VALUES
('Pendiente'),
('En curso'),
('En pausa'),
('Finalizado');

INSERT INTO estado_ubicacion (nombre) VALUES
('Activo'),
('Inactivo'),
('Eliminado');

INSERT INTO tipo_muestra (nombre, id_estado_tipo_muestra) VALUES
('Orina', 1),
('Heces', 1),
('Secreciones', 1),
('Esputo', 1),
('Saliva', 1),
('Líquidos corporales', 1),
('Tejido', 1),
('Células', 1),
('Médula ósea', 1),
('Material reproductivo', 1),
('Material dermatológico', 1),
('Material de heridas', 1),
('Otros', 1);

INSERT INTO subtipo_elemento (nombre, tipo, id_estado_subtipo_elemento) VALUES
('Medicamentos e insumos de origen biológico', 1, 1),
('Productos derivados de la sangre', 1, 1),
('Productos derivados de tejidos', 1, 1),
('Material biológico para investigación', 1, 1),
('Otros', 1, 1),
('Medicamentos e insumos de origen no biológico', 2, 1),
('Instrumental médico', 2, 1),
('Equipamiento médico', 2, 1),
('Equipamiento tecnológico', 2, 1),
('Material de mantenimiento', 2, 1),
('Material de limpieza', 2, 1),
('Elementos de protección', 2, 1),
('Mobiliario', 2, 1),
('Otros', 2, 1);

INSERT INTO categoria (nombre, id_estado_categoria) VALUES
('Protocolos', 1),
('Procedimientos', 1),
('Manuales', 1),
('Instructivos', 1),
('Normativas', 1),
('Formularios', 1),
('Circulares y Comunicados', 1),
('Guías Clínicas', 1),
('Capacitación', 1),
('Documentación Técnica', 1),
('Mantenimiento', 1),
('Calidad', 1),
('Seguridad', 1),
('Recursos Humanos', 1),
('Otros', 1);

INSERT INTO super_usuario (id_super_usuario, codigo, nombre, permisos, pass, id_estado_super_usuario) VALUES
(1, "SU00000001", 'HeadAdmin', 'All', '$2y$10$BqZO1Ja8hWkJ3yUdY1q9KepqzLzNz7ji0gLUyQl4KxJzJ3R8rAKLm', 1),
(2, "SU00000002", 'SubHeadAdmin', 'All', '$2y$10$BqZO1Ja8hWkJ3yUdY1q9KepqzLzNz7ji0gLUyQl4KxJzJ3R8rAKLm', 1);

INSERT INTO funcionario (nombre, apellido, cedula, nacionalidad, fecha_nacimiento, departamento, localidad, direccion, numero_puerta, email, fecha_ingreso, id_estado_funcionario)
VALUES ('Ejemplo', 'Uno', '00000001', 'Argentino/a', '1990-10-10', 'Montevideo', 'Montevideo', 'Calle Falsa 001', '001', 'exampleuno@email.com', '2010-10-10', 1),
('Ejemplo', 'Dos', '00000002', 'Uruguayo/a', '2000-10-10', 'Montevideo', 'Montevideo', 'Calle Falsa 002', '001 Bis', 'exampledos@email.com', '2020-10-10', 1),
('Ejemplo', 'Tres', '00000003', 'Uruguayo/a', '1990-10-10', 'Canelones', 'Salinas', 'Calle Falsa 003', '002', 'exampletres@email.com', '2010-10-10', 1),
('Ejemplo', 'Cinco', '00000005', 'Uruguayo/a', '1990-10-10', 'Durazno', 'Durazno', 'Calle Falsa 005', '003 Bis', 'examplecinco@email.com', '2010-10-10', 1),
('Ejemplo', 'Cuatro', '00000004', 'Uruguayo/a', '2000-10-10', 'Montevideo', 'Cerro Porteño', 'Calle Falsa 004', '666 Bis', 'examplecuatro@email.com', '2020-10-10', 1),
('Ejemplo', 'Seis', '00000006', 'Chino/a', '2000-10-10', 'Montevideo', 'Casavalle', 'Calle Falsa 006', '007 Bis', 'exampleseis@email.com', '2020-10-10', 1);

INSERT INTO telefono_funcionario (id_telefono, telefono, id_funcionario) VALUES
(1, '+59899090901', 1),
(2, '+59899090902', 2),
(3, '+59899090903', 3),
(4, '+59899090905', 4),
(5, '+59899090666', 5),
(6, '+59899090906', 6);

INSERT INTO administrativo (codigo, permisos, pass, id_funcionario) VALUES
('FA00000001', 'high', 'admin', 1),
('FA00000002', 'mid', 'admin', 2);

INSERT INTO conductor (codigo, vencimiento_carnet, categoria_carnet, pass, id_funcionario) VALUES
('DR00000001', '2030-10-10', 'F', 'HASH...', 3),
('DR00000002', '2032-10-10', 'C', 'HASH...', 5);

INSERT INTO copiloto (codigo, especialidad, pass, id_funcionario) VALUES
('CO00000001', 'Trauma', 'HASH...', 4),
('CO00000002', 'Primeros Auxilios', 'HASH...', 6);

INSERT INTO documento (id_documento, nombre, archivo, fecha_creacion, id_estado_documento, id_categoria, id_servicio) VALUES
(1, 'Analisis de sangre', '/uploads/documents/archivo1.pdf', '2026-08-10 11:45:04', 1, 4, 1),
(2, 'Carnet de salud', '/uploads/documents/archivo2.pdf', '2026-08-10 11:45:21', 1, 3, 1),
(3, 'Dialisis', '/uploads/documents/archivo3.pdf', '2026-08-10 11:45:33', 1, 2, 1);

INSERT INTO qr (id_qr, token, fecha_creacion, id_documento) VALUES
(1, '6db3c9490a327205037d517872176966b8df32ed4a61a6d1ea186299b86c38f1', '2026-08-10 11:45:04', 1),
(2, '9516422c1593a0767ba9786e664acf226f24bc0912d3325d1ca568f8106886c9', '2026-08-10 11:45:21', 2),
(3, '797c3d782dddcb7dfcc27a47978a46a820311fc9dde5c9844c5da7f742658598', '2026-08-10 11:45:33', 3);

INSERT INTO administra_documento (id_administra_documento, id_accion, fecha_hora, codigo_administrativo, id_documento) VALUES
(1, 1, '2026-08-10 11:45:04', 'FA00000001', 1),
(2, 1, '2026-08-10 11:45:21', 'FA00000001', 2),
(3, 1, '2026-08-10 11:45:33', 'FA00000001', 3),
(4, 3, '2026-08-10 11:46:07', 'FA00000001', 1),
(5, 2, '2026-08-10 11:46:10', 'FA00000001', 1);

INSERT INTO encuesta (id_encuesta, titulo, contenido, id_estado_encuesta, id_servicio) VALUES
(1, 'Encuesta Documentos Primera Prueba', '{\"title\":\"Encuesta Documentos Primera Prueba\",\"questions\":[{\"title\":\"Qué tan satisfecho está con la facilidad para acceder a los documentos\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Qué tan satisfecho está con la rapidez para obtener los documentos\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Qué tan satisfecho está con este servicio\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Encontró el documento requerido\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}},{\"title\":\"Recomendaría este servicio\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}}]}', 1, 1),
(2, 'Encuesta Documentos Segunda Prueba', '{\"title\":\"Encuesta Documentos Segunda Prueba\",\"questions\":[{\"title\":\"Qué tan satisfecho está con la facilidad para acceder a los documentos\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Pregunta de prueba satisfacción\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Recomendaría este servicio\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}}]}', 2, 1),
(3, 'Encuesta Traslados Primera Prueba', '{\"title\":\"Encuesta Traslados Primera Prueba\",\"questions\":[{\"title\":\"Qué tan satisfecho está con la atención recibida del personal\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Qué tan satisfecho está con las condiciones y comodidad de el traslado\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"ué tan satisfecho está con la puntualidad del traslado\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"El traslado se realizó según lo esperado\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}},{\"title\":\"Recomendaría este servicio\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}}]}', 1, 2),
(4, 'Encuesta Traslados Segunda Prueba', '{\"title\":\"Encuesta Traslados Segunda Prueba\",\"questions\":[{\"title\":\"Qué tan satisfecho está con la puntualidad del traslado\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Pregunta de prueba satisacción\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Recomendaría este servicio\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}}]}', 2, 2);

INSERT INTO paciente (id_paciente, cedula, nombre, apellido, fecha_nacimiento, telefono, direccion, email, id_estado_paciente) VALUES
(1, '23441242', 'Jhon', 'Dúran', '2008-08-08', '+59892784324', 'Calle Falsa 123', 'jhon234@gmail.com', 1),
(2, '23441241', 'Paolo', 'Guerrero', '2008-08-08', '+59892784325', 'Calle Falsa 1 4123', 'paolo2344@gmail.com', 1),
(3, '23441247', 'Xavier', 'Hernandéz', '2006-06-23', '+59892784321', 'Calle Falsa 2 1451', 'xavi232344@gmail.com', 1);

INSERT INTO elemento (id_elemento, codigo, nombre, tipo, descripcion, id_subtipo_elemento, id_estado_elemento) VALUES
(1, 'M001', 'Alcohol Rectificado', 'Biológico', 'Botella de 1000ml', 1, 1),
(2, 'I0001', 'Guantes de nitrilo', 'No Biológico', 'Caja de 100 unidades', 12, 1),
(3, 'H0001', 'Desfibrilador', 'No Biológico', 'Desfibrilador portátil', 8, 1),
(4, 'I0002', 'Gasas rectificadoras', 'Biológico', 'Caja de paquetes de gasas rectificadoras', 3, 1);

INSERT INTO acompaniante (id_acompaniante, cedula, nombre, apellido, id_estado_acompaniante) VALUES
(1, '50987462', 'Diego', 'Maradona', 1),
(2, '50959837', 'Franz', 'Beckenbauer', 1),
(3, '50950383', 'Alfredo', 'Di Stéfano', 1);

INSERT INTO muestra (id_muestra, codigo, descripcion, id_paciente, id_tipo_muestra, id_estado_muestra) VALUES
(1, 'A2349185', 'Perú es good', 1, 1, 1),
(2, 'A4908959', 'Muetra de prueba', 2, 2, 1),
(3, 'A4902416', 'Muestra de prueba', 3, 3, 1);

INSERT INTO ambulancia (id_ambulancia, matricula, marca, modelo, anio, descripcion, id_estado_ambulancia) VALUES
(1, 'SAM1003', 'Mercedez Benz', 'Sprinter', '2025', 'Especializada en transporte de insumos', 1),
(2, 'SAM1002', 'Fiat', 'Ducato', '2023', 'Especializada en transporte de pacientes', 1),
(3, 'SAM1004', 'Renault', 'Master', '2026', 'Propósito general', 1);

INSERT INTO respuesta_encuesta (id_respuesta_encuesta, id_encuesta, fecha_hora, respuestas) VALUES
(1, 1, '2026-09-11 11:45:51', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"1\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"1\"}]}'),
(2, 1, '2026-09-11 11:46:07', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"4\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"4\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"1\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"1\"}]}'),
(3, 1, '2026-09-11 11:46:35', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"3\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"2\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"2\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"1\"}]}');