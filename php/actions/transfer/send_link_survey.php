<?php

session_start();
require_once __DIR__ . '/../../functions/session.php';

header("Content-Type: application/json");

validateActionSession('driver');

require_once __DIR__ . "/../../models/transfer_model.php";
require_once __DIR__ . "/../../models/patient_model.php";
require_once __DIR__ . "/../../conection.php";

$patient_id = (int) ($_GET['patient_id'] ?? '');

echo "SMTP: " . ini_get('SMTP') . "<br>" .  "Puerto: " . ini_get('smtp_port') . "<br>" . "Sendmail path: " . ini_get('sendmail_path') . "<br>" . "PHP.ini: " . php_ini_loaded_file();


if ($patient_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Error, los datos enviados son incorrectos.'
    ]);
    exit;
}

$mysqli = connection_db();

try {

    $patient = findPatientWithId($mysqli, $patient_id);

    if (!$patient) {
        echo json_encode([
            'success' => false,
            'message' => 'Error, no se encontró el paciente.'
        ]);

        $mysqli->close();
        exit;
    }

    $destinatario = "printonacho@gmail.com";
    $asunto = "Encuesta de satisfacción del traslado";

    $nombre = $patient['nombre'];

    $enlace = "http://localhost:3000/php/survey/transfer_survey.php";

    $mensaje = "
        Hola, $nombre.

        Te invitamos a participar en una encuesta de satisfacción
        sobre el traslado que recibiste.

        Accedé al siguiente enlace para completar la encuesta:

        $enlace

        ¡Gracias por tu tiempo y opinión!
    ";

    $cabeceras = "From: Hospital de Clínicas <printonacho@gmail.com>\r\n";
    $cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($destinatario, $asunto, $mensaje, $cabeceras)) {

        echo json_encode([
            'success' => true,
            'message' => 'El correo electrónico se ha enviado correctamente.'
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Error al enviar el correo electrónico.'
        ]);

    }

    $mysqli->close();
    exit;

} catch (mysqli_sql_exception $e) {

    $mysqli->close();

    echo json_encode([
        'success' => false,
        'message' => 'Ha ocurrido un error: ' . $e->getMessage()
    ]);

}

?>
