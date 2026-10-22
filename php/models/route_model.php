<?php

    function findRouteWithId($mysqli, $route_id) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_ruta = ?");
        $stmt->bind_param("i", $route_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $route = $result->fetch_assoc();
        $stmt->close();

        return $route;
    }

    function findRouteWithName($mysqli, $route_name) {

        $stmt = $mysqli->prepare("SELECT id_ruta FROM ruta WHERE nombre = ?");
        $stmt->bind_param("s", $route_name);
        $stmt->execute();

        $result = $stmt->get_result();
        $route = $result->fetch_assoc();
        $stmt->close();

        return $route;
    }

    function findFullRoute($mysqli, $origin, $intermedium, $destination) {

        if ($intermedium === null || $intermedium === '') {

            $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_ubicacion_origen = ?
                AND id_ubicacion_intermedia IS NULL AND id_ubicacion_destino = ?");
            $stmt->bind_param("ii", $origin, $destination);

        } else {

            $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_ubicacion_origen = ?
                AND id_ubicacion_intermedia = ? AND id_ubicacion_destino = ?");
            $stmt->bind_param("iii", $origin, $intermedium, $destination);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        $route = $result->fetch_assoc();
        $stmt->close();

        return $route;
    }

    function findAllRoutes($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta");
        $stmt->execute();

        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findActiveRoutes($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 1");
        $stmt->execute();

        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findInactiveRoutes($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 2");
        $stmt->execute();

        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findDeletedRoutes($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 3");
        $stmt->execute();

        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findAllRoutesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findActiveRoutesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 1 AND nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findInactiveRoutesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 2 AND nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findDeletedRoutesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 3 AND nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();

        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function insertRoute($mysqli, $route_name, $origin, $intermedium, $destination) {

        if ($intermedium === null || $intermedium === '') {

            $stmt = $mysqli->prepare("INSERT INTO ruta (nombre, id_ubicacion_origen,
                id_ubicacion_intermedia, id_ubicacion_destino) VALUES (?, ?, NULL, ?)");
            $stmt->bind_param("sii", $route_name, $origin, $destination);

        } else {

            $stmt = $mysqli->prepare("INSERT INTO ruta (nombre, id_ubicacion_origen,
                id_ubicacion_intermedia, id_ubicacion_destino) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siii", $route_name, $origin, $intermedium, $destination);
        }

        $stmt->execute();
        $stmt->close();
    }

    function updateRoute($mysqli, $route_name, $origin, $intermedium, $destination, $route_id) {

        if ($intermedium === null || $intermedium === '') {

            $stmt = $mysqli->prepare("UPDATE ruta SET nombre = ?, id_ubicacion_origen = ?,
                id_ubicacion_intermedia = NULL, id_ubicacion_destino = ? WHERE id_ruta = ?");
            $stmt->bind_param("siii", $route_name, $origin, $destination, $route_id);

        } else {

            $stmt = $mysqli->prepare("UPDATE ruta SET nombre = ?, id_ubicacion_origen = ?,
                id_ubicacion_intermedia = ?, id_ubicacion_destino = ? WHERE id_ruta = ?");
            $stmt->bind_param("siiii", $route_name, $origin, $intermedium, $destination, $route_id);
        }

        $stmt->execute();
        $stmt->close();
    }

    function findAllRoutesStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_ruta");
        $stmt->execute();

        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function changeStateRoute($mysqli, $route_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE ruta SET id_estado_ruta = ? WHERE id_ruta = ?");
        $stmt->bind_param("ii", $state_id, $route_id);
        $stmt->execute();
        $stmt->close();
    }

?>