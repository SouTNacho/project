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

    function findLastDocument($mysqli) {

        $stmt = $mysqli->prepare("SELECT id_documento, nombre FROM documento ORDER BY id_documento DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $document = $result->fetch_assoc();
        $stmt->close();

        return $document;
    }

    function insertDocument($mysqli, $name, $file, $categoryId, $service_id) {

        $stmt = $mysqli->prepare("INSERT INTO documento(nombre, archivo,  id_categoria, id_servicio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $name, $file, $categoryId, $service_id);
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

        $stmt = $mysqli->prepare("SELECT * FROM qr WHERE token = ?");
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

    function documentChangeState($mysqli, $state_id, $document_id) {

        $stmt = $mysqli->prepare("UPDATE documento SET id_estado_documento = ? WHERE id_documento = ?");
        $stmt->bind_param("ii", $state_id, $document_id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteDocument($mysqli, $file,  $document_id) {

        $stmt = $mysqli->prepare("UPDATE documento SET archivo = ? WHERE id_documento = ?");
        $stmt->bind_param("si", $file, $document_id);
        $stmt->execute();
        $stmt->close();
    }

    function documentAction($mysqli, $id_action, $id_administrativo, $id_documento) {

        $stmt = $mysqli->prepare("INSERT INTO administra_documento(id_accion, id_administrativo, id_documento)
                                    VALUES(?, ?, ?)");
        $stmt->bind_param("isi", $id_action, $id_administrativo, $id_documento);
        $stmt->execute();
        $stmt->close();
    }

?>