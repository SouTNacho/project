<?php
require_once __DIR__ . '/sample_model.php';

function findAllTypes($mysqli) {
    return $mysqli->query('SELECT *, id_tipo_muestra AS id_tipo FROM tipo_muestra ORDER BY nombre')->fetch_all(MYSQLI_ASSOC);
}
function findAllTypesWithPhrase($mysqli, $phrase) {
    $stmt = $mysqli->prepare('SELECT *, id_tipo_muestra AS id_tipo FROM tipo_muestra WHERE nombre LIKE ? ORDER BY nombre');
    $stmt->bind_param('s', $phrase);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}
function findActiveTypes($mysqli) {
    return $mysqli->query('SELECT *, id_tipo_muestra AS id_tipo FROM tipo_muestra WHERE id_estado_tipo_muestra = 1 ORDER BY nombre')->fetch_all(MYSQLI_ASSOC);
}
function findAllTypesStates($mysqli) {
    return $mysqli->query('SELECT * FROM estado_tipo_muestra')->fetch_all(MYSQLI_ASSOC);
}
function updateType($mysqli, $name, $id) {
    updateTypeName($mysqli, $name, $id);
}
function changeStateType($mysqli, $id, $state) {
    $stmt = $mysqli->prepare('UPDATE tipo_muestra SET id_estado_tipo_muestra = ? WHERE id_tipo_muestra = ?');
    $stmt->bind_param('ii', $state, $id);
    $stmt->execute();
    $stmt->close();
}
