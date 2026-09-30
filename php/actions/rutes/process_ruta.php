<?php

require_once "../../conection.php";

$con = connection_db();
$accion = $_POST["accion"] ?? $_GET["accion"] ?? "";


// =====================================================
// LISTAR RUTAS
// =====================================================

if ($accion === "listar") {

    header('Content-Type: application/json; charset=utf-8');

    $id_estado = (int)($_POST["id_estado"] ?? 0);
    $phrase = trim($_POST["phrase"] ?? "");

    if (!in_array($id_estado, [0, 1, 2], true)) {
        echo json_encode([
            "success" => false,
            "message" => "Estado no válido."
        ]);
        $con->close();
        exit;
    }

    $phraseLike = "%" . $phrase . "%";

    if ($id_estado === 0) {

        $stmt = $con->prepare(
            "SELECT r.id_ruta,
                    r.nombre,
                    r.origen,
                    r.destino,
                    r.descripcion,
                    r.id_estado_ruta,
                    e.nombre AS estado
             FROM ruta r
             INNER JOIN estado_ruta e
                ON r.id_estado_ruta = e.id_estado_ruta
             WHERE r.nombre LIKE ?
                OR r.origen LIKE ?
                OR r.destino LIKE ?
                OR r.descripcion LIKE ?
             ORDER BY r.id_ruta DESC"
        );

        $stmt->bind_param(
            "ssss",
            $phraseLike,
            $phraseLike,
            $phraseLike,
            $phraseLike
        );

    } else {

        $stmt = $con->prepare(
            "SELECT r.id_ruta,
                    r.nombre,
                    r.origen,
                    r.destino,
                    r.descripcion,
                    r.id_estado_ruta,
                    e.nombre AS estado
             FROM ruta r
             INNER JOIN estado_ruta e
                ON r.id_estado_ruta = e.id_estado_ruta
             WHERE r.id_estado_ruta = ?
               AND (
                    r.nombre LIKE ?
                    OR r.origen LIKE ?
                    OR r.destino LIKE ?
                    OR r.descripcion LIKE ?
               )
             ORDER BY r.id_ruta DESC"
        );

        $stmt->bind_param(
            "issss",
            $id_estado,
            $phraseLike,
            $phraseLike,
            $phraseLike,
            $phraseLike
        );
    }

    if (!$stmt->execute()) {
        echo json_encode([
            "success" => false,
            "message" => "No se pudieron obtener las rutas."
        ]);
        $stmt->close();
        $con->close();
        exit;
    }

    $result = $stmt->get_result();
    $routes = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        "success" => true,
        "item" => $routes
    ]);

    $stmt->close();
    $con->close();
    exit;
}


// =====================================================
// AGREGAR RUTA
// =====================================================

if ($accion === "agregar") {

    $nombre = trim($_POST["nombre"] ?? "");
    $origen = trim($_POST["origen"] ?? "");
    $destino = trim($_POST["destino"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");

    if ($nombre === "" || $origen === "" || $destino === "") {
        echo "Completa todos los campos obligatorios.";
        $con->close();
        exit;
    }

    if ($descripcion === "") {
        $descripcion = null;
    }

    $stmt = $con->prepare(
        "SELECT id_ruta FROM ruta WHERE nombre = ?"
    );

    $stmt->bind_param("s", $nombre);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Ya existe una ruta con ese nombre.";
        $stmt->close();
        $con->close();
        exit;
    }

    $stmt->close();

    // Estado 1 = Activa
    $estado = 1;

    $stmt = $con->prepare(
        "INSERT INTO ruta
        (nombre, origen, destino, descripcion, id_estado_ruta)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssi",
        $nombre,
        $origen,
        $destino,
        $descripcion,
        $estado
    );

    if ($stmt->execute()) {
        echo "La ruta se ha registrado correctamente.";
    } else {
        echo "Error al registrar la ruta: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    exit;
}


// =====================================================
// CAMBIAR ESTADO
// =====================================================

if ($accion === "cambiar_estado") {

    header('Content-Type: application/json; charset=utf-8');

    $id_ruta = (int)($_POST["id_ruta"] ?? 0);
    $estado = (int)($_POST["id_estado_ruta"] ?? 0);

    if ($id_ruta <= 0 || !in_array($estado, [1, 2], true)) {
        echo json_encode([
            "success" => false,
            "message" => "Datos no válidos."
        ]);
        $con->close();
        exit;
    }

    $stmt = $con->prepare(
        "UPDATE ruta
         SET id_estado_ruta = ?
         WHERE id_ruta = ?"
    );

    $stmt->bind_param("ii", $estado, $id_ruta);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "El estado de la ruta se ha actualizado correctamente."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al actualizar el estado: " . $stmt->error
        ]);
    }

    $stmt->close();
    $con->close();
    exit;
}


// =====================================================
// ELIMINAR / DESACTIVAR RUTA
// =====================================================

if ($accion === "eliminar") {

    $ruta_delete = trim($_POST["ruta_delete"] ?? "");

    if ($ruta_delete === "") {
        echo "Debes indicar la ruta.";
        $con->close();
        exit;
    }

    // Estado 2 = Inactiva
    $estado = 2;

    $stmt = $con->prepare(
        "UPDATE ruta
         SET id_estado_ruta = ?
         WHERE nombre = ?"
    );

    $stmt->bind_param("is", $estado, $ruta_delete);

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            echo "La ruta se ha desactivado correctamente.";
        } else {
            echo "No existe una ruta con ese nombre o ya está inactiva.";
        }

    } else {
        echo "Error al desactivar la ruta: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    exit;
}


// =====================================================
// ACTUALIZAR RUTA
// =====================================================

if ($accion === "actualizar") {

    $nombre_update = trim($_POST["nombre_update"] ?? "");
    $nombre_nuevo = trim($_POST["nombre_nuevo"] ?? "");
    $origen_update = trim($_POST["origen_update"] ?? "");
    $destino_update = trim($_POST["destino_update"] ?? "");
    $descripcion_update = trim($_POST["descripcion_update"] ?? "");

    $estadoRecibido = $_POST["id_estado_ruta_update"] ?? "";

    if ($nombre_update === "") {
        echo "Debes indicar la ruta que deseas actualizar.";
        $con->close();
        exit;
    }

    $stmt = $con->prepare(
        "SELECT id_ruta, nombre, origen, destino, descripcion, id_estado_ruta
         FROM ruta
         WHERE nombre = ?"
    );

    $stmt->bind_param("s", $nombre_update);
    $stmt->execute();

    $result = $stmt->get_result();
    $actual = $result->fetch_assoc();
    $stmt->close();

    if (!$actual) {
        echo "No existe una ruta con ese nombre.";
        $con->close();
        exit;
    }

    // Mantener datos actuales cuando no se indique un valor nuevo.
    $nombreFinal = $nombre_nuevo !== ""
        ? $nombre_nuevo
        : $actual["nombre"];

    $origenFinal = $origen_update !== ""
        ? $origen_update
        : $actual["origen"];

    $destinoFinal = $destino_update !== ""
        ? $destino_update
        : $actual["destino"];

    $descripcionFinal = $descripcion_update !== ""
        ? $descripcion_update
        : $actual["descripcion"];

    $estadoFinal = $estadoRecibido !== ""
        ? (int)$estadoRecibido
        : (int)$actual["id_estado_ruta"];

    if (!in_array($estadoFinal, [1, 2], true)) {
        echo "El estado seleccionado no es válido.";
        $con->close();
        exit;
    }

    // Verificar nombre duplicado si se cambia.
    if ($nombreFinal !== $actual["nombre"]) {

        $stmt = $con->prepare(
            "SELECT id_ruta
             FROM ruta
             WHERE nombre = ?
               AND id_ruta <> ?"
        );

        $stmt->bind_param(
            "si",
            $nombreFinal,
            $actual["id_ruta"]
        );

        $stmt->execute();

        $duplicate = $stmt->get_result();

        if ($duplicate->num_rows > 0) {
            echo "Ya existe una ruta con ese nombre.";
            $stmt->close();
            $con->close();
            exit;
        }

        $stmt->close();
    }

    // Si no cambió ningún dato, informarlo.
    if (
        $nombreFinal === $actual["nombre"] &&
        $origenFinal === $actual["origen"] &&
        $destinoFinal === $actual["destino"] &&
        (string)$descripcionFinal === (string)$actual["descripcion"] &&
        $estadoFinal === (int)$actual["id_estado_ruta"]
    ) {
        echo "No se realizaron cambios en la ruta.";
        $con->close();
        exit;
    }

    $stmt = $con->prepare(
        "UPDATE ruta
         SET nombre = ?,
             origen = ?,
             destino = ?,
             descripcion = ?,
             id_estado_ruta = ?
         WHERE id_ruta = ?"
    );

    $stmt->bind_param(
        "ssssii",
        $nombreFinal,
        $origenFinal,
        $destinoFinal,
        $descripcionFinal,
        $estadoFinal,
        $actual["id_ruta"]
    );

    if ($stmt->execute()) {
        echo "La ruta se ha actualizado correctamente.";
    } else {
        echo "Error al actualizar la ruta: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    exit;
}


echo "Acción no válida.";
$con->close();

?>
