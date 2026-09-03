<?php

require_once __DIR__ . "/../models/muestra_model.php";


function createMuestra(
    $mysqli,
    $codigo,
    $tipo,
    $descripcion,
    $id_paciente
) {
    return insertMuestra(
        $mysqli,
        $codigo,
        $tipo,
        $descripcion,
        $id_paciente
    );
}


function getMuestraById($mysqli, $muestra_id)
{
    return findMuestraWithId(
        $mysqli,
        $muestra_id
    );
}


function getAllMuestras($mysqli)
{
    return findAllMuestras($mysqli);
}


function editMuestra(
    $mysqli,
    $codigo,
    $tipo,
    $descripcion,
    $id_paciente,
    $muestra_id
) {
    return updateMuestra(
        $mysqli,
        $codigo,
        $tipo,
        $descripcion,
        $id_paciente,
        $muestra_id
    );
}


function removeMuestra($mysqli, $muestra_id)
{
    return deleteMuestra(
        $mysqli,
        $muestra_id
    );
}

?>