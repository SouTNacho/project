<?php

    function findPatientWithDocument($mysqli, $document) {
        $stmt = $mysqli->prepare("SELECT * FROM paciente WHERE cedula = ?");
        $stmt->bind_param("s", $document);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
        $stmt->close();

        return $patient;
    }

    function findPatientWithId($mysqli, $patient_id) {
        $stmt = $mysqli->prepare("SELECT * FROM paciente WHERE id_paciente = ?");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
        $stmt->close();

        return $patient;
    }

    function findPatientWithPhoneNumber($mysqli, $phone_number) {
        $stmt = $mysqli->prepare("SELECT * FROM paciente WHERE telefono = ?");
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
        $stmt->close();

        return $patient;
    }

    function findAllPatients($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM paciente");
        $stmt->execute();
        $result = $stmt->get_result();
        $patients = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $patients;
    }

    function findAllPatientsStates($mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM estado_paciente");
        $stmt->execute();
        $result = $stmt->get_result();
        $patients_states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $patients_states;
    }

    function findPatientWithEmail($mysqli, $email) {
        $stmt = $mysqli->prepare("SELECT * FROM paciente WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $patient = $result->fetch_assoc();
        $stmt->close();

        return $patient;
    }

    function insertPatient($mysqli, $first_name, $last_name, $document, $phone_number, $email, $birthdate, $direction) {
        
        $stmt = $mysqli->prepare("INSERT INTO paciente (cedula,
                            nombre, apellido, fecha_nacimiento,
                            telefono, direccion, email)
                            VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $document, $first_name, $last_name, $birthdate, $phone_number, $direction, $email);
        $stmt->execute();
        $stmt->close();
    }

    function updatePatient($mysqli, $first_name, $last_name, $birthdate, $address, $email, $phone_number, $document, $patient_id) {

        $stmt = $mysqli->prepare("UPDATE paciente SET cedula = ?, nombre = ?, apellido = ?, fecha_nacimiento = ?, telefono = ?, direccion = ?, email = ? WHERE id_paciente = ?");
        $stmt->bind_param("sssssssi", $document, $first_name, $last_name, $birthdate, $phone_number, $address, $email, $patient_id);
        $stmt->execute();
        $stmt->close();
    }

    function changeStatePatient($mysqli, $patient_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE paciente SET id_estado_paciente = ? WHERE id_paciente = ?");
        $stmt->bind_param("ii", $state_id, $patient_id);
        $stmt->execute();
        $stmt->close();
    }

?>