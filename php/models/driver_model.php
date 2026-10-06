<?php

    function findDriverWithCode($mysqli, $driver_code) {

        $stmt = $mysqli->prepare("SELECT * FROM conductor WHERE codigo = ?");
        $stmt->bind_param("s", $driver_code);
        $stmt->execute();

        $result = $stmt->get_result();
        $driver = $result->fetch_assoc();
        $stmt->close();

        return $driver;
    }


    function findDriverWithEmployeeId($mysqli, $employee_id) {

        $stmt = $mysqli->prepare("SELECT * FROM conductor WHERE id_funcionario = ?");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $driver = $result->fetch_assoc();
        $stmt->close();

        return $driver;
    }


    function findAllDrivers($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM conductor");
        $stmt->execute();

        $result = $stmt->get_result();
        $drivers = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $drivers;
    }


    function findAllDriversWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM conductor WHERE codigo LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $drivers = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $drivers;
    }


    function insertDriver($mysqli, $license_expiration_date, $license_category, $password, $employee_id) {

        $stmt = $mysqli->prepare("INSERT INTO conductor (vencimiento_carnet, categoria_carnet, pass, id_funcionario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $license_expiration_date, $license_category, $password, $employee_id);
        $stmt->execute();

        $id = $stmt->insert_id;
        $stmt->close();

        return $id;
    }


    function findDriverWithDocument($mysqli, $document) {

        $stmt = $mysqli->prepare(" SELECT c.* FROM conductor c INNER JOIN funcionario f
                                    ON c.id_funcionario = f.id_funcionario WHERE f.cedula = ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();

        $result = $stmt->get_result();
        $driver = $result->fetch_assoc();
        $stmt->close();

        return $driver;
    }


    function updateDriverCode($mysqli, $driver_id, $driver_code) {

        $stmt = $mysqli->prepare("UPDATE conductor SET codigo = ? WHERE id_conductor = ?");
        $stmt->bind_param("si", $driver_code, $driver_id);
        $stmt->execute();
        $stmt->close();
    }


    function updateDriver($mysqli, $driver_id, $license_expiration_date, $license_category, $employee_id) {

        $stmt = $mysqli->prepare("UPDATE conductor SET vencimiento_carnet = ?, categoria_carnet = ?, id_funcionario = ? WHERE id_conductor = ?");
        $stmt->bind_param("ssii", $license_expiration_date, $license_category, $employee_id, $driver_id);
        $stmt->execute();
        $stmt->close();
    }


    function changePasswordDriver($mysqli, $driver_id, $password) {

        $stmt = $mysqli->prepare("UPDATE conductor SET pass = ? WHERE id_conductor = ?");
        $stmt->bind_param("si", $password, $driver_id);
        $stmt->execute();
        $stmt->close();
    }

?>