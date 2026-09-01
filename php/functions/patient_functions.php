<?php

    require_once __DIR__ . "/../models/patient_model.php";
    
    function createPatientList($patients, $mysqli) {

        foreach ($patients as $patient) {
            
            echo "<li>";
            echo "<p> " . htmlspecialchars($patient['cedula']) . "</p>";
            echo "<p> " . htmlspecialchars($patient['nombre']) . "</p>";
            echo "<p>Estado:</p>";
            echo "<select class='change-state-select' data-id='" . htmlspecialchars($patient['id_paciente']) . "'>";
            loadStates($mysqli, $patient['id_estado_paciente']);
            echo "</select>";
            echo "<button class='change-state' data-id='" . htmlspecialchars($patient['id_paciente']) . "'>Confirmar</button>";
            echo "</li>";
        }
    }

    function loadStates($mysqli, $state_id) {

        $patient_states = findAllPatientsStates($mysqli);

        foreach ($patient_states as $state) {
        
            if ($state_id === $state['id_estado_paciente']) continue;
            echo "<option value='" . htmlspecialchars($state['id_estado_paciente']) . "'>" . htmlspecialchars($state['nombre']) . "</option>";
        }

    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

?>