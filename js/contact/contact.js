// Validadores locales: el formulario público no depende de librerías externas.
const registerValidator = {
    mark(item, container, valid, error) {
        item.classList.toggle("valid", valid)
        item.classList.toggle("invalid", !valid)
        container.textContent = valid ? "" : error
        return valid
    },
    namesInput(item, container) {
        const valid = /^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]{1,49}(?: [A-ZÁÉÍÓÚÑ][a-záéíóúñ]{1,49})*$/.test(item.value.trim())
        return this.mark(item, container, valid, "Ingresá el nombre con iniciales mayúsculas (por ejemplo, Juan Pérez).")
    },
    emailInput(item, container) {
        const valid = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(item.value.trim())
        return this.mark(item, container, valid, "Ingresá una dirección de correo válida.")
    },
    contactMessageInput(item, container) {
        const value = item.value.trim()
        const valid = value.length >= 10 && value.length <= 2000 && !/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/.test(value)
        return this.mark(item, container, valid, "El mensaje debe contener entre 10 y 2000 caracteres.")
    }
}
const form = document.querySelector("#contact_form")

if (form) {
    const name = document.querySelector("#contact_name")
    const email = document.querySelector("#contact_email")
    const message = document.querySelector("#contact_message")
    const nameMsg = document.querySelector("#contact_name_msg")
    const emailMsg = document.querySelector("#contact_email_msg")
    const messageMsg = document.querySelector("#contact_message_msg")
    const consent = document.querySelector("#contact_consent")
    const consentMsg = document.querySelector("#contact_consent_msg")
    const button = document.querySelector("#contact_btn")
    const status = document.querySelector("#contact_status")

    name.addEventListener("input", () => registerValidator.namesInput(name, nameMsg))
    email.addEventListener("input", () => registerValidator.emailInput(email, emailMsg))
    message.addEventListener("input", () => registerValidator.contactMessageInput(message, messageMsg))

    const termsDialog = document.querySelector("#contact_terms_dialog")
    const showTerms = document.querySelector("#show_contact_terms")
    const closeTerms = document.querySelector("#close_contact_terms")

    if (termsDialog && showTerms && closeTerms) {
        showTerms.addEventListener("click", () => {
            if (typeof termsDialog.showModal === "function") termsDialog.showModal()
            else termsDialog.setAttribute("open", "")
        })
        closeTerms.addEventListener("click", () => {
            if (typeof termsDialog.close === "function") termsDialog.close()
            else termsDialog.removeAttribute("open")
            showTerms.focus()
        })
        termsDialog.addEventListener("click", (event) => {
            if (event.target === termsDialog && typeof termsDialog.close === "function") termsDialog.close()
        })
    }

    consent.addEventListener("change", () => { consentMsg.textContent = "" })

    form.addEventListener("submit", async (event) => {
        event.preventDefault()
        status.textContent = ""
        status.classList.remove("success", "error")

        const nameValid = registerValidator.namesInput(name, nameMsg)
        const emailValid = registerValidator.emailInput(email, emailMsg)
        const messageValid = registerValidator.contactMessageInput(message, messageMsg)

        const consentValid = consent.checked
        consentMsg.textContent = consentValid ? "" : "Debés aceptar los términos y condiciones para enviar el mensaje."

        if (!nameValid || !emailValid || !messageValid || !consentValid) {
            status.textContent = "Revisá los campos marcados antes de enviar."
            status.classList.add("error")
            if (!nameValid) name.focus()
            else if (!emailValid) email.focus()
            else if (!messageValid) message.focus()
            else consent.focus()
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
                if (result.field === "contact_consent") consentMsg.textContent = result.message
                throw new Error(result.message || `No se pudo enviar el mensaje (HTTP ${response.status}).`)
            }

            form.reset()
            consentMsg.textContent = ""
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
