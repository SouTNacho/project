<?php

require_once "../../conection.php";

$accion = $_POST["accion"] ?? "";

$con = connection_db();


// agregar ubicación
if ($accion === "agregar") {

    $nombre = trim($_POST["nombre_ubicacion"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $departamento = trim($_POST["departamento"] ?? "");
    $localidad = trim($_POST["localidad"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");

    if ($descripcion === "") {
        $descripcion = null;
    }

    // verificación de campos obligatorios
    if (
        $nombre === "" ||
        $direccion === "" ||
        $departamento === "" ||
        $localidad === ""
    ) {
        echo "Complete todos los campos obligatorios";
        exit;
    }

    // verifico si ya existe una ubicación con ese nombre
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
        exit;
    }

    // registro la ubicación
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


//eliminar ubicación
} elseif ($accion === "eliminar") {

    $nombre_ubicacion_delete = trim(
        $_POST["nombre_ubicacion_delete"] ?? ""
    );

    if ($nombre_ubicacion_delete === "") {

        echo "Debe indicar la ubicación que desea eliminar";
        exit;
    }

    $stmt = $con->prepare(
        "DELETE FROM ubicacion
         WHERE nombre = ?"
    );

    $stmt->bind_param(
        "s",
        $nombre_ubicacion_delete
    );

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo "La ubicación se ha eliminado correctamente";

        } else {

            echo "No existe una ubicación con ese nombre";
        }

    } else {

        echo "Error al eliminar ubicación: " . $stmt->error;
    }


// actualizar ubicación
} elseif ($accion === "actualizar") {

    $nombre_ubicacion_update = trim(
        $_POST["nombre_ubicacion_update"] ?? ""
    );

    $nombre_nuevo = trim(
        $_POST["nombre_nuevo"] ?? ""
    );

    $direccion_update = trim(
        $_POST["direccion_update"] ?? ""
    );

    $departamento_update = trim(
        $_POST["departamento_update"] ?? ""
    );

    $localidad_update = trim(
        $_POST["localidad_update"] ?? ""
    );

    $descripcion_update = trim(
        $_POST["descripcion_update"] ?? ""
    );


    // verificar que se haya indicado qué ubicación modificar
    if ($nombre_ubicacion_update === "") {

        echo "Debe indicar la ubicación que desea actualizar";
        exit;
    }


    // verificar que se haya ingresado algún dato
    if (
        $nombre_nuevo === "" &&
        $direccion_update === "" &&
        $departamento_update === "" &&
        $localidad_update === "" &&
        $descripcion_update === ""
    ) {

        echo "Debe completar algún dato para actualizar la ubicación";
        exit;
    }


    // verificar que la ubicación exista
    $stmt = $con->prepare(
        "SELECT id_ubicacion
         FROM ubicacion
         WHERE nombre = ?"
    );

    $stmt->bind_param(
        "s",
        $nombre_ubicacion_update
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {

        echo "No existe una ubicación con ese nombre";
        exit;
    }


// actualizar todos los campos
    if (
        $nombre_nuevo !== "" &&
        $direccion_update !== "" &&
        $departamento_update !== "" &&
        $localidad_update !== "" &&
        $descripcion_update !== ""
    ) {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET nombre = ?,
                 direccion = ?,
                 departamento = ?,
                 localidad = ?,
                 descripcion = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ssssss",
            $nombre_nuevo,
            $direccion_update,
            $departamento_update,
            $localidad_update,
            $descripcion_update,
            $nombre_ubicacion_update
        );


// actualizar nombre y departamento
    } elseif (
        $nombre_nuevo !== "" &&
        $direccion_update !== ""
    ) {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET nombre = ?,
                 direccion = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "sss",
            $nombre_nuevo,
            $direccion_update,
            $nombre_ubicacion_update
        );


// actualizar nombre 
    } elseif (
        $nombre_nuevo !== "" &&
        $departamento_update !== ""
    ) {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET nombre = ?,
                 departamento = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "sss",
            $nombre_nuevo,
            $departamento_update,
            $nombre_ubicacion_update
        );
    } elseif ($nombre_nuevo !== "") {

        // verificat que el nuevo nombre no esté usado
        $stmt = $con->prepare(
            "SELECT id_ubicacion
             FROM ubicacion
             WHERE nombre = ?
             AND nombre != ?"
        );

        $stmt->bind_param(
            "ss",
            $nombre_nuevo,
            $nombre_ubicacion_update
        );

        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            echo "Ya existe una ubicación con ese nombre";
            exit;
        }

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET nombre = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ss",
            $nombre_nuevo,
            $nombre_ubicacion_update
        );


// actualizar dirección
    } elseif ($direccion_update !== "") {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET direccion = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ss",
            $direccion_update,
            $nombre_ubicacion_update
        );
    } elseif ($direccion_update !== "") {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET direccion = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ss",
            $direccion_update,
            $nombre_ubicacion_update
        );


// actualizar departamento
    } elseif ($departamento_update !== "") {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET departamento = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ss",
            $departamento_update,
            $nombre_ubicacion_update
        );
    } elseif ($departamento_update !== "") {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET departamento = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ss",
            $departamento_update,
            $nombre_ubicacion_update
        );


// actualizar localidad
    } elseif ($localidad_update !== "") {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET localidad = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ss",
            $localidad_update,
            $nombre_ubicacion_update
        );

    } elseif ($localidad_update !== "") {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET localidad = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ss",
            $localidad_update,
            $nombre_ubicacion_update
        );


    } elseif ($descripcion_update !== "") {

        $stmt = $con->prepare(
            "UPDATE ubicacion
             SET descripcion = ?
             WHERE nombre = ?"
        );

        $stmt->bind_param(
            "ss",
            $descripcion_update,
            $nombre_ubicacion_update
        );
    }

    if (isset($stmt)) {

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {

                echo "La ubicación se ha actualizado correctamente";

            } else {

                echo "No se realizaron cambios en la ubicación";
            }

        } else {

            echo "Error al actualizar ubicación: " . $stmt->error;
        }
    }

} else {

    echo "Acción no válida";
}

?>