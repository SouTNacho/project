<?php

    /*

        Falta hacer que pregunte el formulario y modularizar o hace una nueva funcion para que lo cargue
        y un php llamado get_survey.php para solicitar y para que devuelva el survey correspondiente
        con estado activo al servicio de documentos

    */

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";

    $document_token = $_GET['token'];

    if (!isset($document_token)) {
        
        echo json_encode([
            'succes' => false,
            'message' => 'Error, el documento seleccionado no es valido.'
        ]);
        exit;
    }

    $mysqli = connection_db();

    try {

        $document = findDocumentWithToken($mysqli, $document_token);

        if (!$document) {
            
            $mysqli->close();
            echo json_encode([
                'succes' => false,
                'message' => 'Error, el documento seleccionado no existe.'
            ]);
            exit;
        }

        $mysqli->close();
        echo json_encode([
            'succes' => true,
            'message' => 'El documento seleccionado es válido.',
            'param' => '/php/pages/document/download_document_qr.php?token=' . $document_token
        ]);

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();
        echo json_encode([
            'succes' => false,
            'message' => 'Ha ocurrido un error, intente nuevamente.'
        ]);
        exit;
    }

?>