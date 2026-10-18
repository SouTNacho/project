<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");
    
    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../models/category_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";
    
    $id_administrativo = "FA00000001";
    $document_id = (int) ($_GET['id'] ?? 0);
    $document_name = trim($_POST['name'] ?? '');
    $file_document = $_FILES['file_document'] ?? null;
    $document_category = (int) ($_POST['category_id'] ?? 0);

    if ($document_id <= 0) {

        echo json_encode([
            'success' => false,
            'message' => 'Documento no válido.'
        ]);

        exit;
    }

    if ($file_document) {

        if ($file_document['error'] !== UPLOAD_ERR_OK) {

            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar el archivo.'
            ]);

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
    }

    if (!validateEmptyData($document_name)) {

        if (!validateString($document_name)) {
        echo json_encode(
            ['success' => false,
            'message' => 'El nombre ingresado no es válido.']
        );
        exit;
        }
    }

    if (!validateEmptyData($document_category)) {
        
        if ($document_category <= 0) {

            echo json_encode([
                'success' => false,
                'message' => 'La catogoría ingresada no es válida.'
            ]);

            exit;
        }
    }

    $mysqli = connection_db();

    $document = findDocumentWithId($mysqli, $document_id);

    if (!$document) {

        $mysqli->close();

        echo json_encode([
            'success' => false,
            'message' => 'El documento no existe.'
        ]);

        exit;
    }

    if ((int) $document['id_estado_documento'] === 3) {
        $mysqli->close();

        echo json_encode([
            'success' => false,
            'message' => 'No se puede modificar un registro eliminado.'
        ]);

        exit;
    }

    try {

        $mysqli->begin_transaction();

        $document_name = $document_name === '' ? $document['nombre'] : $document_name;
        $category_id = $document_category === 0 ? (int) $document['id_categoria'] : $document_category;

        if ($category_id !== (int) $document['id_categoria'] && !isCategoryActive($mysqli, $category_id)) {
            $mysqli->rollback();
            $mysqli->close();
            echo json_encode([
                'success' => false,
                'message' => 'La categoría seleccionada no existe o está inactiva.'
            ]);
            exit;
        }

        updateDocument($mysqli, $document_name, $category_id, $document_id);

        $file_name = "archivo" . $document_id . ".pdf";
        $ruta = __DIR__ . "/../../../uploads/documents/" . $file_name;

        if ($file_document) {

            if (!file_exists($ruta)) {
                throw new RuntimeException("El archivo actual no existe.");
            }

            if (!move_uploaded_file($file_document['tmp_name'], $ruta)) {
                throw new RuntimeException("Error al cargar el documento.");
            }
        }

        documentAction($mysqli, 4, $id_administrativo, $document_id);
        $mysqli->commit();
        $mysqli->close();

        echo json_encode([
            'success' => true,
            'message' => 'La actualización fue exitosa.'
        ]);
        exit;

    } catch (mysqli_sql_exception | RuntimeException $e) {

        $mysqli->rollback();
        $mysqli->close();

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }

?>