<?php

    require_once __DIR__ . "/../models/companion_model.php";
    
    function createCompanionList($companions, $mysqli) {

        foreach ($companions as $companion) {
            
            echo "<li>";
            echo "<p> " . htmlspecialchars($companion['cedula']) . "</p>";
            echo "<p> " . htmlspecialchars($companion['nombre']) . "</p>";
            echo "<p>Estado:</p>";
            echo "<select class='change-state-select' data-id='" . htmlspecialchars($companion['id_companion']) . "'>";
            loadStates($mysqli, $companion['id_estado_companion']);
            echo "</select>";
            echo "<button class='change-state' data-id='" . htmlspecialchars($companion['id_companion']) . "'>Confirmar</button>";
            echo "</li>";
        }
    }

    function loadStates($mysqli, $state_id) {

        $companion_states = findAllCompanionsStates($mysqli);

        foreach ($companion_states as $state) {
        
            if ($state_id === $state['id_estado_companion']) continue;
            echo "<option value='" . htmlspecialchars($state['id_estado_companion']) . "'>" . htmlspecialchars($state['nombre']) . "</option>";
        }

    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

?>