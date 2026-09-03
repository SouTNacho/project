<?php

    require_once __DIR__ . "/../models/muestra_model.php";


    function createMuestra(
        $mysqli,
        $tipo,
        $subtipo,
        $descripcion,
        $id_paciente
    ) {

        insertMuestra(
            $mysqli,
            $tipo,
            $subtipo,
            $descripcion,
            $id_paciente
        );

        return true;
    }


    function getMuestraById($mysqli, $muestra_id) {

        return findMuestraWithId($mysqli, $muestra_id);
    }


    function getAllMuestras($mysqli) {

        return findAllMuestras($mysqli);
    }


    function editMuestra(
        $mysqli,
        $tipo,
        $subtipo,
        $descripcion,
        $id_paciente,
        $muestra_id
    ) {

        updateMuestra(
            $mysqli,
            $tipo,
            $subtipo,
            $descripcion,
            $id_paciente,
            $muestra_id
        );

        return true;
    }


    function removeMuestra($mysqli, $muestra_id) {

        deleteMuestra($mysqli, $muestra_id);

        return true;
    }

?>