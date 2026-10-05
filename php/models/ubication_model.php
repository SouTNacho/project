<?php

    function findUbicationWithName($mysqli, $name) {

        $stmt = $mysqli->prepare("SELECT * FROM ubicacion WHERE nombre = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $ubication = $result->fetch_assoc();
        $stmt->close();

        return $ubication;
    }

    function findUbicationWithId($mysqli, $ubication_id) {

        $stmt = $mysqli->prepare("SELECT * FROM ubicacion WHERE id_ubicacion = ?");
        $stmt->bind_param("i", $ubication_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ubication = $result->fetch_assoc();
        $stmt->close();

        return $ubication;
    }

    function findAllUbications($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM ubicacion");
        $stmt->execute();
        $result = $stmt->get_result();
        $ubications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ubications;
    }

    function findActiveUbications($mysqli) {

        $stmt = $mysqli->prepare(
            "SELECT * FROM ubicacion WHERE id_estado_ubicacion = 1"
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $ubications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ubications;
    }

    function findInactiveUbications($mysqli) {

        $stmt = $mysqli->prepare(
            "SELECT * FROM ubicacion WHERE id_estado_ubicacion = 2"
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $ubications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ubications;
    }

    function findDeletedUbications($mysqli) {

        $stmt = $mysqli->prepare(
            "SELECT * FROM ubicacion WHERE id_estado_ubicacion = 3"
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $ubications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ubications;
    }

    function findAllUbicationsWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare(
            "SELECT * FROM ubicacion WHERE nombre LIKE ?"
        );
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $ubications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ubications;
    }

    function findActiveUbicationsWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ubicacion WHERE id_estado_ubicacion = 1 AND nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $ubications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ubications;
    }

    function findInactiveUbicationsWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ubicacion WHERE id_estado_ubicacion = 2 AND nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $ubications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ubications;
    }

    function findDeletedUbicationsWithPhrase($mysqli, $phrase) {

        $stmt = $mysqli->prepare("SELECT * FROM ubicacion WHERE id_estado_ubicacion = 3 AND nombre LIKE ?");
        $stmt->bind_param("s", $phrase);
        $stmt->execute();
        $result = $stmt->get_result();
        $ubications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $ubications;
    }

    function insertUbication($mysqli, $name, $address, $latitude, $longitude) {

        $stmt = $mysqli->prepare("INSERT INTO ubicacion(nombre, direccion, latitud, longitud) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $name, $address, $latitude, $longitude);
        $stmt->execute();
        $stmt->close();
    }

    function updateUbication($mysqli, $name, $address, $latitude, $longitude, $ubication_id) {

        $stmt = $mysqli->prepare("UPDATE ubicacion SET nombre = ?, direccion = ?, latitud = ?, longitud = ? WHERE id_ubicacion = ?");
        $stmt->bind_param("ssddi", $name, $address, $latitude, $longitude, $ubication_id);
        $stmt->execute();
        $stmt->close();
    }

    function findAllUbicationsStates($mysqli) {

        $stmt = $mysqli->prepare("SELECT * FROM estado_ubicacion");
        $stmt->execute();
        $result = $stmt->get_result();
        $states = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $states;
    }

    function changeStateUbication($mysqli, $ubication_id, $state_id) {

        $stmt = $mysqli->prepare("UPDATE ubicacionSET id_estado_ubicacion = ? WHERE id_ubicacion = ?");
        $stmt->bind_param("ii", $state_id, $ubication_id);
        $stmt->execute();
        $stmt->close();
    }

?>