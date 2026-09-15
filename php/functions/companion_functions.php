<?php

    function createCompanionList($companions, $mysqli) {
        
        require_once __DIR__ . "/../models/companion_model.php";

        foreach ($companions as $companion) {
            
            echo "<li>";
            echo "<p> " . htmlspecialchars($companion['cedula']) . "</p>";
            echo "<p> " . htmlspecialchars($companion['nombre']) . "</p>";
            echo "<p> " . htmlspecialchars($companion['apellido']) . "</p>";
            echo "</li>";
        }
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

?>