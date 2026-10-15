<?php

function insertContactMessage(mysqli $mysqli, string $name, string $email, string $content): void
{
    $stmt = $mysqli->prepare(
        "INSERT INTO mensaje (nombre, email, contenido) VALUES (?, ?, ?)"
    );

    try {
        $stmt->bind_param("sss", $name, $email, $content);
        $stmt->execute();
    } finally {
        $stmt->close();
    }
}

?>
