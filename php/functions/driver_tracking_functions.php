<?php
require_once __DIR__ . '/../models/driver_tracking_model.php';

function validateDriverCoordinates($input) {
    $coords = [];
    foreach (['latitud' => 90, 'longitud' => 180] as $field => $limit) {
        $raw = $input[$field] ?? null;
        if (!is_scalar($raw) || is_bool($raw) || !is_numeric($raw)) {
            throw new InvalidArgumentException('Latitud y longitud deben ser números.');
        }
        $value = (float) $raw;
        if (!is_finite($value) || abs($value) > $limit) {
            throw new InvalidArgumentException('Las coordenadas están fuera de rango.');
        }
        $coords[] = $value;
    }
    return $coords;
}

function getDriverTracking($mysqli, $transfer_id, $driver_code, $lock = false) {
    $transfer = findDriverTransfer($mysqli, $transfer_id, $lock);
    if (!$transfer) throw new DomainException('No existe el traslado de prueba. Revisar su ID en la configuración.', 404);
    if ($transfer['codigo_conductor'] !== $driver_code) {
        throw new DomainException('El traslado de prueba no está asignado a este conductor.', 403);
    }
    return $transfer;
}

function processDriverTracking($mysqli, $transfer_id, $driver_code, $operation, $input) {
    if (!in_array($operation, ['iniciar', 'pausar', 'finalizar', 'ubicacion'], true)) {
        throw new InvalidArgumentException('Operación inválida.');
    }
    $coordinates = in_array($operation, ['iniciar', 'ubicacion'], true) ? validateDriverCoordinates($input) : null;
    $mysqli->begin_transaction();
    try {
        $transfer = getDriverTracking($mysqli, $transfer_id, $driver_code, true);
        $state = $transfer['estado'];
        if ($operation === 'ubicacion') {
            if ($state !== 'En curso') throw new DomainException('El traslado no está en curso.', 409);
        } elseif ($operation === 'iniciar') {
            if (!in_array($state, ['Pendiente', 'En pausa', 'En curso'], true)) {
                throw new DomainException('Este traslado ya finalizó.', 409);
            }
            if ($state !== 'En curso') {
                changeDriverTransferState($mysqli, $transfer_id, 'En curso', $state === 'En pausa' ? 'Traslado reanudado por el conductor.' : 'Traslado iniciado por el conductor.');
            }
        } elseif ($operation === 'pausar') {
            if (!in_array($state, ['En curso', 'En pausa'], true)) throw new DomainException('No se puede pausar este traslado.', 409);
            if ($state === 'En curso') changeDriverTransferState($mysqli, $transfer_id, 'En pausa', 'Traslado pausado por el conductor.');
        } else {
            if (!in_array($state, ['En curso', 'En pausa', 'Finalizado'], true)) throw new DomainException('Primero debe iniciar el traslado.', 409);
            if ($state !== 'Finalizado') changeDriverTransferState($mysqli, $transfer_id, 'Finalizado', 'Traslado finalizado por el conductor.');
        }
        if ($coordinates) saveDriverLocation($mysqli, $transfer_id, $coordinates[0], $coordinates[1]);
        $result = getDriverTracking($mysqli, $transfer_id, $driver_code);
        $result['ubicacion'] = findDriverLocation($mysqli, $transfer_id);
        $mysqli->commit();
        return $result;
    } catch (Throwable $error) {
        $mysqli->rollback();
        throw $error;
    }
}
