<?php

    function createServicesOptions($services) {

        foreach ($services as $service) {

            echo "<option value='" . htmlspecialchars($service['id_servicio']) . "'>" . htmlspecialchars($service['nombre']) . "</option>";
        }
    }

?>