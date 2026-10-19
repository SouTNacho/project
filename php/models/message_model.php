<?php
/** Guarda el consentimiento junto a la consulta, usando parámetros SQL seguros. */
function insertContactMessage(mysqli $mysqli, string $name, string $email, string $content): void
{
    $stmt = $mysqli->prepare(
        "INSERT INTO mensaje_contacto (nombre, correo, mensaje, consentimiento, fecha_consentimiento) VALUES (?, ?, ?, 1, NOW())"
    );
    try {
        $stmt->bind_param("sss", $name, $email, $content);
        $stmt->execute();
    } finally {
        $stmt->close();
    }
}
