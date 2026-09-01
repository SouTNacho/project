<?php

    require_once __DIR__ . "/../models/element_model.php";
    
    function createElementList($elements, $mysqli) {

        foreach ($elements as $element) {
            
            echo "<li>";
            echo "<p> " . htmlspecialchars($element['codigo']) . "</p>";
            echo "<p> " . htmlspecialchars($element['nombre']) . "</p>";
            echo "<p>Estado:</p>";
            echo "<select class='change-state-select' data-id='" . htmlspecialchars($element['id_elemento']) . "'>";
            loadStates($mysqli, $element['id_estado_elemento']);
            echo "</select>";
            echo "<button class='change-state' data-id='" . htmlspecialchars($element['id_elemento']) . "'>Confirmar</button>";
            echo "</li>";
        }
    }

    function loadStates($mysqli, $state_id) {

        $element_states = findAllElementsStates($mysqli);

        foreach ($element_states as $state) {
        
            if ($state_id === $state['id_estado_elemento']) continue;
            echo "<option value='" . htmlspecialchars($state['id_estado_elemento']) . "'>" . htmlspecialchars($state['nombre']) . "</option>";
        }

    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

?>