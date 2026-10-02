<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../conection.php";
require_once __DIR__ . "/../../models/employee_model.php";

try {
    $state = (int) ($_GET["id_state"] ?? 0);
    $phrase = trim($_GET["phrase"] ?? "");

if ($phrase === "null") {
    $phrase = "";
}

    $mysqli = connection_db();
    $employees = findAllEmployees($mysqli);

    $filtered = [];

    foreach ($employees as $employee) {
        // filtro para no mostrar los funcionarios con cargo "SU" (Super Usuarios),no deberían aparecer en la lista de funcionarios.
        if (($employee["cargo"] ?? "") === "SU") {
            continue;
        }

        $employee_state = (int) ($employee["id_estado_funcionario"] ?? 0);

        /* filtros de estados de funcionarios: 
         * 0 = todos
         * 1 = activos
         * 2 = inactivos
         * 3 = jubilados
         */
        if ($state === 1 && $employee_state !== 1) {
            continue;
        }

        if ($state === 2 && $employee_state !== 6) {
            continue;
        }

        if ($state === 3 && $employee_state !== 7) {
            continue;
        }

        $employee_code = ($employee["cargo"] ?? "") . str_pad(
            (string) ($employee["id_funcionario"] ?? ""),
            8,
            "0",
            STR_PAD_LEFT
        );

        if ($phrase !== "") {
            $search_text = implode(" ", [
                $employee["nombre"] ?? "",
                $employee["apellido"] ?? "",
                $employee["cedula"] ?? "",
                $employee["email"] ?? "",
                $employee_code
            ]);

            if (stripos($search_text, $phrase) === false) {
                continue;
            }
        }

        $filtered[] = $employee;
    }

    $mysqli->close();

    echo json_encode([
        "success" => true,
        "item" => $filtered
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "No se pudieron obtener los funcionarios."
    ], JSON_UNESCAPED_UNICODE);
}
