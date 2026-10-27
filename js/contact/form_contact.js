import formTools from "/js/library.js"
const { registerValidator } = formTools

const form = document.querySelector("#contact_form")
const name = document.querySelector("#contact_name")
const email = document.querySelector("#contact_email")
const message = document.querySelector("#contact_message")

const name_msg = document.querySelector("#contact_name_msg")
const email_msg = document.querySelector("#contact_email_msg")
const message_msg = document.querySelector("#contact_message_msg")

const button = document.querySelector("#contact_btn")
const status = document.querySelector("#contact_status")
const checkbox_btn = document.querySelector("#checkbox_btn")
const dialog = document.querySelector('#contact_terms_dialog')
const close_btn = document.querySelector('#close_contact_terms')

name.addEventListener("input", () =>
    registerValidator.namesInput(name, name_msg))

email.addEventListener("input", () =>
    registerValidator.emailInput(email, email_msg))

message.addEventListener("input", () =>
    registerValidator.messageInput(message, message_msg))

checkbox_btn.addEventListener('click', () => dialog.showModal())
close_btn.addEventListener('click', () => dialog.close())

form.addEventListener("submit", async (event) => {
    event.preventDefault()

    if (!status.checked) {
        notificacion('Para poder enviar el mensaje debe de leer y aceptar las políticas.', "warning")
        return
    }

    if (!registerValidator.namesInput(name, name_msg)) {
        notificacion('El nombre ingresado no es válido.', "error")
        return
    }

    if (!registerValidator.emailInput(email, email_msg)) {
        notificacion('El email ingresado no es válido.', "error")
        return
    }

    if (!registerValidator.messageInput(message, message_msg)) {
        notificacion('El mensaje ingresado no es válido.', "error")
        return
    }

    try {

        button.disabled = true
        button.textContent = "Enviando..."

        const response = await fetch('/php/actions/contact/send_message.php', {
            method: "POST",
            body: new FormData(form)
        })

        const result = await response.json()

        if (!response.ok || !result.success) {
            notificacion(result.message || 'Ha ocurrido un error al enviar el mensaje, intente nuevamente.', "error")
            return
        }

        notificacion(result.message || 'El envío del mensaje ha sido exitoso.', "success")
        location.reload()
    } catch (error) {

        console.error("Error al enviar el mensaje de contacto:", error.message)
        notificacion("Ha ocurrido un error al enviar el mensaje, intente nuevamente.", "error")
    } finally {

        button.disabled = false
        button.textContent = "Enviar mensaje"
    }
})

lucide.createIcons()
