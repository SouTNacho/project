<?php

require_once "../../conection.php";

$accion = $_POST["accion"] ?? "";
$con = connection_db();


// =====================================================
// LISTAR UBICACIONES
// =====================================================

if ($accion === "listar") {

    header("Content-Type: application/json; charset=utf-8");

    $result = $con->query(
        "SELECT id_ubicacion,
                nombre,
                direccion,
                departamento,
                localidad,
                descripcion
         FROM ubicacion
         ORDER BY nombre ASC"
    );

    if (!$result) {
        echo json_encode([
            "success" => false,
            "message" => "No se pudieron cargar las ubicaciones."
        ]);
        $con->close();
        exit;
    }

    echo json_encode([
        "success" => true,
        "item" => $result->fetch_all(MYSQLI_ASSOC)
    ]);

    $con->close();
    exit;
}


// =====================================================
// AGREGAR UBICACIÓN
// =====================================================

if ($accion === "agregar") {

    $nombre = trim($_POST["nombre_ubicacion"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $departamento = trim($_POST["departamento"] ?? "");
    $localidad = trim($_POST["localidad"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");

    if (
        $nombre === "" ||
        $direccion === "" ||
        $departamento === "" ||
        $localidad === ""
    ) {
        echo "Complete todos los campos obligatorios";
        exit;
    }

    $descripcion = $descripcion === "" ? null : $descripcion;

    $stmt = $con->prepare(
        "SELECT id_ubicacion
         FROM ubicacion
         WHERE nombre = ?"
    );

    $stmt->bind_param("s", $nombre);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "La ubicación ya existe";
        $stmt->close();
        $con->close();
        exit;
    }

    $stmt->close();

    $stmt = $con->prepare(
        "INSERT INTO ubicacion
        (nombre, direccion, departamento, localidad, descripcion)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssss",
        $nombre,
        $direccion,
        $departamento,
        $localidad,
        $descripcion
    );

    if ($stmt->execute()) {
        echo "La ubicación se ha guardado correctamente";
    } else {
        echo "Error al subir ubicación: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    exit;
}


// =====================================================
// ELIMINAR UBICACIÓN
// =====================================================

if ($accion === "eliminar") {

    $id_ubicacion = (int)($_POST["id_ubicacion"] ?? 0);

    if ($id_ubicacion <= 0) {
        echo "Debe indicar una ubicación válida";
        exit;
    }

    $stmt = $con->prepare(
        "DELETE FROM ubicacion
         WHERE id_ubicacion = ?"
    );

    $stmt->bind_param("i", $id_ubicacion);

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            echo "La ubicación se ha eliminado correctamente";
        } else {
            echo "No existe la ubicación seleccionada";
        }

    } else {
        echo "Error al eliminar ubicación: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    exit;
}


// =====================================================
// ACTUALIZAR UBICACIÓN
// =====================================================

if ($accion === "actualizar") {

    $id_ubicacion = (int)($_POST["id_ubicacion"] ?? 0);
    $nombre = trim($_POST["nombre_ubicacion_update"] ?? "");
    $direccion = trim($_POST["direccion_update"] ?? "");
    $departamento = trim($_POST["departamento_update"] ?? "");
    $localidad = trim($_POST["localidad_update"] ?? "");
    $descripcion = trim($_POST["descripcion_update"] ?? "");

    if ($id_ubicacion <= 0) {
        echo "Debe seleccionar una ubicación";
        exit;
    }

    if (
        $nombre === "" ||
        $direccion === "" ||
        $departamento === "" ||
        $localidad === ""
    ) {
        echo "Nombre, dirección, departamento y localidad son obligatorios";
        exit;
    }

    $descripcion = $descripcion === "" ? null : $descripcion;

    $stmt = $con->prepare(
        "SELECT id_ubicacion
         FROM ubicacion
         WHERE nombre = ?
           AND id_ubicacion <> ?"
    );

    $stmt->bind_param("si", $nombre, $id_ubicacion);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "Ya existe una ubicación con ese nombre";
        $stmt->close();
        $con->close();
        exit;
    }

    $stmt->close();

    $stmt = $con->prepare(
        "UPDATE ubicacion
         SET nombre = ?,
             direccion = ?,
             departamento = ?,
             localidad = ?,
             descripcion = ?
         WHERE id_ubicacion = ?"
    );

    $stmt->bind_param(
        "sssssi",
        $nombre,
        $direccion,
        $departamento,
        $localidad,
        $descripcion,
        $id_ubicacion
    );

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            echo "La ubicación se ha actualizado correctamente";
        } else {
            echo "No se realizaron cambios en la ubicación";
        }

    } else {
        echo "Error al actualizar ubicación: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    exit;
}


echo "Acción no válida";
$con->close();

?>
