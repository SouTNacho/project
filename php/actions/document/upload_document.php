<?php
    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/documents_functions.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";
    
    // Falta despues con la session obtener el id real del empleado
    $id_administrativo = "FA00000001";
    $document_name = $_POST['name'];
    $file_document = $_FILES['file_document'];
    $document_category = (int) $_POST['category_id'];

    if (!isset($_POST['name']) ||
    !isset($_POST['category_id']) ||
    !isset($_FILES['file_document'])) {

        echo json_encode(
            ['success' => false,
            'message' => 'Todos los campos son obligatorios.']
        );
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
    $document = findLastDocument($mysqli);

    $last_document = 0;
    if ($document) {

        $last_document = $document['id_documento'];
    }

    $ruta = __DIR__ . "/../../../uploads/documents/archivo" . ($last_document + 1) . ".pdf";
    $db_ruta = "/uploads/documents/" . "archivo" . ($last_document + 1) . ".pdf";

    if (!move_uploaded_file($file_document['tmp_name'], $ruta)) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cargar el documento']
        );
        exit;
    }

    $token = bin2hex(random_bytes(32));

    while (findDocumentToken($mysqli, $token)) {

        $token = bin2hex(random_bytes(32));
    }
    
    try {

        $mysqli->begin_transaction();

        $id_document = insertDocument($mysqli, $document_name, $db_ruta, $document_category, 1);
        insertQr($mysqli, $token, $id_document);

        documentAction($mysqli, 1, $id_administrativo, $id_document);
        $mysqli->commit();
        $mysqli->close();

        echo json_encode(
            ['success' => true,
            'message' => 'El documento fue cargado exitosamente']
        );
        exit;

    } catch (mysqli_sql_exception $e) {

        $mysqli->rollback();
        $mysqli->close();

        echo json_encode(
            ['success' => false,
            'message' => $e->getMessage()]
        );
        exit;
    }

?>