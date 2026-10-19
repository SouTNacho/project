<?php
/*
 * Endpoint del formulario público de contacto.
 * Todas las respuestas se devuelven en JSON, incluso ante errores fatales.
 */
ob_start();
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store");

function contactResponse(bool $success, string $message, int $status = 200, ?string $field = null): void
{
    if (ob_get_level() > 0) {
        ob_clean();
    }

    http_response_code($status);
    header("Content-Type: application/json; charset=UTF-8");

    $response = [
        "success" => $success,
        "message" => $message
    ];

    if ($field !== null) {
        $response["field"] = $field;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

/* Evita que un error fatal deje al fetch sin una respuesta JSON. */
register_shutdown_function(function (): void {
    $error = error_get_last();
    $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];

    if ($error !== null && in_array($error["type"], $fatalTypes, true)) {
        if (ob_get_level() > 0) {
            ob_clean();
        }

        if (!headers_sent()) {
            http_response_code(500);
            header("Content-Type: application/json; charset=UTF-8");
        }

        error_log("Error fatal en send_message.php: " . ($error["message"] ?? "desconocido"));
        echo json_encode([
            "success" => false,
            "message" => "Ocurrió un error interno al procesar el mensaje. Revisá la configuración del servidor."
        ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
});

if (($_SERVER["REQUEST_METHOD"] ?? "") !== "POST") {
    contactResponse(false, "Método no permitido.", 405);
}

try {
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/message_model.php";
    require_once __DIR__ . "/../../conection.php";

    $name = trim((string) ($_POST["contact_name"] ?? ""));
    $email = trim((string) ($_POST["contact_email"] ?? ""));
    $content = trim((string) ($_POST["contact_message"] ?? ""));
    $consent = ($_POST["contact_consent"] ?? null) === "1";
    if (!$consent) {
        contactResponse(false, "Debés aceptar los términos y condiciones para enviar la consulta.", 422, "contact_consent");
    }

    if (!function_exists("validateName") || !function_exists("validateEmail")) {
        throw new RuntimeException("No están disponibles las funciones de validación del formulario de contacto.");
    }

    if (!validateName($name)) {
        contactResponse(false, "Ingresá un nombre válido. Cada palabra debe comenzar con mayúscula.", 422, "contact_name");
    }

    if (!validateEmail($email)) {
        contactResponse(false, "Ingresá un correo electrónico válido.", 422, "contact_email");
    }

    $messageLength = function_exists("mb_strlen")
        ? mb_strlen($content, "UTF-8")
        : strlen($content);

    if (
        $messageLength < 10 ||
        $messageLength > 2000 ||
        preg_match('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F\\x7F]/', $content)
    ) {
        contactResponse(false, "El mensaje debe tener entre 10 y 2000 caracteres.", 422, "contact_message");
    }

    $mysqli = connection_db();
    $mysqli->set_charset("utf8mb4");

    insertContactMessage($mysqli, $name, $email, $content);

    $mysqli->close();
    contactResponse(true, "Tu mensaje se envió correctamente. Gracias por contactarnos.");
} catch (Throwable $exception) {
    if (isset($mysqli) && $mysqli instanceof mysqli) {
        try {
            $mysqli->close();
        } catch (Throwable $ignored) {
            // No reemplazar el error original.
        }
    }

    error_log("Error al guardar mensaje de contacto: " . $exception->getMessage());
    contactResponse(false, "No se pudo guardar el mensaje. Verificá que la base de datos BYP tenga aplicada la migración de mensajes y que MySQL esté funcionando.", 500);
}
