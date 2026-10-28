
const BYP_THEMES = {
    success: {
        background: "rgba(220, 252, 231, 0.94)",
        color: "#166534",
        accent: "#16a34a",
        border: "rgba(22, 163, 74, 0.4)",
        timer: 4000
    },
    error: {
        background: "rgba(255, 228, 230, 0.95)",
        color: "#9f1239",
        accent: "#e11d48",
        border: "rgba(225, 29, 72, 0.4)",
        timer: 6000
    },
    warning: {
        background: "rgba(254, 243, 199, 0.95)",
        color: "#92400e",
        accent: "#d97706",
        border: "rgba(217, 119, 6, 0.4)",
        timer: 5000
    },
    info: {
        background: "rgba(219, 234, 254, 0.95)",
        color: "#1e40af",
        accent: "#2563eb",
        border: "rgba(37, 99, 235, 0.4)",
        timer: 4500
    }
};

function notificacion(mensaje, tipo = "info", opciones = {}) {
    const aviso = JSON.stringify({mensaje: String(mensaje), tipo, fecha: Date.now()});
    try { sessionStorage.setItem('byp-notice', aviso); } catch (_) {}

    const tema = BYP_THEMES[tipo] || BYP_THEMES.info;

    const {
        timer = tema.timer,
        descripcion,
        background = tema.background,
        color = tema.color,
        accent = tema.accent,
        border = tema.border,
        ...otrasOpciones
    } = opciones;

    const didOpenOriginal = otrasOpciones.didOpen;
    delete otrasOpciones.didOpen;

    const clases = {
        ...(otrasOpciones.customClass || {}),
        popup: [
            "byp-toast",
            `byp-toast-${tipo}`,
            otrasOpciones.customClass?.popup || ""
        ].filter(Boolean).join(" ")
    };

    return Swal.fire({
        toast: true,
        position: "top-end",
        icon: tipo,
        titleText: String(mensaje),
        text: descripcion || undefined,

        showConfirmButton: false,
        showCloseButton: true,

        timer,
        timerProgressBar: true,

        background,
        color,
        customClass: clases,

        ...otrasOpciones,

        didOpen: (toast) => {
            toast.style.setProperty("--toast-bg", background);
            toast.style.setProperty("--toast-text", color);
            toast.style.setProperty("--toast-accent", accent);
            toast.style.setProperty("--toast-border", border);

            if (typeof didOpenOriginal === "function") {
                didOpenOriginal(toast);
            }
        }
    }).then((resultado) => {
        try { if (sessionStorage.getItem('byp-notice') === aviso) sessionStorage.removeItem('byp-notice'); } catch (_) {}
        return resultado;
    });
}

function notificacionExito(mensaje, opciones = {}) {
    return notificacion(mensaje, "success", opciones);
}

function notificacionError(mensaje, opciones = {}) {
    return notificacion(mensaje, "error", opciones);
}

function notificacionAdvertencia(mensaje, opciones = {}) {
    return notificacion(mensaje, "warning", opciones);
}

function notificacionInfo(mensaje, opciones = {}) {
    return notificacion(mensaje, "info", opciones);
}

async function confirmar(mensaje, opciones = {}) {
    const resultado = await Swal.fire({
        title: "¿Estás seguro?",
        text: mensaje,
        icon: "question",

        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",

        confirmButtonColor: "#2e53b8",
        cancelButtonColor: "#64748b",

        background: "#ffffff",
        color: "#263247",

        customClass: {
            popup: "byp-modal"
        },

        reverseButtons: true,
        focusCancel: true,

        ...opciones,

        customClass: {
            popup: "byp-modal",
            ...(opciones.customClass || {})
        }
    });

    return resultado.isConfirmed;
}

function alerta(mensaje, tipo = "info", opciones = {}) {
    return Swal.fire({
        title: "Aviso",
        text: mensaje,
        icon: tipo,

        confirmButtonText: "Aceptar",
        confirmButtonColor: "#2e53b8",

        background: "#ffffff",
        color: "#263247",

        customClass: {
            popup: "byp-modal"
        },

        ...opciones,

        customClass: {
            popup: "byp-modal",
            ...(opciones.customClass || {})
        }
    });
}


// Recuperar el aviso si la operación navegó inmediatamente a otra página.
document.addEventListener('DOMContentLoaded', () => {
    try {
        const aviso = JSON.parse(sessionStorage.getItem('byp-notice'));
        sessionStorage.removeItem('byp-notice');
        if (aviso && Date.now() - aviso.fecha < 15000) notificacion(aviso.mensaje, aviso.tipo);
    } catch (_) {}
});

// Resultados PHP: usar las mismas notificaciones que en los formularios JS.
document.addEventListener('DOMContentLoaded', async () => {
    if (!window.Swal) return;
    for (const element of document.querySelectorAll('.success-message, .error-message, .info-message')) {
        const message = element.textContent.trim();
        if (!message) continue;
        element.hidden = true;
        await notificacion(message, element.classList.contains('error-message') ? 'error' : element.classList.contains('info-message') ? 'info' : 'success');
    }
});
