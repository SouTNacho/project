<?php

    require_once __DIR__ . "/../models/ambulance_model.php";
    
    function createAmbulancesList($ambulances, $mysqli) {

        foreach ($ambulances as $ambulance) {
        
            $state_id = $ambulance['id_estado_ambulancia'];

            echo "<li>";
            echo "<p> " . htmlspecialchars($ambulance['matricula']) . "</p>";
            echo "<p> " . htmlspecialchars($ambulance['marca']) . "</p>";
            echo "<p> " . htmlspecialchars($ambulance['modelo']) . "</p>";

            echo "<p>Estado: " . htmlspecialchars(loadStateName($mysqli, $ambulance['id_estado_ambulancia'])) . "</p>";

            if ((int) $state_id !== 4) {

                echo "<select class='change-state-select' data-id='" . htmlspecialchars($ambulance['id_ambulancia']) . "'>";
                loadStates($mysqli, $ambulance['id_estado_ambulancia']);
                echo "</select>";
                echo "<button class='change-state' data-id='" . htmlspecialchars($ambulance['id_ambulancia']) . "'>Confirmar</button>";

            }

            echo "</li>";
        }
    }

    function loadStates($mysqli, $state_id) {

        $ambulance_states = findAllAmbulancesStates($mysqli);

        foreach ($ambulance_states as $state) {
        
            if ($state_id === $state['id_estado_ambulancia']) continue;
            echo "<option value='" . htmlspecialchars($state['id_estado_ambulancia']) . "'>" . htmlspecialchars($state['nombre']) . "</option>";
        }

    }

    function loadStateName($mysqli, $state_id) {

        $ambulance_states = findAllAmbulancesStates($mysqli);

        foreach ($ambulance_states as $state) {
        
            if ($state_id === $state['id_estado_ambulancia']) {

                return $state['nombre'];
            }
        }

    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

?>