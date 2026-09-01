<?php

    function findAmbulanceWithRegistration($mysqli, $registration) {

        $stmt = $mysqli->prepare("SELECT * FROM ambulancia WHERE matricula = ?");
        $stmt->bind_param("s", $registration);
        $stmt->execute();
        $result = $stmt->get_result();
        $ambulance = $result->fetch_assoc();
        $stmt->close();

        return $ambulance;
    }

    function findAmbulanceWithId($mysqli, $ambulance_id) {

        $stmt = $mysqli->prepare("SELECT * FROM ambulancia WHERE id_ambulancia = ?");
        $stmt->bind_param("i", $ambulance_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ambulance = $result->fetch_assoc();
        $stmt->close();

        return $ambulance;
    }

    function findAllAmbulances($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM ambulancia");
        $stmt->execute();
        $result = $stmt->get_result();
        $ambulances = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ambulances;
    }

    function insertAmbulance($mysqli, $registration, $brand, $model, $year, $description) {

        $stmt = $mysqli->prepare("INSERT INTO ambulancia(matricula, marca, modelo, anio, descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $registration, $brand, $model, $year, $description);
        $stmt->execute();
        $stmt->close();
    }

    function updateAmbulance($mysqli, $registration, $brand, $model, $year, $description, $ambulance_id) {

        $stmt = $mysqli->prepare("UPDATE ambulancia SET matricula = ?, marca = ?, modelo = ?, anio = ?, descripcion = ? WHERE id_ambulancia = ?");
        $stmt->bind_param("sssiss", $registration, $brand, $model, $year, $description, $ambulance_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllAmbulancesStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_ambulancia");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function changeStateAmbulance($mysqli, $ambulance_id, $state_id) {
        
        $stmt = $mysqli->prepare("UPDATE ambulancia SET id_estado_ambulancia = ? WHERE id_ambulancia = ?");
        $stmt->bind_param("ii", $state_id, $ambulance_id);
        $stmt->execute();
        $stmt->close();
    }

?>