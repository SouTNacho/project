<?php

    require_once __DIR__ . "/../../models/super_user_model.php";
    require_once __DIR__ . "/../../conection.php";

    $mysqli = connection_db();

    $state_id = isset($_GET['id_state'])
        ? (int) $_GET['id_state']
        : 0;

    $phrase = isset($_GET['phrase'])
        ? $_GET['phrase']
        : "null";

    $super_users = findSuperUsersWithFilter(
        $mysqli,
        $state_id,
        $phrase
    );

    echo json_encode([
        "success" => true,
        "item" => $super_users
    ]);
?>