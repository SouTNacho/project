<?php
require_once __DIR__ . '/element_model.php';

function findAllSubtypesWithPhrase($mysqli, $phrase) {
    $stmt = $mysqli->prepare('SELECT *, id_subtipo_elemento AS id_subtipo FROM subtipo_elemento WHERE nombre LIKE ? ORDER BY nombre');
    $stmt->bind_param('s', $phrase);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}
function findAllSubtypesStates($mysqli) {
    return $mysqli->query('SELECT * FROM estado_subtipo_elemento')->fetch_all(MYSQLI_ASSOC);
}
function findSubtypeWithNameAndType($mysqli, $name, $type) {
    return findSubtypeWithName($mysqli, $name, $type);
}
function findActiveSubtypesWithType($mysqli, $type) {
    $stmt = $mysqli->prepare('SELECT *, id_subtipo_elemento AS id_subtipo FROM subtipo_elemento WHERE tipo = ? AND id_estado_subtipo_elemento = 1 ORDER BY nombre');
    $stmt->bind_param('i', $type);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}
function updateSubtype($mysqli, $name, $type, $id) {
    $stmt = $mysqli->prepare('UPDATE subtipo_elemento SET nombre = ?, tipo = ? WHERE id_subtipo_elemento = ?');
    $stmt->bind_param('sii', $name, $type, $id);
    $stmt->execute();
    $stmt->close();
}
function changeStateSubtype($mysqli, $id, $state) {
    $stmt = $mysqli->prepare('UPDATE subtipo_elemento SET id_estado_subtipo_elemento = ? WHERE id_subtipo_elemento = ?');
    $stmt->bind_param('ii', $state, $id);
    $stmt->execute();
    $stmt->close();
}
