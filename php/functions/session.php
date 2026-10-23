<?php

function isLoggedIn() {
    if (($_SESSION['logged'] ?? false) !== true) return false;
    $current = $_SESSION['type_user'] ?? null;
    $legacy = $_SESSION['user_type'] ?? null;
    if ($current && $legacy && $current !== $legacy) return false;
    $role = $current ?: $legacy;
    if (!in_array($role, ['administrative', 'super_user', 'driver', 'copilot'], true)) return false;

    // Compatibilidad con sesiones anteriores a la unificación de nombres.
    $_SESSION['type_user'] = $role;
    $_SESSION['user_type'] = $role;
    $code = $_SESSION['employee_code'] ?? $_SESSION['codigo'] ?? null;
    if (is_string($code) && $code !== '') {
        $_SESSION['employee_code'] = $code;
        $_SESSION['codigo'] = $code;
    }
    return true;
}

function loggedUserHome() {
    switch ($_SESSION['type_user'] ?? '') {
        case 'administrative': return '/php/pages/administrative_pages/administrative_panel.php';
        case 'super_user': return '/php/pages/super_user_pages/super_user_panel.php';
        // El ZIP no incluye un panel propio de conductor o copiloto.
        default: return '/index.php';
    }
}

function redirectLoggedUser() {
    if (!isLoggedIn()) return;
    header('Location: ' . loggedUserHome());
    exit();
}

function validateSession($user_types) {
    if (!isLoggedIn()) {
        header('Location: /php/pages/login.php');
        exit();
    }
    if (!in_array($_SESSION['type_user'], (array) $user_types, true)) {
        http_response_code(403);
        exit('No tiene permisos para acceder a esta página.');
    }
    return true;
}

function validateActionSession($user_types, ...$additional_user_types) {
    header('Content-Type: application/json; charset=UTF-8');
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión.']);
        exit();
    }
    $allowed = (array) $user_types;
    foreach ($additional_user_types as $types) $allowed = array_merge($allowed, (array) $types);
    if (!in_array($_SESSION['type_user'], $allowed, true)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No tiene permisos para realizar esta acción.']);
        exit();
    }
}

function loggedEmployeeCode() {
    return isLoggedIn() ? ($_SESSION['employee_code'] ?? '') : '';
}
