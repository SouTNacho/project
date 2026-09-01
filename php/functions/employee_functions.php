<?php

    require_once __DIR__ . "/../models/employee_model.php";

    function deleteRole($employee_type, $funcionary_id, $mysqli) {

        switch ($employee_type) {

            case "FA":

                deleteAdministrative($funcionary_id, $mysqli);
                break;
            case "CO":

                deleteCopilot($funcionary_id, $mysqli);
                break;
            case "DR":

                deleteDriver($funcionary_id, $mysqli);
                break;
            default:

                return false;
        }
    }

    function insertRole($employee_type, $funcionary_id, $mysqli, $permissions, $especiality, $license_expired, $license_category, $employee) {

        switch ($employee_type) {

            case "FA":
                insertAdministrative($funcionary_id, $mysqli, $permissions, $employee);
                break;

            case "CO":
                insertCopilot($funcionary_id, $mysqli, $especiality, $employee);
                break;

            case "DR":
                insertDriver($funcionary_id, $mysqli, $license_expired, $license_category, $employee);
                break;
            default:

                return false;
        }
    }

    function updateRole($employee_type, $funcionary_id, $mysqli, $permissions, $especiality, $license_expiration_date, $license_category) {

        switch ($employee_type) {

            case "FA":
                updateAdministrative($funcionary_id, $mysqli, $permissions);
                break;

            case "CO":
                updateCopilot($funcionary_id, $mysqli, $especiality);
                break;

            case "DR":
                updateDriver($funcionary_id, $mysqli, $license_expiration_date, $license_category);
                break;
            default:

                return false;
        }
    }

    function getEmployeeType($employee_id) {
        return substr($employee_id, 0, 2);
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    function loadStateOptions($mysqli, $stateName) {

        $states = getStateOptions($mysqli);

        foreach ($states as $state) {

            if ($state["nombre"] === $stateName) continue;
            echo "<option value='" . htmlspecialchars($state['id_estado_funcionario']) . "'>" . htmlspecialchars($state['nombre']) . "</option>";
        }

    }

    function createEmployeeList($employees, $mysqli) {

        $states = [
            1 => "Activo", 2 => "Licencia Medica", 3 => "Licencia Anual", 4 => "Seguro de Paro", 5 => "Suspendido", 6 => "Inactivo", 7 => "Jubilado",
        ];

        $positions = [
            "SU" => "Administrador", "FA" => "Administrativo", "DR" => "Conductor", "CO" => "Copiloto"
        ];

        foreach ($employees as $employee) {
            if ($employee["cargo"] === "SU") continue;

            echo "<li>";
            echo "<p> " . htmlspecialchars($employee['nombre']) . "</p>";
            echo "<p> " . htmlspecialchars($positions[$employee["cargo"]]) . "</p>";
            echo "<p>Fecha de Ingreso: " . htmlspecialchars($employee['fecha_ingreso']) . "</p>";
            echo "<p>Estado: " . htmlspecialchars($states[$employee["id_estado_funcionario"]]) . "</p>";
            echo "<div class='edit-state-container'>";
            echo "<select class='change-state-select' data-id='" . htmlspecialchars($employee['id_funcionario']) . "'>";
            loadStateOptions($mysqli, $states[$employee["id_estado_funcionario"]]);
            echo "</select>";
            echo "<button class='change-state-button' data-id='" . htmlspecialchars($employee['id_funcionario']) . "'>Cambiar Estado</button>";
            echo "</div>";
            echo "<button class='change-password-button' data-id='" . htmlspecialchars($employee['id_funcionario']) . "'>Cambiar Contraseña</button>";
            echo "</li>";
        }
    }

?>