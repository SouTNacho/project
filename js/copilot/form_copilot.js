import formTools from "/js/library.js"
const { registerValidator } = formTools

const code = new URLSearchParams(window.location.search).get('code')
const form = document.querySelector("#copilot_form")
const title = document.querySelector('h2')
const is_update = code !== null && code.trim() !== ''

async function loadCopilot() {

    try {

        const response = await fetch(`/php/actions/copilot/get_copilot.php?code=${code}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el copiloto')
            return
        }

        const item = result.item

        speciality.value = item.especialidad

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const copilot_document = document.querySelector("#copilot_document")
const speciality = document.querySelector("#copilot_speciality")
const password = document.querySelector("#copilot_password")
const confirm_password = document.querySelector("#confirm_password")
const btn = document.querySelector("#copilot_btn")

const copilot_document_msg = document.querySelector("#copilot_document_msg")
const speciality_msg = document.querySelector("#copilot_speciality_msg")
const password_msg = document.querySelector("#copilot_password_msg")
const confirm_password_msg = document.querySelector("#confirm_password_msg")
// const btn_msg = document.querySelector("#copilot_btn_msg")
const inputs = [copilot_document, speciality, password, confirm_password]

if (is_update) {

    form.action = `/php/actions/copilot/copilot_update.php?code=${code}`
    title.textContent = 'Actualizar Copiloto'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadCopilot()
} else {

    form.action = '/php/actions/copilot/copilot_register.php'
    title.textContent = 'Registrar Copiloto'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


copilot_document.addEventListener('input', () => {

    if (copilot_document.value.trim() !== '') {

        return registerValidator.documentIdInput(copilot_document, copilot_document_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(copilot_document, copilot_document_msg)
    }
    
    return formTools.setInvalid(copilot_document, copilot_document_msg, 'Este campo es obligatorio')
})

speciality.addEventListener('change', () => {

    if (speciality.value.trim() !== '') {

        return registerValidator.selectsInput(speciality, speciality_msg)
    }

    if(is_update) {

        return formTools.setValid(speciality, speciality_msg)
    }

    return formTools.setInvalid(speciality, speciality_msg, 'Este campo es obligatorio')
})

password.addEventListener('input', () => {

    if (password.value.trim() !== '') {

        return registerValidator.passwordInput(password, password_msg)
    }

    if(is_update) {

        return formTools.setValid(password, password_msg)
    }

    return formTools.setInvalid(password, password_msg, 'Este campo es obligatorio')
})

confirm_password.addEventListener('input', () => {

    if (confirm_password.value.trim() !== '') {

        return registerValidator.passwordMatch(confirm_password, confirm_password_msg, password)
    }
    
    if (is_update) {

        return formTools.setValid(confirm_password, confirm_password_msg)
    }

    return formTools.setInvalid(confirm_password, confirm_password_msg, 'Este campo es obligatorio')
})

form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (is_update) {

        let hasChanges = false
        for (const input of inputs) {
            if (input.value.trim() !== '') {
                hasChanges = true
                break
            }
        }

        if (!hasChanges) {
            alert("Debe completar al menos un campo")
            return
        }
    }

    if (copilot_document.value.trim() === '') {

        if (!is_update) {

            alert("La cédula es obligatoria")
            return
        }
    } else {

        if (!registerValidator.documentIdInput(copilot_document, copilot_document_msg)) {
            alert("La cédula no es válida")
            return
        }
    }

    if (speciality.value.trim() === '') {

        if (!is_update) {

            alert("La especialidad es obligatoria")
            return
        }
    } else {

        if (!registerValidator.selectsInput(speciality, speciality_msg)) {

            alert("La especialidad no es válida")
            return
        }
    }

    if (password.value.trim() === '') {

        if (!is_update) {

            alert("La contraseña es obligatoria")
            return
        }
    } else {

        if (!registerValidator.passwordInput(password, password_msg)) {

            alert("La contraseña no es válida")
            return
        }
    }
    
    if (confirm_password.value.trim() === '') {

        if (!is_update) {

            alert("La validación de la contraseña es obligatoria")
            return
        }
    } else {

        if (!registerValidator.passwordMatch(confirm_password, confirm_password_msg, password)) {

            alert("Las contraseñas no coinciden")
            return
        }
    }

    form.submit()
})

lucide.createIcons()