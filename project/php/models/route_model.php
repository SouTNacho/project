<?php

    function findRouteWithOriginAndDestination($mysqli, $origin, $destination) {

        $stmt = $mysqli->prepare( "SELECT * FROM ruta WHERE origen = ? AND destino = ?");
        $stmt->bind_param("ss", $origin, $destination);
        $stmt->execute();
        $result = $stmt->get_result();
        $route = $result->fetch_assoc();
        $stmt->close();

        return $route;
    }

    function findRouteWithId($mysqli, $route_id) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_ruta = ?");
        $stmt->bind_param("i", $route_id);
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

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE origen LIKE ? OR destino LIKE ?");
        $stmt->bind_param("ss", $phrase, $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findActiveRoutesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 1 AND (origen LIKE ? OR destino LIKE ?)");
        $stmt->bind_param("ss", $phrase, $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findInactiveRoutesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 2 AND (origen LIKE ? OR destino LIKE ?)");
        $stmt->bind_param("ss", $phrase, $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function findDeletedRoutesWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ruta WHERE id_estado_ruta = 3 AND (origen LIKE ? OR destino LIKE ?)");
        $stmt->bind_param("ss", $phrase, $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $routes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $routes;
    }

    function insertRoute($mysqli, $origin, $destination) {

        $stmt = $mysqli->prepare("INSERT INTO ruta(origen, destino) VALUES (?, ?)");
        $stmt->bind_param("ss", $origin, $destination);
        $stmt->execute();
        $stmt->close();
    }

    function updateRoute($mysqli, $origin, $destination, $route_id) {

        $stmt = $mysqli->prepare("UPDATE ruta SET origen = ?, destino = ? WHERE id_ruta = ?");
        $stmt->bind_param("ssi", $origin, $destination, $route_id);
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