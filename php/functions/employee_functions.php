<?php
require_once __DIR__ . '/../models/employee_model.php';
function getEmployeeType($employee_code) {
    return strtoupper(substr(trim($employee_code), 0, 2));
}
