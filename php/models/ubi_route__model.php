<?php

    function findEmployeeWithCode($employee_type, $mysqli, $employee_id) {

        switch($employee_type) {

            case "FA":

                $stmt = $mysqli->prepare("SELECT funcionario.* FROM administrativo INNER JOIN funcionario 
                                        ON administrativo.id_funcionario = funcionario.id_funcionario
                                        WHERE administrativo.id_administrativo = ?");
                $stmt->bind_param("s", $employee_id);
                $stmt->execute();
                $result = $stmt->get_result();
                break;
            case "CO":

                $stmt = $mysqli->prepare("SELECT funcionario.* FROM copiloto INNER JOIN funcionario 
                                        ON copiloto.id_funcionario = funcionario.id_funcionario
                                        WHERE copiloto.id_copiloto = ?");
                $stmt->bind_param("s", $employee_id);
                $stmt->execute();
                $result = $stmt->get_result();          
                break;
            case "DR":

                $stmt = $mysqli->prepare("SELECT funcionario.* FROM conductor INNER JOIN funcionario 
                                        ON conductor.id_funcionario = funcionario.id_funcionario
                                        WHERE conductor.id_conductor = ?");
                $stmt->bind_param("s", $employee_id);
                $stmt->execute();
                $result = $stmt->get_result();
                break;
            case "SU":

                $stmt = $mysqli->prepare("SELECT funcionario.* FROM super_usuario INNER JOIN funcionario 
                                        ON super_usuario.id_funcionario = funcionario.id_funcionario
                                        WHERE super_usuario.id_super_usuario = ?");
                $stmt->bind_param("s", $employee_id);
                $stmt->execute();
                $result = $stmt->get_result();
                break;
            default:

                return false;
        }

        $funcionary = $result->fetch_assoc();
        $stmt->close();

        return $funcionary;
    }

    function findEmployeeWithDocument($mysqli, $document) {
        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE cedula = ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $funcionary = $result->fetch_assoc();
        $stmt->close();

        return $funcionary;
    }

    function findAllEmployees($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM funcionario");
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
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

    function findEmployeeWithEmail($mysqli, $email) {
        $stmt = $mysqli->prepare("SELECT * FROM funcionario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $funcionary = $result->fetch_assoc();
        $stmt->close();

        return $funcionary;
    }

    function findCopilot($mysqli, $employee_id) {
        $stmt = $mysqli->prepare("SELECT * FROM copiloto WHERE id_copiloto = ?");
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $funcionary = $result->fetch_assoc();
        $stmt->close();

        return $funcionary;
    }

    function findAdministrative($mysqli, $employee_id) {
        $stmt = $mysqli->prepare("SELECT * FROM administrativo WHERE id_administrativo = ?");
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $funcionary = $result->fetch_assoc();
        $stmt->close();

        return $funcionary;
    }

    function findDriver($mysqli, $employee_id) {
        $stmt = $mysqli->prepare("SELECT * FROM conductor WHERE id_conductor = ?");
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $funcionary = $result->fetch_assoc();
        $stmt->close();

        return $funcionary;
    }


    function findSuperUser($mysqli, $employee_id) {
        $stmt = $mysqli->prepare("SELECT funcionario.pass FROM super_usuario INNER JOIN funcionario 
                                ON super_usuario.id_funcionario = funcionario.id_funcionario
                                WHERE super_usuario.id_super_usuario = ?");
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $funcionary = $result->fetch_assoc();
        $stmt->close();

        return $funcionary;
    }

    function insertCellphone($mysqli, $phone_number, $funcionary_id) {
        $stmt = $mysqli->prepare("INSERT INTO telefono_funcionario (telefono, id_funcionario) VALUES(?, ?)");
        $stmt->bind_param("si", $phone_number, $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteCellphone($mysqli, $cellphone_id) {
        $stmt = $mysqli->prepare("DELETE FROM telefono_funcionario WHERE id_telefono = ?");
        $stmt->bind_param("i", $cellphone_id);
        $stmt->execute();
        $stmt->close();
    }

    function getStateOptions($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM estado_funcionario");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function updateCellphone($mysqli, $new_fullphone, $cellphone_id) {
        $stmt = $mysqli->prepare("UPDATE telefono_funcionario 
                                SET telefono = ?
                                WHERE id_telefono = ?");
        $stmt->bind_param("si", $new_fullphone, $cellphone_id);
        $stmt->execute();
        $stmt->close();
    }

    function insertEmployee($mysqli, $first_name, $last_name, $document, $nationality, $birthdate, $department,
                $locality, $direction, $apartment, $email, $position, $entry_date, $hash_pass, $id_state) {
        $stmt = $mysqli->prepare("INSERT INTO funcionario
                                    (nombre, apellido, cedula, nacionalidad, fecha_nacimiento, departamento, localidad,
                                    direccion, numero_puerta, email, cargo, fecha_ingreso, pass, id_estado_funcionario)
                                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssi", $first_name, $last_name, $document, $nationality, $birthdate, $department,
                $locality, $direction, $apartment, $email, $position, $entry_date, $hash_pass, $id_state);
        $stmt->execute();
        $id = $mysqli->insert_id;
        $stmt->close();
        return $id;
    }

    function updateEmployee($mysqli, $first_name, $last_name, $nationality, $birthdate, $department,
                $locality, $address, $door_number, $email, $position, $entry_date, $funcionary_id) {

        $stmt = $mysqli->prepare("UPDATE funcionario SET nombre = ?, apellido = ?, nacionalidad = ?, fecha_nacimiento = ?, departamento = ?,
                                        localidad = ?, direccion = ?, numero_puerta = ?, email = ?, cargo = ?, fecha_ingreso = ? WHERE id_funcionario = ?");
        $stmt->bind_param("sssssssssssi", $first_name, $last_name, $nationality, $birthdate, $department,
                $locality,  $address, $door_number, $email, $position, $entry_date, $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function insertAdministrative($funcionary_id, $mysqli, $permissions, $employee_id) {

        $stmt = $mysqli->prepare("INSERT INTO administrativo (id_administrativo, permisos, id_funcionario) VALUES(?, ?, ?)");
        $stmt->bind_param("ssi", $employee_id, $permissions, $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteAdministrative($funcionary_id, $mysqli) {

        $stmt = $mysqli->prepare("DELETE FROM administrativo WHERE id_funcionario = ?");
        $stmt->bind_param("i", $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function insertDriver($funcionary_id, $mysqli, $license_expiration_date, $license_category, $employee_id) {

        $stmt = $mysqli->prepare("INSERT INTO conductor (id_conductor, vencimiento_carnet, categoria_carnet, id_funcionario) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("sssi", $employee_id, $license_expiration_date, $license_category, $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteDriver($funcionary_id, $mysqli) {

        $stmt = $mysqli->prepare("DELETE FROM conductor WHERE id_funcionario = ?");
        $stmt->bind_param("i", $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function insertCopilot($funcionary_id, $mysqli, $speciality, $employee_id) {

        $stmt = $mysqli->prepare("INSERT INTO copiloto (id_copiloto, especialidad, id_funcionario) VALUES(?, ?, ?)");
        $stmt->bind_param("ssi", $employee_id, $speciality, $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteCopilot($funcionary_id, $mysqli) {

        $stmt = $mysqli->prepare("DELETE FROM copiloto WHERE id_funcionario = ?");
        $stmt->bind_param("i", $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function findEmployeeCellphone($mysqli, $funcionary_id, $fullphone) {
        $stmt = $mysqli->prepare("SELECT * FROM telefono_funcionario WHERE id_funcionario = ? AND telefono = ?");
        $stmt->bind_param("is", $funcionary_id, $fullphone);
        $stmt->execute();

        $result = $stmt->get_result();
        $cellphone = $result->fetch_assoc();

        $stmt->close();
        return $cellphone;
    }

    function updateAdministrative($funcionary_id, $mysqli, $permissions) {
        $stmt = $mysqli->prepare("UPDATE administrativo
                                    SET permisos = ?
                                    WHERE id_funcionario = ?");
        $stmt->bind_param("si", $permissions, $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function updateCopilot($funcionary_id, $mysqli, $speciality) {
        $stmt = $mysqli->prepare("UPDATE copiloto
                                    SET especialidad = ?
                                    WHERE id_funcionario = ?");
        $stmt->bind_param("si", $speciality, $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function updateDriver($funcionary_id, $mysqli, $license_expiration_date, $license_category) {
        $stmt = $mysqli->prepare("UPDATE conductor
                                    SET vencimiento_carnet = ?, categoria_carnet = ?
                                    WHERE id_funcionario = ?");
        $stmt->bind_param("ssi", $license_expiration_date, $license_category, $funcionary_id);
        $stmt->execute();
        $stmt->close();
    }

    function changeStateEmployee($mysqli, $employee_id, $state_id) {
        $stmt = $mysqli->prepare("UPDATE funcionario SET id_estado_funcionario = ?
                                    WHERE id_funcionario = ?");
        $stmt->bind_param("ii", $state_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    }

    function changePasswordEmployee($mysqli, $employee_id, $password) {
        $stmt = $mysqli->prepare("UPDATE funcionario SET pass = ? WHERE id_funcionario = ?");
        $stmt->bind_param("si", $password, $employee_id);
        $stmt->execute();
        $stmt->close();
    }
?>