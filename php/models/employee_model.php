<?php

    function findEmployeeWithDocument($mysqli, $document) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE cedula = ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        $stmt->close();

        return $employee;
    }

    function findEmployeeWithId($mysqli, $employee_id) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE id_funcionario = ?");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        $stmt->close();

        return $employee;
    }

    function findAllEmployees($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario");
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    function findActiveEmployees($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE id_estado_funcionario = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    function findInactiveEmployees($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE id_estado_funcionario = 2");
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    function findDeletedEmployees($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE id_estado_funcionario = 3");
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    function findAllEmployeesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE cedula LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    function findActiveEmployeesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE id_estado_funcionario = 1 AND cedula LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    function findInactiveEmployeesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE id_estado_funcionario = 2 AND cedula LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    function findDeletedEmployeesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE id_estado_funcionario = 3 AND cedula LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    function findEmployeeWithEmail($mysqli, $email) {

        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        $stmt->close();

        return $employee;
    }

    function insertEmployee($mysqli, $first_name, $last_name, $document, $nationality, $birthdate, $department,
                $locality, $address, $door_number, $email, $entry_date) {

        $stmt = $mysqli->prepare("INSERT INTO funcionario
                                    (nombre, apellido, cedula, nacionalidad, fecha_nacimiento, departamento,
                                    localidad, direccion, numero_puerta, email, fecha_ingreso)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssss",
            $first_name,
            $last_name,
            $document,
            $nationality,
            $birthdate,
            $department,
            $locality,
            $address,
            $door_number,
            $email,
            $entry_date
        );

        $stmt->execute();
        $stmt->close();
    }

    function updateEmployee($mysqli, $first_name, $last_name, $document, $nationality, $birthdate, $department,
                $locality, $address, $door_number, $email, $entry_date, $employee_id) {

        $stmt = $mysqli->prepare("UPDATE funcionario
                                    SET nombre = ?, apellido = ?, cedula = ?, nacionalidad = ?,
                                        fecha_nacimiento = ?, departamento = ?, localidad = ?,
                                        direccion = ?, numero_puerta = ?, email = ?, fecha_ingreso = ?
                                    WHERE id_funcionario = ?");

        $stmt->bind_param("sssssssssssi",
            $first_name,
            $last_name,
            $document,
            $nationality,
            $birthdate,
            $department,
            $locality,
            $address,
            $door_number,
            $email,
            $entry_date,
            $employee_id
        );

        $stmt->execute();
        $stmt->close();
    }

    function findAllEmployeesStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_funcionario");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function changeStateEmployee($mysqli, $employee_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE funcionario
                                    SET id_estado_funcionario = ?
                                    WHERE id_funcionario = ?");

        $stmt->bind_param("ii", $state_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    }

    function findEmployeeWithCode($user_type, $mysqli, $employee_code) {

        switch ($user_type) {
            case "FA":

                $stmt = $mysqli->prepare("SELECT f.id_funcionario, f.nombre, f.apellido, f.id_estado_funcionario, a.pass, a.codigo
                    FROM funcionario f INNER JOIN administrativo a ON a.id_funcionario = f.id_funcionario WHERE a.codigo = ?");
                break;
            case "DR":

                $stmt = $mysqli->prepare("SELECT f.id_funcionario, f.nombre, f.apellido, f.id_estado_funcionario, c.pass, c.codigo
                    FROM funcionario f INNER JOIN conductor c ON c.id_funcionario = f.id_funcionario WHERE c.codigo = ?");
                break;
            case "SU":

                $stmt = $mysqli->prepare("SELECT id_super_usuario AS id_funcionario, nombre, id_estado_super_usuario AS id_estado_funcionario, pass, codigo
                    FROM super_usuario WHERE codigo = ?");
                break;

            default:
                return null;
        }

        if (!$stmt) { return null; }
        $stmt->bind_param("s", $employee_code);
        $stmt->execute();

        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        $stmt->close();

        return $employee;
    }

?>