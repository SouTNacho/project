<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");
    
    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../models/category_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";
    
    $id_administrativo = "FA00000001";
    $document_name = trim($_POST['name'] ?? '');
    $file_document = $_FILES['file_document'] ?? null;
    $document_category = (int) ($_POST['category_id'] ?? 0);

    if (validateEmptyData($document_name) || validateEmptyData($document_category) ||
        $file_document === null || $file_document['error'] !== UPLOAD_ERR_OK) {

        echo json_encode(
            ['success' => false,
            'message' => 'Todos los campos son obligatorios.']
        );
        exit;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file_document['tmp_name']);

    if ($mime_type !== 'application/pdf') {
        echo json_encode([
            'success' => false,
            'message' => 'El archivo debe ser un PDF.'
        ]);
        exit;
    }

    if (!validateString($document_name)) {
        echo json_encode(
            ['success' => false,
            'message' => 'El nombre ingresado no es válido']
        );
        exit;
    }

    $mysqli = connection_db();
    
    try {
        if (!isCategoryActive($mysqli, $document_category)) {
            $mysqli->close();
            echo json_encode([
                'success' => false,
                'message' => 'La categoría seleccionada no existe o está inactiva.'
            ]);
            exit;
        }

        $mysqli->begin_transaction();

        $id_document = insertDocument($mysqli, $document_name, 'default', $document_category, 1);

        $file_name = "archivo" . $id_document . ".pdf";

        $ruta = __DIR__ . "/../../../uploads/documents/" . $file_name;
        $db_ruta = "/uploads/documents/" . $file_name;


        if (!move_uploaded_file($file_document['tmp_name'], $ruta)) {
            throw new RuntimeException("Error al cargar el documento.");
        }

        updateDocumentFile($mysqli, $db_ruta, $id_document);
        
        $token = bin2hex(random_bytes(32));
        while (findDocumentToken($mysqli, $token)) {

            $token = bin2hex(random_bytes(32));
        }

        insertQr($mysqli, $token, $id_document);
        documentAction($mysqli, 1, $id_administrativo, $id_document);

        $mysqli->commit();
        $mysqli->close();

        echo json_encode(
            ['success' => true,
            'message' => 'El registro fue exitoso.']
        );
        exit;

    } catch (mysqli_sql_exception | RuntimeException $e) {

        $mysqli->rollback();

        if (isset($ruta) && file_exists($ruta)) {
            unlink($ruta);
        }

        $mysqli->close();

        echo json_encode(
            ['success' => false,
            'message' => $e->getMessage()]
        );
        exit;
    }

?>