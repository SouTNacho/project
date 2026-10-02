<?php

session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../models/employee_model.php";
require_once __DIR__ . "/../../conection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stateId = (int) ($_POST["state_id"] ?? 0);
$employeeId = (int) ($_POST["employee_id"] ?? 0);

if ($stateId <= 0 || $employeeId <= 0) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Datos incompletos para cambiar el estado."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $mysqli = connection_db();

    $employee = findEmployeeWithId($mysqli, $employeeId);

    if (!$employee || ($employee["cargo"] ?? "") === "SU") {
        $mysqli->close();
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "El funcionario no fue encontrado."
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stateStmt = $mysqli->prepare(
        "SELECT id_estado_funcionario FROM estado_funcionario WHERE id_estado_funcionario = ?"
    );
    $stateStmt->bind_param("i", $stateId);
    $stateStmt->execute();
    $stateResult = $stateStmt->get_result();
    $stateExists = $stateResult->num_rows > 0;
    $stateStmt->close();

    if (!$stateExists) {
        $mysqli->close();
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "El estado seleccionado no existe."
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    changeStateEmployee($mysqli, $employeeId, $stateId);

    $updatedEmployee = findEmployeeWithId($mysqli, $employeeId);
    $mysqli->close();

    if (!$updatedEmployee || (int) $updatedEmployee["id_estado_funcionario"] !== $stateId) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "No se pudo actualizar el estado del funcionario."
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        "success" => true,
        "message" => "Estado del funcionario actualizado correctamente."
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error al cambiar el estado del funcionario."
    ], JSON_UNESCAPED_UNICODE);
}
