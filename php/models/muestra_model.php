<?php

function findMuestraWithId($mysqli, $muestra_id)
{
    $stmt = $mysqli->prepare(
        "SELECT
            muestra.id_muestra,
            muestra.codigo,
            muestra.tipo,
            muestra.descripcion,
            muestra.id_paciente,
            muestra.id_estado_muestra,
            paciente.cedula
        FROM muestra
        INNER JOIN paciente
            ON muestra.id_paciente = paciente.id_paciente
        WHERE muestra.id_muestra = ?"
    );

    $stmt->bind_param("i", $muestra_id);

    $stmt->execute();

    $result = $stmt->get_result();

    $muestra = $result->fetch_assoc();

    $stmt->close();

    return $muestra;
}


function findAllMuestras($mysqli)
{
    $stmt = $mysqli->prepare(
        "SELECT
            muestra.id_muestra,
            muestra.codigo,
            muestra.tipo,
            muestra.descripcion,
            muestra.id_paciente,
            muestra.id_estado_muestra,
            paciente.cedula
        FROM muestra
        INNER JOIN paciente
            ON muestra.id_paciente = paciente.id_paciente
        ORDER BY muestra.id_muestra ASC"
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $muestras = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $muestras;
}


function insertMuestra(
    $mysqli,
    $codigo,
    $tipo,
    $descripcion,
    $id_paciente
) {
    $stmt = $mysqli->prepare(
        "INSERT INTO muestra
        (codigo, tipo, descripcion, id_paciente)
        VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssi",
        $codigo,
        $tipo,
        $descripcion,
        $id_paciente
    );

    $result = $stmt->execute();

    $stmt->close();

    return $result;
}


function updateMuestra(
    $mysqli,
    $codigo,
    $tipo,
    $descripcion,
    $id_paciente,
    $muestra_id
) {
    $stmt = $mysqli->prepare(
        "UPDATE muestra
        SET codigo = ?,
            tipo = ?,
            descripcion = ?,
            id_paciente = ?
        WHERE id_muestra = ?"
    );

    $stmt->bind_param(
        "sssii",
        $codigo,
        $tipo,
        $descripcion,
        $id_paciente,
        $muestra_id
    );

    $result = $stmt->execute();

    $stmt->close();

    return $result;
}


function deleteMuestra($mysqli, $muestra_id)
{
    $stmt = $mysqli->prepare(
        "DELETE FROM muestra
        WHERE id_muestra = ?"
    );

    $stmt->bind_param("i", $muestra_id);

    $result = $stmt->execute();

    $stmt->close();

    return $result;
}

?>