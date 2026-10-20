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

    // Igual que findElementWithId pero agrega los nombres del tipo y del subtipo
    // (tipo y subtipo en la tabla elemento son ids). Sirve para mostrar el elemento.
    function findElementDetailWithId($mysqli, $element_id) {
        $stmt = $mysqli->prepare("SELECT e.*, t.nombre AS nombre_tipo, s.nombre AS nombre_subtipo
            FROM elemento e
            LEFT JOIN tipo_elemento t ON t.id_tipo = e.tipo
            LEFT JOIN subtipo_elemeto s ON s.id_subtipo = e.subtipo
            WHERE e.id_elemento = ?");
        $stmt->bind_param("i", $element_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $element = $result->fetch_assoc();
        $stmt->close();

        return $element;
    }

    function updateElement($mysqli, $new_code, $name, $type, $subtype, $description, $element_id) {

        $stmt = $mysqli->prepare("UPDATE elemento SET codigo = ?, nombre = ?, tipo = ?,
            subtipo = ?, descripcion = ? WHERE id_elemento = ?");
        $stmt->bind_param("ssiisi", $new_code, $name, $type, $subtype, $description, $element_id);
        $stmt->execute();
        $stmt->close();
    }

    function insertElement($mysqli, $code, $name, $type, $subtype, $description) {
        
        $stmt = $mysqli->prepare("INSERT INTO elemento(codigo, nombre, tipo, subtipo, descripcion) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiis", $code, $name, $type, $subtype, $description);
        $stmt->execute();
        $stmt->close();
    }

    function changeStateElement($mysqli, $element_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE elemento SET id_estado_elemento = ? WHERE id_elemento = ?");
        $stmt->bind_param("ii", $state_id, $element_id);
        $stmt->execute();
        $stmt->close();
    }

    // Subtipos: los nombres SQL coinciden con byp.sql.
    function findSubtypes($mysqli, $phrase = '', $filter = 'all') {
        $sql = "SELECT id_subtipo_elemento AS id_subtipo, nombre, tipo,
                CASE WHEN tipo = 1 THEN 'Biológico' WHEN tipo = 2 THEN 'No biológico' END AS nombre_tipo
                FROM subtipo_elemento";
        $conditions = []; $values = []; $types = '';
        if ($phrase !== '') { $conditions[] = 'nombre LIKE ?'; $values[] = '%' . $phrase . '%'; $types .= 's'; }
        if ($filter === 'bio' || $filter === 'nonbio') { $conditions[] = 'tipo = ?'; $values[] = $filter === 'bio' ? 1 : 2; $types .= 'i'; }
        if ($conditions) $sql .= ' WHERE ' . implode(' AND ', $conditions);
        $sql .= ' ORDER BY nombre';
        $stmt = $mysqli->prepare($sql);
        if ($values) $stmt->bind_param($types, ...$values);
        $stmt->execute(); $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }
    function findAllSubtypes($mysqli) {
        return $mysqli->query('SELECT id_subtipo_elemento AS id_subtipo, nombre, tipo FROM subtipo_elemento')->fetch_all(MYSQLI_ASSOC);
    }
    function findSubtypeWithId($mysqli, $subtype_id) {
        $stmt = $mysqli->prepare('SELECT id_subtipo_elemento AS id_subtipo, nombre, tipo FROM subtipo_elemento WHERE id_subtipo_elemento = ?');
        $stmt->bind_param('i', $subtype_id); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return $row;
    }
    function findSubtypeWithName($mysqli, $name, $type) {
        $stmt = $mysqli->prepare('SELECT id_subtipo_elemento AS id_subtipo, nombre, tipo FROM subtipo_elemento WHERE nombre = ? AND tipo = ?');
        $stmt->bind_param('si', $name, $type); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return $row;
    }
    function insertSubtype($mysqli, $name, $type) {
        $stmt = $mysqli->prepare('INSERT INTO subtipo_elemento(nombre, tipo) VALUES(?, ?)');
        $stmt->bind_param('si', $name, $type); $stmt->execute(); $stmt->close();
    }
    function updateSubtypeName($mysqli, $name, $subtype_id) {
        $stmt = $mysqli->prepare('UPDATE subtipo_elemento SET nombre = ? WHERE id_subtipo_elemento = ?');
        $stmt->bind_param('si', $name, $subtype_id); $stmt->execute(); $stmt->close();
    }
    function deleteSubtype($mysqli, $subtype_id) {
        $stmt = $mysqli->prepare('DELETE FROM subtipo_elemento WHERE id_subtipo_elemento = ?');
        $stmt->bind_param('i', $subtype_id); $stmt->execute(); $stmt->close();
    }
    function countElementsWithSubtype($mysqli, $subtype_id) {
        $stmt = $mysqli->prepare('SELECT COUNT(*) AS total FROM elemento WHERE id_subtipo_elemento = ?');
        $stmt->bind_param('i', $subtype_id); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return (int)$row['total'];
    }
?>
