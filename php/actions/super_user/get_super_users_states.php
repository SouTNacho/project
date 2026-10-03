<?php

    require_once __DIR__ . "/../../models/super_user_model.php";
    require_once __DIR__ . "/../../conection.php";

    $mysqli = connection_db();

    $states = findAllSuperUsersStates($mysqli);

    echo json_encode([
        "success" => true,
        "item" => $states
    ]);
?>