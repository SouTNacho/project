<?php

    function findAllCategories($mysqli) {

        $stmt = $mysqli->prepare("SELECT c.id_categoria, c.nombre, c.id_estado_categoria, e.nombre AS estado
                                FROM categoria c
                                INNER JOIN estado_categoria e
                                    ON c.id_estado_categoria = e.id_estado_categoria
                                ORDER BY c.nombre");
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $categories;
    }

    function findCategoryById($mysqli, $category_id) {

        $stmt = $mysqli->prepare("SELECT id_categoria, nombre, id_estado_categoria
                                FROM categoria WHERE id_categoria = ?");
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

    function isCategoryActive($mysqli, $category_id) {

        $stmt = $mysqli->prepare("SELECT id_categoria FROM categoria
                                WHERE id_categoria = ? AND id_estado_categoria = 1");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $is_active = $result->num_rows > 0;
        $stmt->close();

        return $is_active;
    }

    function changeCategoryState($mysqli, $category_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE categoria SET id_estado_categoria = ? WHERE id_categoria = ?");
        $stmt->bind_param("ii", $state_id, $category_id);
        $stmt->execute();
        $stmt->close();
    }

?>