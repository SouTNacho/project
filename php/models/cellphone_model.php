<?php

    function findCellphoneWithNumber($mysqli, $phone_number) {

        $stmt = $mysqli->prepare("SELECT * FROM telefono_funcionario WHERE telefono = ?");
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();

        $result = $stmt->get_result();
        $cellphone = $result->fetch_assoc();
        $stmt->close();

        return $cellphone;
    }

    function findCellphoneWithId($mysqli, $cellphone_id) {

        $stmt = $mysqli->prepare("SELECT telefono_funcionario.*, funcionario.cedula FROM telefono_funcionario INNER JOIN funcionario
                                ON telefono_funcionario.id_funcionario = funcionario.id_funcionario WHERE telefono_funcionario.id_telefono = ?");
        $stmt->bind_param("i", $cellphone_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $cellphone = $result->fetch_assoc();
        $stmt->close();

        return $cellphone;
    }

    function findEmployeeCellphones($mysqli, $document) {

        $stmt = $mysqli->prepare("SELECT telefono_funcionario.* FROM telefono_funcionario INNER JOIN funcionario
                                ON telefono_funcionario.id_funcionario = funcionario.id_funcionario WHERE funcionario.cedula LIKE ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();

        $result = $stmt->get_result();
        $cellphones = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $cellphones;
    }

    function findAllCellphonesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM telefono_funcionario WHERE telefono LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $cellphones = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $cellphones;
    }

    function findAllCellphones($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM telefono_funcionario");
        $stmt->execute();

        $result = $stmt->get_result();
        $cellphones = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $cellphones;
    }

    function insertCellphone($mysqli, $phone_number, $employee_id) {

        $stmt = $mysqli->prepare("INSERT INTO telefono_funcionario (telefono, id_funcionario) VALUES (?, ?)");
        $stmt->bind_param("si", $phone_number, $employee_id);
        $stmt->execute();
        $stmt->close();
    }

    function updateCellphone($mysqli, $new_fullphone, $cellphone_id) {

        $stmt = $mysqli->prepare("UPDATE telefono_funcionario SET telefono = ? WHERE id_telefono = ?");
        $stmt->bind_param("si", $new_fullphone, $cellphone_id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteCellphone($mysqli, $cellphone_id) {

        $stmt = $mysqli->prepare("DELETE FROM telefono_funcionario WHERE id_telefono = ?");
        $stmt->bind_param("i", $cellphone_id);
        $stmt->execute();
        $stmt->close();
    }

    function findCellphoneWithEmployeeAndNumber($mysqli, $employee_id, $phone_number) {

        $stmt = $mysqli->prepare("SELECT * FROM telefono_funcionario WHERE id_funcionario = ? AND telefono = ?");
        $stmt->bind_param("is", $employee_id, $phone_number);
        $stmt->execute();

        $result = $stmt->get_result();
        $cellphone = $result->fetch_assoc();
        $stmt->close();

        return $cellphone;
    }

?>