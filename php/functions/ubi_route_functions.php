<?php

    require_once __DIR__ . "/../models/document_model.php";
    
    function createDocumentList($documents, $user_type) {

        foreach ($documents as $document) {

            if ($user_type !== "FA" && $document['id_estado_documento'] === 2) continue;
            
            echo "<li>";
            echo "<p> " . htmlspecialchars($document['nombre']) . "</p>";
            echo "<p>Fecha de Creación: " . htmlspecialchars($document['fecha_creacion']) . "</p>";
            echo "<button class='preview-button' data-token='" . htmlspecialchars($document['token']) . "'>Previsualizar</button>";
            echo "<button class='download-button' data-token='" . htmlspecialchars($document['token']) . "'>Descargar</button>";

            if ($user_type === "FA") {
            
                if ($document['id_estado_documento'] === 2) {

                    echo "<button class='change-state' data-token='" . htmlspecialchars($document['token']) . "'>Activar</button>";
                } else {

                    echo "<button class='change-state' data-token='" . htmlspecialchars($document['token']) . "'>Desactivar</button>";
                }
            
                echo "<button class='generate-qr-button' data-token='" . htmlspecialchars($document['token']) . "'>Generar QR</button>";
            }
            echo "</li>";
        }
    }

    function loadCategories($categories) {

        foreach ($categories as $category) {
            
            echo "<option value='" . htmlspecialchars($category['id_categoria']) . "'>" . htmlspecialchars($category['nombre']) . "</option>";
        }

    }

?>