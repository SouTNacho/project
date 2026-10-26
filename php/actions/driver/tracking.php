<?php
session_start();
require_once __DIR__ . '/../../functions/session.php';
validateActionSession('driver');
header('Cache-Control: no-store');
require_once __DIR__ . '/../../config/driver_tracking.php';
require_once __DIR__ . '/../../functions/driver_tracking_functions.php';
require_once __DIR__ . '/../../conection.php';

function trackingResponse($status, $body) {
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['GET', 'POST'], true)) {
    header('Allow: GET, POST');
    trackingResponse(405, ['success' => false, 'message' => 'Método no permitido.']);
}
if ($method === 'POST' && (!is_string($_POST['csrf'] ?? null) ||
    !isset($_SESSION['driver_tracking_csrf']) || !hash_equals($_SESSION['driver_tracking_csrf'], $_POST['csrf']))) {
    trackingResponse(403, ['success' => false, 'message' => 'Recargue la página para renovar la sesión.']);
}
$code = loggedEmployeeCode();
$mysqli = null;
try {
    $mysqli = connection_db();
    $mysqli->set_charset('utf8mb4');
    if ($method === 'GET') {
        $data = getDriverTracking($mysqli, DRIVER_TEST_TRANSFER_ID, $code);
        $data['ubicacion'] = findDriverLocation($mysqli, DRIVER_TEST_TRANSFER_ID);
    } else {
        $data = processDriverTracking($mysqli, DRIVER_TEST_TRANSFER_ID, $code, $_POST['operacion'] ?? '', $_POST);
    }
    $mysqli->close();
    trackingResponse(200, ['success' => true, 'traslado' => $data]);
} catch (InvalidArgumentException $error) {
    trackingResponse(422, ['success' => false, 'message' => $error->getMessage()]);
} catch (DomainException $error) {
    trackingResponse($error->getCode() ?: 409, ['success' => false, 'message' => $error->getMessage()]);
} catch (Throwable $error) {
    error_log('Seguimiento conductor: ' . $error->getMessage());
    trackingResponse(500, ['success' => false, 'message' => 'No se pudo guardar o consultar el traslado. Revisar el SQL utilizado y el registro de errores de PHP.']);
}
