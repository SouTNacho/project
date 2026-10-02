<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../../models/employee_model.php";
require_once __DIR__ . "/../../conection.php";

try {

    $phrase = trim($_GET["phrase"] ?? "");

    $mysqli = connection_db();

    $employees = findAllEmployees($mysqli);

    $result = [];

    foreach ($employees as $employee) {

        // no mostrar superusuarios
        if ($employee["cargo"] === "SU") {
            continue;
        }

        $employee_code = $employee["cargo"] . str_pad(
            $employee["id_funcionario"],
            8,
            "0",
            STR_PAD_LEFT
        );

        if ($phrase !== "") {

            $search_text =
                $employee["nombre"] . " " .
                $employee["apellido"] . " " .
                $employee["cedula"] . " " .
                $employee["email"] . " " .
                $employee_code;

            if (stripos($search_text, $phrase) === false) {
                continue;
            }
        }

        $result[] = $employee;
    }

    $mysqli->close();

    echo json_encode([
        "success" => true,
        "item" => $result
    ]);

} catch (mysqli_sql_exception $e) {

    echo json_encode([
        "success" => false,
        "message" => "Error al obtener los funcionarios."
    ]);
}