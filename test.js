
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("successBtn").addEventListener("click", () => {
        notificacionExito("Funcionario registrado correctamente", {
            descripcion: "Los datos se guardaron con éxito."
        });
    });

    document.getElementById("errorBtn").addEventListener("click", () => {
        notificacionError("No se pudo guardar el registro", {
            descripcion: "Verificá los datos e intentá nuevamente."
        });
    });

    document.getElementById("warningBtn").addEventListener("click", () => {
        notificacionAdvertencia("Faltan campos obligatorios", {
            descripcion: "Completá los campos antes de continuar."
        });
    });

    document.getElementById("infoBtn").addEventListener("click", () => {
        notificacionInfo("No hay registros disponibles", {
            descripcion: "No se encontraron resultados para mostrar."
        });
    });

    document.getElementById("customBtn").addEventListener("click", () => {
        notificacionExito("¡Cambios guardados!", {
            descripcion: "Tu información se actualizó correctamente.",
            timer: 8000,
            background: "rgba(187, 247, 208, 0.88)",
            color: "#14532d",
            accent: "#15803d",
            border: "rgba(21, 128, 61, 0.6)"
        });
    });

    document.getElementById("confirmBtn").addEventListener("click", async () => {
        const aceptado = await confirmar(
            "Esta acción no se puede deshacer.",
            {
                title: "¿Eliminar funcionario?",
                icon: "warning",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
                confirmButtonColor: "#dc2626"
            }
        );

        if (aceptado) {
            notificacionExito("Confirmación aceptada");
        } else {
            notificacionInfo("Operación cancelada");
        }
    });

    document.getElementById("alertBtn").addEventListener("click", () => {
        alerta("No se pudo conectar con el servidor.", "error", {
            title: "Error de conexión",
            confirmButtonText: "Entendido",
            confirmButtonColor: "#dc2626"
        });
    });
});
