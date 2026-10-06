<?php

    function findAllCategories($mysqli) {

        $stmt = $mysqli->prepare("SELECT id_categoria, nombre FROM categoria ORDER BY nombre");
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $categories;
    }

    function findCategoryById($mysqli, $category_id) {

        $stmt = $mysqli->prepare("SELECT id_categoria, nombre FROM categoria WHERE id_categoria = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();
        $stmt->close();

        return $category;
    }

    function insertCategory($mysqli, $name) {

        $stmt = $mysqli->prepare("INSERT INTO categoria(nombre) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    function updateCategory($mysqli, $category_id, $name) {

        $stmt = $mysqli->prepare("UPDATE categoria SET nombre = ? WHERE id_categoria = ?");
        $stmt->bind_param("si", $name, $category_id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteCategory($mysqli, $category_id) {

        $stmt = $mysqli->prepare("DELETE FROM categoria WHERE id_categoria = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $deleted = $stmt->affected_rows > 0;
        $stmt->close();

        return $deleted;
    }

?>