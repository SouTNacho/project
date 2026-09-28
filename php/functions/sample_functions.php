<?php

    require_once __DIR__ . "/../models/sample_model.php";

    function createSampleList($samples, $mysqli) {

        foreach ($samples as $sample) {

            $state_id = $sample['id_estado_muestra'];
            
            echo "<li>";
            echo "<p>Código: " . htmlspecialchars($sample['codigo']) . "</p>";
            echo "<p>Desc:  " . htmlspecialchars($sample['descripcion']) . "</p>";

            $patient = findPatientWithId($mysqli, (int) $sample['id_paciente']);

            if ($patient) {
                echo "<p>Paciente " . htmlspecialchars($patient['cedula']) . "</p>";
            }

            echo "<p>Estado: " . htmlspecialchars(loadStateName($mysqli, $sample['id_estado_muestra'])) . "</p>";

            if ((int) $state_id !== 3) {

                echo "<select class='change-state-select' data-id='" . htmlspecialchars($sample['id_muestra']) . "'>";
                loadStates($mysqli, $sample['id_estado_muestra']);
                echo "</select>";
                echo "<button class='change-state' data-id='" . htmlspecialchars($sample['id_muestra']) . "'>Confirmar</button>";

            }

            echo "</li>";
        }
    }

    function loadStates($mysqli, $state_id) {

        $sample_states = findAllSampleStates($mysqli);

        foreach ($sample_states as $state) {
        
            if ($state_id === $state['id_estado_muestra']) continue;
            echo "<option value='" . htmlspecialchars($state['id_estado_muestra']) . "'>" . htmlspecialchars($state['nombre']) . "</option>";
        }

    }

    function loadStateName($mysqli, $state_id) {

        $sample_states = findAllSampleStates($mysqli);

        foreach ($sample_states as $state) {
        
            if ($state_id === $state['id_estado_muestra']) {

                return $state['nombre'];
            }
        }

    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

?>