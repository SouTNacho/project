<?php
session_start();

// Falta validar rol

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../models/element_model.php";
require_once __DIR__ . "/../../conection.php";



$search = $_GET['search'] ?? '';
$search = is_string($search) ? trim($search) : '';

$filter = $_GET['filter'] ?? 'all';

if (!is_string($filter) || !in_array($filter, ['all', 'bio', 'nonbio'], true)) {
    $filter = 'all';
}


$mysqli = connection_db();

try {

    $subtypes = findSubtypes($mysqli, $search, $filter);

    echo json_encode(
        [
            'success' => true,
            'message' => 'Solicitud exitosa.',
            'data'    => $subtypes
        ],
        JSON_UNESCAPED_UNICODE
    );

} catch (Throwable $e) {

    // El detalle va al log de Apache (xampp/apache/logs/error.log), no al usuario.
    error_log('get_element_subtype.php: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode(
        [
            'success' => false,
            'message' => 'Ha ocurrido un error.'
        ],
        JSON_UNESCAPED_UNICODE
    );

} finally {

    $mysqli->close();

}

