<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/element/element_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/element/element_form.php?id=" . $id);
        exit();
    }

    function keepOldValue($newValue, $oldValue) {
        return trim($newValue) === "" ? $oldValue : $newValue;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $code = trim($_POST['element_code'] ?? '');
        $name = trim($_POST['element_name'] ?? '');
        $description = trim($_POST['element_description'] ?? '');

        // El tipo puede llegar como id (1 / 2) o como texto (Biológico / No Biológico).
        // El subtipo llega siempre como id (es el value de cada opción del select).
        $type_text = $_POST['element_type'] ?? '';
        $type_text = is_string($type_text) ? mb_strtolower(trim($type_text)) : '';

        $subtype_text = $_POST['element_subtype'] ?? '';
        $subtype_text = is_string($subtype_text) ? trim($subtype_text) : '';
        
        if ($id <= 0) {
            $_SESSION["errors"] = 'El ID es incorrecto';
            header("Location: /php/pages/element/element_form.php");
            exit();
        }

        if (!validateEmptyData($code)) {
            if (!validateElementCode($code)) {
                redirectionForError("El código ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($name)) {
            if (!validateString($name)) {
                redirectionForError("El nombre ingresado no es válido.", $id);
            }
        }

        // Tipo y subtipo van juntos: se cambian los dos o ninguno.
        // null significa "no se modifica, se mantiene el valor guardado".
        $type = null;
        $subtype = null;

        if (!validateEmptyData($type_text) || !validateEmptyData($subtype_text)) {

            if (validateEmptyData($type_text)) {
                redirectionForError("El tipo es obligatorio.", $id);
            }

            if (validateEmptyData($subtype_text)) {
                redirectionForError("El subtipo es obligatorio.", $id);
            }

            $type = match ($type_text) {
                '1', 'biológico', 'biologico' => 1,
                '2', 'no biológico', 'no biologico' => 2,
                default => null
            };

            if ($type === null) {
                redirectionForError("El tipo ingresado no es válido.", $id);
            }

            // filter_var devuelve false si no es un entero puro (evita que "5abc" pase como 5)
            $subtype = filter_var($subtype_text, FILTER_VALIDATE_INT);

            if ($subtype === false || $subtype <= 0) {
                redirectionForError("El subtipo ingresado no es válido.", $id);
            }
        }

        if (!validateEmptyData($description)) {
            if (!validateLargeString($description)) {
                redirectionForError("La descripción ingresada no es válida.", $id);
            }
        }

        $mysqli = connection_db();

        try {

            $element = findElementWithId($mysqli, $id);

            if(!$element) {
                redirectWithError($mysqli, "No existe este registro.", $id);
            }

            if ((int) $element['id_estado_elemento'] === 3) {
                redirectWithError($mysqli, "No se puede modificar un registro eliminado.", $id);
            }

            // Si se cambia el subtipo, tiene que existir y pertenecer al tipo elegido
            if ($subtype !== null) {

                $subtype_row = findSubtypeWithId($mysqli, $subtype);

                if (!$subtype_row) {
                    redirectWithError($mysqli, "El subtipo ingresado no existe.", $id);
                }

                if ((int) $subtype_row['tipo'] !== $type) {
                    redirectWithError($mysqli, "El subtipo ingresado no corresponde al tipo.", $id);
                }
            }

            if (!validateEmptyData($code)) {
                $element_code = findElementWithCode($mysqli, $code);

                if($element_code && (int) $element['id_elemento'] !== (int) $element_code['id_elemento']) {
                    redirectWithError($mysqli, "Este código ya existe.", $id);
                }
            }

            if (!validateEmptyData($name)) {

                $element_name = findElementWithName($mysqli, $name);

                if ($element_name && (int) $element['id_elemento'] !== (int) $element_name['id_elemento']) {
                    redirectWithError($mysqli, "El nombre ingresado ya está registrado.", $id);
                }
            }

            $code = keepOldValue($code, $element["codigo"]);
            $name = keepOldValue($name, $element["nombre"]);
            $type = $type ?? (int) $element["tipo"];
            $subtype = $subtype ?? (int) $element["subtipo"];
            $description = keepOldValue($description, $element["descripcion"]);

            updateElement($mysqli, $code, $name, $type, $subtype, $description, (int) $element['id_elemento']);

            $mysqli->close();
            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/element/element_form.php?id=" . $id);
            exit();
        
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR ESTE MENSAGE
            error_log("Error al actualizar: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] = "Error al actualizar.";
            header("Location: /php/pages/element/element_form.php?id=" . $id);
            exit();
        }
    }
?>