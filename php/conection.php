<?php

    function connection_db() {

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "byp";

        $connection = mysqli_connect($host, $user, $pass, $db);

        if (!$connection) {
            die("Error: " . mysqli_connect_error());
        }

        return $connection;
    }

?>