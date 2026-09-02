<?php
    require_once "../../conection.php";

$accion = $_POST["accion"];

if ($accion === "agregar") {

$nombre = $_POST["nombre"];
$origen = $_POST["origen"];
$destino = $_POST["destino"];
$descripcion = $_POST["descripcion"];



$con = connection_db(); //llamo a la funcion de conexion a bd

 // Verificar si la ruta ya existe
    $stmt = $con->prepare(
        "SELECT nombre FROM ruta WHERE nombre = ?"
    );

    $stmt->bind_param("s", $nombre);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        echo "La ruta ya está registrada";

    } else {

if (empty($descripcion)) {
    $descripcion = null;
}


// Consultas preparadas que pidio carbonell
$stmt = $con->prepare(
    "INSERT INTO ruta (nombre, origen, destino, descripcion)
    VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("ssss", $nombre, $origen, $destino, $descripcion);

if ($stmt->execute()) {
    echo " La ruta se ha guardado correctamente";
} else {
    echo " Error al subir ruta: " . $stmt->error;
}
    }

}elseif ($accion === "eliminar") {
    $ruta_delete = $_POST["ruta_delete"];
    $con = connection_db(); //llamo a la funcion de conexion a bd

    $stmt = $con->prepare("DELETE FROM ruta WHERE nombre = ?");
    $stmt->bind_param("s", $ruta_delete);

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        echo "La ruta se ha eliminado correctamente";
    } else {
        echo "No existe una ruta con ese nombre";
    }

} else {
    echo "Error al eliminar ruta: " . $stmt->error;
}


} elseif ($accion === "actualizar") {

    $nombre_update = $_POST["nombre_update"];
    $nombre_nuevo = $_POST["nombre_nuevo"];
    $origen_update = $_POST["origen_update"];
    $destino_update = $_POST["destino_update"];
    $descripcion_update = $_POST["descripcion_update"];

    $con = connection_db();

    if (!empty($nombre_nuevo)){
        
        // Verificar si la nueva ruta ya existe
        $stmt = $con->prepare(
            "SELECT nombre FROM ruta WHERE nombre = ?"
        );

        $stmt->bind_param("s", $nombre_nuevo);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo "La nueva ruta ya está registrada";
            exit; 
        }
    }
    

    // No se modifica ningún dato
    if (empty($nombre_nuevo) && empty($origen_update) && empty($destino_update)) {
        echo "No hay datos para actualizar";
    }

    // Solo nombre
    elseif (!empty($nombre_nuevo) && empty($origen_update) && empty($destino_update)) {
        $stmt = $con->prepare("UPDATE ruta SET nombre = ? WHERE nombre = ?");
        $stmt->bind_param("ss", $nombre_nuevo, $nombre_update);
    }

    // Solo origen
    elseif (empty($origen_nuevo) && !empty($origen_update) && empty($destino_update)) {
        $stmt = $con->prepare("UPDATE ruta SET origen = ? WHERE nombre = ?");
        $stmt->bind_param("ss", $origen_update, $nombre_update);
    }

    // Solo destino
    elseif (empty($origen_nuevo) && empty($origen_update) && !empty($destino_update)) {
        $stmt = $con->prepare("UPDATE ruta SET destino = ? WHERE nombre = ?");
        $stmt->bind_param("ss", $destino_update, $nombre_update);
    }

    // nmbre y origen
    elseif (!empty($nombre_nuevo) && !empty($origen_update) && empty($destino_update)) {
        $stmt = $con->prepare("UPDATE ruta SET nombre = ?, origen = ? WHERE nombre = ?");
        $stmt->bind_param("sss", $nombre_nuevo, $origen_update, $nombre_update);
    }

    // nombre y destino
    elseif (!empty($nombre_nuevo) && empty($origen_update) && !empty($destino_update)) {
        $stmt = $con->prepare("UPDATE ruta SET nombre = ?, destino = ? WHERE nombre = ?");
        $stmt->bind_param("sss", $nombre_nuevo, $destino_update, $nombre_update);
    }

    // origen y destino
    elseif (empty($nombre_nuevo) && !empty($origen_update) && !empty($destino_update)) {
        $stmt = $con->prepare("UPDATE ruta SET origen = ?, destino = ? WHERE nombre = ?");
        $stmt->bind_param("sss", $origen_update, $destino_update, $nombre_update);
    }

    // las 3
    else {
        $stmt = $con->prepare("UPDATE ruta SET nombre = ?, origen = ?, destino = ? WHERE nombre = ?");
        $stmt->bind_param("ssss", $nombre_nuevo, $origen_update, $destino_update, $nombre_update);
    }

    // Ejecutar la consulta
    if (isset($stmt)) {

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            echo "La ruta se ha actualizado correctamente";
        } else {
            echo "No existe una ruta con ese nombre";
        }

    } else {
        echo "Error al actualizar ruta: " . $stmt->error;
    }
}
}