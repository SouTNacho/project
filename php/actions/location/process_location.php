<?php

require_once "../../conection.php";

$accion = $_POST["accion"];


// AGREGAR 

if ($accion === "agregar") {

    $nombre = $_POST["nombre_ubicacion"];
    $direccion = $_POST["direccion"];
    $departamento = $_POST["departamento"];
    $localidad = $_POST["localidad"];
    $descripcion = $_POST["descripcion"];

    if (empty($descripcion)) {
        $descripcion = null;
    }

    $con = connection_db();

    // Busco la localidad y verifico que pertenezca al departamento indicado
    $stmt = $con->prepare(
        "SELECT localidad.id_localidad FROM localidad INNER JOIN departamento 
        ON localidad.id_departamento = departamento.id_departamento WHERE localidad.nombre = ? AND departamento.nombre = ?"
    );

    $stmt->bind_param("ss", $localidad, $departamento);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {

        echo "La localidad no existe en el departamento indicado";

    } else {

        $fila = $resultado->fetch_assoc();
        $id_localidad = $fila["id_localidad"];

        // Verifico si ya existe una ubicación con ese nombre
        $stmt = $con->prepare("SELECT id_ubicacion FROM ubicacion WHERE nombre = ?");

        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            echo "La ubicación ya existe";

        } else {

            $stmt = $con->prepare(
                "INSERT INTO ubicacion (nombre, direccion, descripcion, id_localidad) VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param("sssi", $nombre, $direccion, $descripcion, $id_localidad);

            if ($stmt->execute()) {
                echo "La ubicación se ha guardado correctamente";
            } else {
                echo "Error al subir ubicación: " . $stmt->error;
            }
        }
    }


// ELIMINAAR


} elseif ($accion === "eliminar") {

    $nombre_ubicacion_delete = $_POST["nombre_ubicacion_delete"];

    $con = connection_db();

    $stmt = $con->prepare("DELETE FROM ubicacion WHERE nombre = ?");
    $stmt->bind_param("s", $nombre_ubicacion_delete);

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            echo "La ubicación se ha eliminado correctamente";
        } else {
            echo "No existe una ubicación con ese nombre";
        }

    } else {
        echo "Error al eliminar ubicación: " . $stmt->error;
    }


//ACTUALIZAR


} elseif ($accion === "actualizar") {

    $nombre_ubicacion_update = $_POST["nombre_ubicacion_update"];
    $nombre_nuevo = $_POST["nombre_nuevo"];
    $direccion_update = $_POST["direccion_update"];
    $departamento_update = $_POST["departamento_update"];
    $localidad_update = $_POST["localidad_update"];
    $descripcion_update = $_POST["descripcion_update"];

    $con = connection_db();

    if (
        empty($nombre_nuevo) && empty($direccion_update) && empty($departamento_update) && 
        empty($localidad_update) && empty($descripcion_update)
    ) {

        echo "debes completar algun dato para actualizar la ubicación";

    } else {

        $id_localidad = null;

        // Si se vs a cambiar departamento o localidad, busco el nuevo id_localidad
        if (!empty($departamento_update) || !empty($localidad_update)) {

            if (empty($departamento_update) || empty($localidad_update)) {

                echo "Para cambiar la localidad debe indicar departamento y localidad";

            } else {

                $stmt = $con->prepare(
                    "SELECT localidad.id_localidad FROM localidad INNER JOIN departamento
                    ON localidad.id_departamento = departamento.id_departamento
                    WHERE localidad.nombre = ? AND departamento.nombre = ?"
                );

                $stmt->bind_param("ss", $localidad_update, $departamento_update);
                $stmt->execute();

                $resultado = $stmt->get_result();

                if ($resultado->num_rows === 0) {

                    echo "La localidad no existe en el departamento indicado";
                    exit;

                } else {

                    $fila = $resultado->fetch_assoc();
                    $id_localidad = $fila["id_localidad"];
                }
            }
        }


        // Actualizo los campos que fueron ingresados
        if (!empty($nombre_nuevo) && empty($direccion_update) && empty($departamento_update) &&
            empty($localidad_update) && empty($descripcion_update)
        ) {

            $stmt = $con->prepare(
                "UPDATE ubicacion SET nombre = ? WHERE nombre = ?"
            );

            $stmt->bind_param("ss", $nombre_nuevo, $nombre_ubicacion_update);


        } elseif (
            empty($nombre_nuevo) && !empty($direccion_update) && empty($departamento_update) &&
            empty($localidad_update) && empty($descripcion_update)
        ) {

            $stmt = $con->prepare("UPDATE ubicacion SET direccion = ? WHERE nombre = ?");

            $stmt->bind_param("ss", $direccion_update, $nombre_ubicacion_update);


        } elseif (
            empty($nombre_nuevo) && empty($direccion_update) && empty($departamento_update) && 
            empty($localidad_update) && !empty($descripcion_update)
        ) {

            $stmt = $con->prepare("UPDATE ubicacion SET descripcion = ? WHERE nombre = ?");

            $stmt->bind_param("ss", $descripcion_update, $nombre_ubicacion_update);


        } elseif (
            empty($nombre_nuevo) &&empty($direccion_update) &&!empty($departamento_update) &&!empty($localidad_update) &&
            empty($descripcion_update)) {

            $stmt = $con->prepare("UPDATE ubicacion SET id_localidad = ? WHERE nombre = ?");

            $stmt->bind_param("is", $id_localidad, $nombre_ubicacion_update);


        } elseif (
            !empty($nombre_nuevo) && !empty($direccion_update) && empty($departamento_update) &&
            empty($localidad_update) && empty($descripcion_update)) {

            $stmt = $con->prepare("UPDATE ubicacion SET nombre = ?, direccion = ? WHERE nombre = ?");

            $stmt->bind_param("sss", $nombre_nuevo, $direccion_update, $nombre_ubicacion_update);


        } elseif (!empty($nombre_nuevo) && empty($direccion_update) &&! empty($departamento_update) &&
            !empty($localidad_update) && empty($descripcion_update)) {

            $stmt = $con->prepare("UPDATE ubicacion SET nombre = ?, id_localidad = ? WHERE nombre = ?");

            $stmt->bind_param("sis", $nombre_nuevo, $id_localidad, $nombre_ubicacion_update);


        } elseif (!empty($nombre_nuevo) && empty($direccion_update) && empty($departamento_update) &&
            empty($localidad_update) && !empty($descripcion_update)) {

            $stmt = $con->prepare("UPDATE ubicacion SET nombre = ?, descripcion = ? WHERE nombre = ?");

            $stmt->bind_param("sss", $nombre_nuevo, $descripcion_update, $nombre_ubicacion_update);


        } elseif (empty($nombre_nuevo) && !empty($direccion_update) && !empty($departamento_update) &&
            !empty($localidad_update) && empty($descripcion_update)) {

            $stmt = $con->prepare("UPDATE ubicacion SET direccion = ?, id_localidad = ? WHERE nombre = ?");

            $stmt->bind_param("sis", $direccion_update, $id_localidad, $nombre_ubicacion_update);


        } elseif (
            empty($nombre_nuevo) && !empty($direccion_update) && empty($departamento_update) &&
            empty($localidad_update) && !empty($descripcion_update)) {

            $stmt = $con->prepare("UPDATE ubicacion SET direccion = ?, descripcion = ? WHERE nombre = ?");

            $stmt->bind_param("sss", $direccion_update, $descripcion_update, $nombre_ubicacion_update);


        } elseif (empty($nombre_nuevo) && empty($direccion_update) && !empty($departamento_update) 
        && !empty($localidad_update) && !empty($descripcion_update)) {

            $stmt = $con->prepare("UPDATE ubicacion SET id_localidad = ?, descripcion = ? WHERE nombre = ?");

            $stmt->bind_param("iss", $id_localidad, $descripcion_update, $nombre_ubicacion_update);


        } elseif (!empty($nombre_nuevo) && !empty($direccion_update) && !empty($departamento_update) 
        && !empty($localidad_update) && !empty($descripcion_update)) {

            $stmt = $con->prepare("UPDATE ubicacion SET nombre = ?, direccion = ?, descripcion = ?, id_localidad = ? WHERE nombre = ?"
            );

            $stmt->bind_param("sssis", $nombre_nuevo, $direccion_update, $descripcion_update,
             $id_localidad, $nombre_ubicacion_update);


        } else {

            if (!empty($nombre_nuevo) && !empty($direccion_update) && !empty($descripcion_update)) {

                $stmt = $con->prepare("UPDATE ubicacion SET nombre = ?, direccion = ?, descripcion = ? WHERE nombre = ?");

                $stmt->bind_param("ssss", $nombre_nuevo, $direccion_update, $descripcion_update, $nombre_ubicacion_update);


            } elseif (!empty($nombre_nuevo) && !empty($direccion_update) && !empty($departamento_update)
             && !empty($localidad_update)) {

                $stmt = $con->prepare("UPDATE ubicacion SET nombre = ?, direccion = ?, id_localidad = ? WHERE nombre = ?"
                );

                $stmt->bind_param("ssis", $nombre_nuevo, $direccion_update, $id_localidad, $nombre_ubicacion_update);


            } elseif (!empty($nombre_nuevo) && !empty($descripcion_update) && !empty($departamento_update)
             && !empty($localidad_update)) {

                $stmt = $con->prepare("UPDATE ubicacion SET nombre = ?, descripcion = ?, id_localidad = ? WHERE nombre = ?");

                $stmt->bind_param("ssis", $nombre_nuevo, $descripcion_update, $id_localidad, $nombre_ubicacion_update);


            } elseif (!empty($direccion_update) && !empty($departamento_update) && !empty($localidad_update)
             && !empty($descripcion_update)) {

                $stmt = $con->prepare("UPDATE ubicacion SET direccion = ?, descripcion = ?, id_localidad = ? WHERE nombre = ?");

                $stmt->bind_param("ssis", $direccion_update, $descripcion_update, $id_localidad, $nombre_ubicacion_update);
            }
        }


        if (isset($stmt)) {

            if ($stmt->execute()) {
                echo "La ubicación se ha actualizado correctamente";
            } else {
                echo "Error al actualizar ubicación: " . $stmt->error;
            }
        }
    }
}

?>