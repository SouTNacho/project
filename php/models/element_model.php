<?php

    function findElementWithCode($mysqli, $code) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE codigo = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $element = $result->fetch_assoc();
        $stmt->close();

        return $element;
    }

    function findAllElements($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento");
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }
    
    function findActiveElements($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE id_estado_elemento = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findInactiveElements($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE id_estado_elemento = 2");
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findDeletedElements($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE id_estado_elemento = 3");
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findAllElementsStates($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM estado_elemento");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function findAllElementsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findActiveElementsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE id_estado_elemento = 1 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findInactiveElementsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE id_estado_elemento = 2 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findDeletedElementsWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE id_estado_elemento = 3 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findElementWithId($mysqli, $element_id) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE id_elemento = ?");
        $stmt->bind_param("i", $element_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $element = $result->fetch_assoc();
        $stmt->close();

        return $element;
    }

    function findElementWithName($mysqli, $name) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE nombre = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $element = $result->fetch_assoc();
        $stmt->close();

        return $element;
    }

    function updateElement($mysqli, $new_code, $name, $type, $subtype, $description, $element_id) {

        $stmt = $mysqli->prepare("UPDATE elemento SET codigo = ?, nombre = ?, tipo = ?,
            subtipo = ?, descripcion = ? WHERE id_elemento = ?");
        $stmt->bind_param("sssssi", $new_code, $name, $type, $subtype, $description, $element_id);
        $stmt->execute();
        $stmt->close();
    }

    function insertElement($mysqli, $code, $name, $type, $subtype, $description) {
        
        $stmt = $mysqli->prepare("INSERT INTO elemento(codigo, nombre, tipo, subtipo, descripcion) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $code, $name, $type, $subtype, $description);
        $stmt->execute();
        $stmt->close();
    }

    function changeStateElement($mysqli, $element_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE elemento SET id_estado_elemento = ? WHERE id_elemento = ?");
        $stmt->bind_param("ii", $state_id, $element_id);
        $stmt->execute();
        $stmt->close();
    }

    //SUBTIPOS DE ACA PARA ABAJO

        function findSubtypes($mysqli, $phrase = '', $filter = 'all') {

    $sql = "SELECT id_subtipo, nombre, tipo
            FROM subtipo_elemeto";

    $conditions = [];
    $search = '%' . $phrase . '%';
    $type = null;

    if ($phrase !== '') {
        $conditions[] = "nombre LIKE ?";
    }

    if ($filter === 'bio') {
        $type = 1;
        $conditions[] = "tipo = ?";
    }

    if ($filter === 'nonbio') {
        $type = 2;
        $conditions[] = "tipo = ?";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY nombre";

    $stmt = $mysqli->prepare($sql);

    if ($phrase !== '' && $type !== null) {
        $stmt->bind_param("si", $search, $type);
    } elseif ($phrase !== '') {
        $stmt->bind_param("s", $search);
    } elseif ($type !== null) {
        $stmt->bind_param("i", $type);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $subtypes = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $subtypes;
}

    function findSubtypeWithName($mysqli, $subtype) {
        $stmt = $mysqli-> prepare("SELECT nombre FROM subtipo_elemeto");
        $stmt->bind_param("s", $subtype);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }

    function findAllSubtypes($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemeto");
        $stmt->execute();
        $result = $stmt->get_result();
        $subtype = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $subtype;
    }

    function findBioSubetypes($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE tipo= 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findNonBioSubtypes($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM elemento WHERE tipo=2");
        $stmt->execute();
        $result = $stmt->get_result();
        $elements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $elements;
    }

    function findBioSubtypesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemeto WHERE tipo = 1 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtypes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $subtypes;
    }


    function findNonBioSubtypesWithPhrase($mysqli, $phrase) {
        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemeto WHERE tipo = 2 AND codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtypes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $subtypes;
    }

    function findSubtypeWithId($mysqli, $subtype_id) {
        $stmt = $mysqli->prepare("SELECT * FROM subtipo_elemeto WHERE id_subtipo = ?");
        $stmt->bind_param("i", $subtype_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtype = $result->fetch_assoc();
        $stmt->close();

        return $subtype;
    }

    //update

    function updateSubtypeName($mysqli, $name, $subtype_id) {

        $stmt = $mysqli->prepare("UPDATE subtipo_elemeto SET nombre = ? WHERE id_subtipo = ?");
        $stmt->bind_param("ss", $name, $subtype_id);
        $stmt->execute();
        $stmt->close();
    }


    //register
    function insertSubtype($mysqli, $name, $type) {
        
        $stmt = $mysqli->prepare("INSERT INTO subtipo_elemeto(nombre, tipo) VALUES(?, ?)");
        $stmt->bind_param("ss", $name, $type);
        $stmt->execute();
        $stmt->close();
    }
?>