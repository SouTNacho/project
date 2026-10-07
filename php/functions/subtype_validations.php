<?php
const SUBTYPE_NAME_MAX_LENGTH = 80;
 
    // Saca espacios de los extremos y colapsa espacios repetidos.
    function normalizeSubtypeName($name) {
        return preg_replace('/\s+/u', ' ', trim($name)) ?? '';
    }
 
    // Devuelve el mensaje de error o null si el nombre es válido.
    function validateSubtypeName($name) {
 
        if ($name === '') {
            return "El nombre es obligatorio.";
        }
 
        if (mb_strlen($name) > SUBTYPE_NAME_MAX_LENGTH) {
            return "El nombre no puede superar los " . SUBTYPE_NAME_MAX_LENGTH . " caracteres.";
        }
 
        if (!preg_match('/^[\p{L}\p{N} .,\-()\/&]+$/u', $name)) {
            return "El nombre contiene caracteres no permitidos.";
        }
 
        return null;
    }
?>