<?php

    session_start();

    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";

    $document_token = $_GET['token'];

    $mysqli = connection_db();
    $document = findDocumentWithToken($mysqli, $document_token);

    if($document) {

        header("Content-Type: application/pdf");
        header('Content-Disposition: attachment; filename="' . $document['nombre'] . '.pdf"');
        header("Content-Length: " . filesize($document['archivo']));

        readfile($document['archivo']);
        exit;
    }

?>