import formTools from "/js/library.js"

const { registerValidator } = formTools
const form = document.querySelector("#contact_form")

if (form) {
    const name = document.querySelector("#contact_name")
    const email = document.querySelector("#contact_email")
    const message = document.querySelector("#contact_message")
    const nameMsg = document.querySelector("#contact_name_msg")
    const emailMsg = document.querySelector("#contact_email_msg")
    const messageMsg = document.querySelector("#contact_message_msg")
    const button = document.querySelector("#contact_btn")
    const status = document.querySelector("#contact_status")

    name.addEventListener("input", () => registerValidator.namesInput(name, nameMsg))
    email.addEventListener("input", () => registerValidator.emailInput(email, emailMsg))
    message.addEventListener("input", () => registerValidator.contactMessageInput(message, messageMsg))

    form.addEventListener("submit", async (event) => {
        event.preventDefault()
        status.textContent = ""
        status.classList.remove("success", "error")

        const nameValid = registerValidator.namesInput(name, nameMsg)
        const emailValid = registerValidator.emailInput(email, emailMsg)
        const messageValid = registerValidator.contactMessageInput(message, messageMsg)

        if (!nameValid || !emailValid || !messageValid) {
            status.textContent = "Revisá los campos marcados antes de enviar."
            status.classList.add("error")
            if (!nameValid) name.focus()
            else if (!emailValid) email.focus()
            else message.focus()
            return
        }

        button.disabled = true
        button.textContent = "Enviando..."

        try {
            const response = await fetch(form.action, {
                method: "POST",
                body: new FormData(form),
                headers: { "Accept": "application/json" }
            })

            const responseText = await response.text()
            let result

            try {
                if (!responseText.trim()) {
                    throw new Error("El PHP devolvió una respuesta vacía.")
                }
                result = JSON.parse(responseText)
            } catch (parseError) {
                console.error("Respuesta inválida de send_message.php", {
                    httpStatus: response.status,
                    responseText
                })
                throw new Error(`El servidor no devolvió JSON válido (HTTP ${response.status}). Revisá el registro de errores de PHP/Apache.`)
            }

            if (!response.ok || !result.success) {
                if (result.field === "contact_name") registerValidator.namesInput(name, nameMsg)
                if (result.field === "contact_email") registerValidator.emailInput(email, emailMsg)
                if (result.field === "contact_message") registerValidator.contactMessageInput(message, messageMsg)
                throw new Error(result.message || `No se pudo enviar el mensaje (HTTP ${response.status}).`)
            }

            form.reset()
            for (const field of [name, email, message]) {
                field.classList.remove("valid", "invalid")
            }
            for (const fieldMessage of [nameMsg, emailMsg, messageMsg]) {
                fieldMessage.textContent = ""
            }

            status.textContent = result.message || "Tu mensaje se envió correctamente."
            status.classList.add("success")
        } catch (error) {
            console.error("Error al enviar el mensaje de contacto:", error)
            status.textContent = error.message || "No se pudo enviar el mensaje. Intentá nuevamente."
            status.classList.add("error")
        } finally {
            button.disabled = false
            button.textContent = "Enviar mensaje"
        }
    })
}
