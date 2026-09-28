<?php

    require_once __DIR__ . "/../models/super_user_model.php";
    
    function createSuperUsersList($super_users, $mysqli) {

        foreach ($super_users as $super_user) {

            $state_id = $super_user['id_estado_super_usuario'];
            
            echo "<li>";
            echo "<p> " . htmlspecialchars($super_user['nombre']) . "</p>";
            echo "<p> " . htmlspecialchars($super_user['permisos']) . "</p>";
            echo "<p>Estado: " . htmlspecialchars(loadStateName($mysqli, $super_user['id_estado_super_usuario'])) . "</p>";

            if ((int) $state_id !== 3) {

                echo "<select class='change-state-select' data-id='" . htmlspecialchars($super_user['id_super_usuario']) . "'>";
                loadStates($mysqli, $super_user['id_estado_super_usuario']);
                echo "</select>";
                echo "<button class='change-state' data-id='" . htmlspecialchars($super_user['id_super_usuario']) . "'>Confirmar</button>";

            }

            echo "</li>";
        }
    }

    function loadStates($mysqli, $state_id) {

        $super_user_states = findAllSuperUsersStates($mysqli);

        foreach ($super_user_states as $state) {
        
            if ($state_id === $state['id_estado_super_usuario']) continue;
            echo "<option value='" . htmlspecialchars($state['id_estado_super_usuario']) . "'>" . htmlspecialchars($state['nombre']) . "</option>";
        }

    }

    function loadStateName($mysqli, $state_id) {

        $super_user_states = findAllSuperUsersStates($mysqli);

        foreach ($super_user_states as $state) {
        
            if ($state_id === $state['id_estado_super_usuario']) {

                return $state['nombre'];
            }
        }

    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

?>