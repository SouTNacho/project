<?php

session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../models/employee_model.php";
require_once __DIR__ . "/../../functions/validations.php";
require_once __DIR__ . "/../../conection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$employeeId = (int) ($_POST["employee_id"] ?? 0);
$password = $_POST["password"] ?? "";
$confirmPassword = $_POST["confirm_password"] ?? "";

if ($employeeId <= 0 || $password === "" || $confirmPassword === "") {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Todos los campos son obligatorios."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!validatePassword($password)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "La contraseña ingresada no es válida."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($password !== $confirmPassword) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Las contraseñas no coinciden."
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

    $hash = password_hash($password, PASSWORD_BCRYPT);
    changePasswordEmployee($mysqli, $employeeId, $hash);
    $mysqli->close();

    echo json_encode([
        "success" => true,
        "message" => "Contraseña cambiada correctamente."
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error al cambiar la contraseña del funcionario."
    ], JSON_UNESCAPED_UNICODE);
}
