<?php

    function findAllDocuments($mysqli) {

        $stmt = $mysqli->prepare("SELECT documento.*, qr.token FROM documento INNER JOIN qr
                                    ON documento.id_documento = qr.id_documento");
        $stmt->execute();
        $result = $stmt->get_result();
        $documents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $documents;
    }

    function insertDocument($mysqli, $name, $file, $category_id, $service_id) {

        $stmt = $mysqli->prepare("INSERT INTO documento(nombre, archivo,  id_categoria, id_servicio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $name, $file, $category_id, $service_id);
        $stmt->execute();
        $id_document = $mysqli->insert_id;
        $stmt->close();

        return $id_document;
    }

    function findCategories($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM categoria");
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $categories;
    }

    function findDocumentToken($mysqli, $token) {

        $stmt = $mysqli->prepare("SELECT * FROM qr WHERE qr.token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $token = $result->fetch_assoc();
        $stmt->close();

        return $token;
    }

    function insertQr($mysqli, $token, $id_document) {

        $stmt = $mysqli->prepare("INSERT INTO qr(token, id_documento) VALUES (?, ?)");
        $stmt->bind_param("si", $token, $id_document);
        $stmt->execute();
        $stmt->close();
    }

    function findDocumentWithToken($mysqli, $document_token) {
        
        $stmt = $mysqli->prepare("SELECT documento.* FROM documento INNER JOIN qr
                                    ON documento.id_documento = qr.id_documento
                                    WHERE token = ?");
        $stmt->bind_param('s', $document_token);
        $stmt->execute();
        $result = $stmt->get_result();
        $document = $result->fetch_assoc();
        $stmt->close();

        return $document;
    }

    function updateDocumentFile($mysqli, $file, $document_id) {

        $stmt = $mysqli->prepare("UPDATE documento SET archivo = ? WHERE id_documento = ?");
        $stmt->bind_param("si", $file, $document_id);
        $stmt->execute();
        $stmt->close();
    }

    function documentAction($mysqli, $id_action, $administrative_code, $id_documento) {

        $stmt = $mysqli->prepare("INSERT INTO administra_documento(id_accion, codigo_administrativo, id_documento)
                                    VALUES(?, ?, ?)");
        $stmt->bind_param("isi", $id_action, $administrative_code, $id_documento);
        $stmt->execute();
        $stmt->close();
    }

    function findDocumentWithId($mysqli, $document_id) {

        $stmt = $mysqli->prepare("SELECT * FROM documento WHERE id_documento = ?");
        $stmt->bind_param("i", $document_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $document = $result->fetch_assoc();
        $stmt->close();

        return $document;
    }

    function changeStateDocument($mysqli, $document_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE documento SET id_estado_documento = ? WHERE id_documento = ?");
        $stmt->bind_param("ii", $state_id, $document_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllDocumentsStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_documento");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function findActiveDocuments($mysqli) {
        $stmt = $mysqli->prepare("SELECT documento.*, qr.token FROM documento INNER JOIN qr
                                ON documento.id_documento = qr.id_documento WHERE documento.id_estado_documento = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $documents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $documents;
    }

    function findInactiveDocuments($mysqli) {
        $stmt = $mysqli->prepare("SELECT documento.*, qr.token FROM documento INNER JOIN qr
                                ON documento.id_documento = qr.id_documento WHERE documento.id_estado_documento = 2");
        $stmt->execute();
        $result = $stmt->get_result();
        $documents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $documents;
    }

    function findDeletedDocuments($mysqli) {
        $stmt = $mysqli->prepare("SELECT documento.*, qr.token FROM documento INNER JOIN qr
                                ON documento.id_documento = qr.id_documento WHERE documento.id_estado_documento = 3");
        $stmt->execute();
        $result = $stmt->get_result();
        $documents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $documents;
    }

    function findAllDocumentsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT documento.*, qr.token FROM documento INNER JOIN qr
                                ON documento.id_documento = qr.id_documento WHERE documento.nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $documents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $documents;
    }

    function findActiveDocumentsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT documento.*, qr.token FROM documento INNER JOIN qr
            ON documento.id_documento = qr.id_documento WHERE documento.id_estado_documento = 1 AND documento.nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $documents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $documents;
    }

    function findInactiveDocumentsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT documento.*, qr.token FROM documento INNER JOIN qr
            ON documento.id_documento = qr.id_documento WHERE documento.id_estado_documento = 2 AND documento.nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $documents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $documents;
    }

    function findDeletedDocumentsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT documento.*, qr.token FROM documento INNER JOIN qr
            ON documento.id_documento = qr.id_documento WHERE documento.id_estado_documento = 3 AND documento.nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $documents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $documents;
    }

    function updateDocument($mysqli, $name, $category_id, $document_id) {

        $stmt = $mysqli->prepare("UPDATE documento SET nombre = ?, id_categoria = ? WHERE id_documento = ?");
        $stmt->bind_param("sii", $name, $category_id, $document_id);
        $stmt->execute();
        $stmt->close();
    }
    
?>