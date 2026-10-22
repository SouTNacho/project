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
        alert('Para poder enviar el mensaje debe de leer y aceptar las políticas.')
        return
    }

    if (!registerValidator.namesInput(name, name_msg)) {
        alert('El nombre ingresado no es válido.')
        return
    }

    if (!registerValidator.emailInput(email, email_msg)) {
        alert('El email ingresado no es válido.')
        return
    }

    if (!registerValidator.messageInput(message, message_msg)) {
        alert('El mensaje ingresado no es válido.')
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
            alert(result.message || 'Ha ocurrido un error al enviar el mensaje, intente nuevamente.')
            return
        }

        alert(result.message || 'El envío del mensaje ha sido exitoso.')
        location.reload()
    } catch (error) {

        console.error("Error al enviar el mensaje de contacto:", error.message)
        alert("Ha ocurrido un error al enviar el mensaje, intente nuevamente.")
    } finally {

        button.disabled = false
        button.textContent = "Enviar mensaje"
    }
})

lucide.createIcons()