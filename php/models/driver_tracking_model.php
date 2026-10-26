<?php

function findDriverTransfer($mysqli, $transfer_id, $lock = false) {
    $sql = 'SELECT t.id_traslado, t.codigo_conductor, t.id_estado_traslado, e.nombre AS estado
            FROM traslado t JOIN estado_traslado e ON e.id_estado_traslado = t.id_estado_traslado
            WHERE t.id_traslado = ?' . ($lock ? ' FOR UPDATE' : '');
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $transfer_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function findDriverLocation($mysqli, $transfer_id) {
    $stmt = $mysqli->prepare('SELECT latitud, longitud, fecha_hora FROM ubicacion_traslado
        WHERE id_traslado = ? ORDER BY id_ubicacion_traslado DESC LIMIT 1');
    $stmt->bind_param('i', $transfer_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function saveDriverLocation($mysqli, $transfer_id, $latitude, $longitude) {
    // Se conserva una ubicación actual. El bloqueo del traslado evita altas simultáneas.
    $stmt = $mysqli->prepare('SELECT id_ubicacion_traslado FROM ubicacion_traslado
        WHERE id_traslado = ? ORDER BY id_ubicacion_traslado DESC LIMIT 1');
    $stmt->bind_param('i', $transfer_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id_ubicacion_traslado'];
        $stmt = $mysqli->prepare('UPDATE ubicacion_traslado SET latitud = ?, longitud = ?,
            fecha_hora = CURRENT_TIMESTAMP WHERE id_ubicacion_traslado = ?');
        $stmt->bind_param('ddi', $latitude, $longitude, $id);
    } else {
        $stmt = $mysqli->prepare('INSERT INTO ubicacion_traslado (latitud, longitud, id_traslado) VALUES (?, ?, ?)');
        $stmt->bind_param('ddi', $latitude, $longitude, $transfer_id);
    }
    $stmt->execute();
}

function changeDriverTransferState($mysqli, $transfer_id, $state_name, $description) {
    $stmt = $mysqli->prepare('SELECT id_estado_traslado FROM estado_traslado WHERE nombre = ?');
    $stmt->bind_param('s', $state_name);
    $stmt->execute();
    $state = $stmt->get_result()->fetch_assoc();
    if (!$state) throw new RuntimeException('Falta el estado requerido en estado_traslado.');
    $state_id = $state['id_estado_traslado'];
    $sql = 'UPDATE traslado SET id_estado_traslado = ?';
    if ($state_name === 'En curso') $sql .= ', hora_inicio_real = COALESCE(hora_inicio_real, CURRENT_TIMESTAMP)';
    if ($state_name === 'Finalizado') $sql .= ', hora_fin_real = CURRENT_TIMESTAMP';
    $stmt = $mysqli->prepare($sql . ' WHERE id_traslado = ?');
    $stmt->bind_param('ii', $state_id, $transfer_id);
    $stmt->execute();
    $stmt = $mysqli->prepare('INSERT INTO historial_traslado (descripcion, id_traslado, id_estado_traslado) VALUES (?, ?, ?)');
    $stmt->bind_param('sii', $description, $transfer_id, $state_id);
    $stmt->execute();
}
