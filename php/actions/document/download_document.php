<?php

    session_start();

    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";

    $document_token = $_GET['token'] ?? '';

    if ($document_token === '') {
        error_log("No se pudo descargar el documento. Token vacío.");
        exit;
    }

    $mysqli = connection_db();
    $document = findDocumentWithToken($mysqli, $document_token);
    $mysqli->close();

    if (!$document) {
        error_log("No se pudo descargar el documento. Token no encontrado: " . $document_token);
        exit;
    }

    if ((int) $document['id_estado_documento'] !== 1) {
        error_log("No se pudo descargar el documento. Documento no activo: " . $document_token);
        exit;
    }

    $file_path = __DIR__ . "/../../../" . $document['archivo'];

    if (!file_exists($file_path)) {
        error_log("No se pudo descargar el documento. Archivo no encontrado: " . $file_path);
        exit;
    }

    header("Content-Type: application/pdf");
    header('Content-Disposition: attachment; filename="' . $document['nombre'] . '.pdf"');
    header("Content-Length: " . filesize($file_path));

    readfile($file_path);
    exit;

?>