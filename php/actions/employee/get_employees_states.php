<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../conection.php";

try {
    $mysqli = connection_db();

    $stmt = $mysqli->prepare("SELECT id_estado_funcionario, nombre FROM estado_funcionario ORDER BY id_estado_funcionario");
    $stmt->execute();

    $result = $stmt->get_result();
    $states = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $mysqli->close();

    echo json_encode([
        "success" => true,
        "item" => $states
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "No se pudieron obtener los estados de los funcionarios."
    ], JSON_UNESCAPED_UNICODE);
}
