<?php

    require_once __DIR__ . "/../models/document_model.php";
    
    function createDocumentList($documents, $user_type) {

        foreach ($documents as $document) {

            $name = htmlspecialchars($document['nombre']);
            $date = htmlspecialchars($document['fecha_creacion']);
            $token = htmlspecialchars($document['token']);
            $state = (int) $document['id_estado_documento'];

            if ($user_type !== "FA") {

                if ($state !== 1) continue;

                echo "<li>";

                echo "<p> " . $name . "</p>";
                echo "<p>Fecha de Creación: " . $date . "</p>";

                echo "<button class='preview-button' data-token='" . $token . "'>
                        Previsualizar
                    </button>";

                echo "<button class='download-button' data-token='" . $token . "'>
                        Descargar
                    </button>";

                echo "</li>";
                
                continue;
            }

            echo "<li>";

            echo "<p> " . $name . "</p>";
            echo "<p>Fecha de Creación: " . $date . "</p>";
            
            if ($state === 3) {

                echo "<p>Estado de Documento: " . 'Eliminado' . "</p>";
            } else {

                echo "<button class='preview-button' data-token='" . $token . "'>
                        Previsualizar
                    </button>";

                echo "<button class='download-button' data-token='" . $token . "'>
                        Descargar
                    </button>";

                echo "<button class='generate-qr-button' data-token='" . $token . "'>
                        Generar QR
                    </button>";

                echo "<button class='delete-button' data-token='" . $token . "'>
                        Eliminar
                    </button>";

                if ($state === 2) {

                    echo "<button class='change-state' data-token='" . $token . "'>
                            Activar
                        </button>";

                }
                
                if ($state === 1) {

                    echo "<button class='change-state' data-token='" . $token . "'>
                            Desactivar
                        </button>";
                }

            }

            echo "</li>";
        }
    }

    function loadCategories($categories) {

        foreach ($categories as $category) {

            $id = htmlspecialchars($category['id_categoria']);
            $category_name= htmlspecialchars($category['nombre']);
            
            echo "<option value='" . $id . "'>" . $category_name . "</option>";
        }

    }

?>