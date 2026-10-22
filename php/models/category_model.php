<?php

    function findCategoryWithName($mysqli, $name) {

        $stmt = $mysqli->prepare("SELECT * FROM categoria WHERE nombre = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_assoc();
        $stmt->close();

        return $categories;
    }

    function findCategoryWithId($mysqli, $category_id) {

        $stmt = $mysqli->prepare("SELECT * FROM categoria WHERE id_categoria = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_assoc();
        $stmt->close();

        return $categories;
    }

    function findAllCategories($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM categoria");
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $categories;
    }

    function findActiveCategories($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM categoria WHERE id_estado_categoria = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $categories;
    }

    function insertCategory($mysqli, $name) {

        $stmt = $mysqli->prepare("INSERT INTO categoria(nombre) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    function updateCategory($mysqli, $name, $category_id) {

        $stmt = $mysqli->prepare("UPDATE categoria SET nombre = ? WHERE id_categoria = ?");
        $stmt->bind_param("si", $name, $category_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllCategorieStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_categoria");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function changeStateCategory($mysqli, $category_id, $state_id) {
        
        $stmt = $mysqli->prepare("UPDATE categoria SET id_estado_categoria = ? WHERE id_categoria = ?");
        $stmt->bind_param("ii", $state_id, $category_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllCategoriesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM categoria WHERE nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $categories;
    }

?>