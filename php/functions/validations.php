<?php

    function validateEmployeeCode($code) {
        return preg_match('/^[A-Z]{2}\d{8}$/', $code);
    }

    function validateEmail($email) {
        return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
    }

    function validateEmptyData($value) {
        return trim($value) === "";
    }

    function validatePassword($password) {
        return preg_match('/^(?=.*[A-Z]).{8,20}$/', $password);
    }

    function validatePhone($phone_number) {
        return preg_match('/^\+\d{8,18}$/', $phone_number);
    }

    function validateDocument($document) {
        return preg_match('/^\d{8}$/', $document);
    }

    function validateDoorNumber($door_number) {
        return preg_match('/^\d+[a-zA-Z0-9\s\/-]{0,19}$/', $door_number);
    }

    function validateName($name) {
        return preg_match('/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]{1,49}(?: [A-ZÁÉÍÓÚÑ][a-záéíóúñ]{1,49})*$/', $name);
    }

    function validateString($string) {
        return preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9.,_\/\s-]{4,50}$/u', $string);
    }

    function validateDate($date) {
        return preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date);
    }

    function authenticateEmployee($input_password, $hash_password) {
        return password_verify($input_password, $hash_password);
    }

    function validateLargeString($string) {
        return preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9.,_\/\s-]{4,150}$/u', $string);
    }

    function validateElementCode($code) {
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9\/-]{4,8}$/u', $code);
    }

    function validateAmbulanceRegistration($registration) {
        return preg_match('/^[A-Z]{3}\d{4}$/u', $registration);
    }

    function validateYear($year) {
        return preg_match('/^\d{4}$/u', $year);
    }

    function validateSampleCode($code) {
        return preg_match('/^[A-Z]{1}\d{7}$/u', $code);
    }

?>