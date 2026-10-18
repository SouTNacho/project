<?php

function getEmployeeType($employee_code)
{
    return strtoupper(substr(trim($employee_code), 0, 2));
}

function findEmployeeWithCode($user_type, $mysqli, $employee_code)
{
    switch ($user_type) {

        case "FA":
            $stmt = $mysqli->prepare(
                "SELECT
                    f.id_funcionario,
                    f.nombre,
                    f.apellido,
                    f.id_estado_funcionario,
                    a.pass,
                    a.codigo,
                    'FA' AS cargo
                 FROM funcionario f
                 INNER JOIN administrativo a
                    ON a.id_funcionario = f.id_funcionario
                 WHERE a.codigo = ?"
            );
            break;

        case "DR":
            $stmt = $mysqli->prepare(
                "SELECT
                    f.id_funcionario,
                    f.nombre,
                    f.apellido,
                    f.id_estado_funcionario,
                    c.pass,
                    c.codigo,
                    'DR' AS cargo
                 FROM funcionario f
                 INNER JOIN conductor c
                    ON c.id_funcionario = f.id_funcionario
                 WHERE c.codigo = ?"
            );
            break;

        case "CO":
            $stmt = $mysqli->prepare(
                "SELECT
                    f.id_funcionario,
                    f.nombre,
                    f.apellido,
                    f.id_estado_funcionario,
                    c.pass,
                    c.codigo,
                    'CO' AS cargo
                 FROM funcionario f
                 INNER JOIN copiloto c
                    ON c.id_funcionario = f.id_funcionario
                 WHERE c.codigo = ?"
            );
            break;

        case "SU":
            $stmt = $mysqli->prepare(
                "SELECT
                    id_super_usuario AS id_funcionario,
                    nombre,
                    '' AS apellido,
                    id_estado_super_usuario AS id_estado_funcionario,
                    pass,
                    codigo,
                    'SU' AS cargo
                 FROM super_usuario
                 WHERE codigo = ?"
            );
            break;

        default:
            return null;
    }

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("s", $employee_code);
    $stmt->execute();

    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    $stmt->close();

    return $employee ?: null;
}

?>
