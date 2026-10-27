import formTools from "/js/library.js"
const { registerValidator } = formTools

const code = new URLSearchParams(window.location.search).get('code')
const form = document.querySelector("#administrative_form")
const title = document.querySelector('h2')
const is_update = code !== null && code.trim() !== ''

async function loadAdministrative() {

    try {

        const response = await fetch(`/php/actions/administrative/get_administrative.php?code=${code}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el administrativo')
            return
        }

        const item = result.item

        const other_response = await fetch(`/php/actions/employee/get_employee.php?id=${item.id_funcionario}`)
        const other_result = await other_response.json()

        if (!other_response.ok || !other_result.success) {

            console.error('Ha ocurrido un error en la petic. del conductor', result.message)
            return
        }

        administrative_document.placeholder = other_result.item.cedula
        permissions[0].textContent = item.permisos

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const administrative_document = document.querySelector("#administrative_document")
const permissions = document.querySelector("#administrative_permissions")
const password = document.querySelector("#administrative_password")
const confirm_password = document.querySelector("#confirm_password")
const btn = document.querySelector("#administrative_btn")

const administrative_document_msg = document.querySelector("#administrative_document_msg")
const permissions_msg = document.querySelector("#administrative_permissions_msg")
const password_msg = document.querySelector("#administrative_password_msg")
const confirm_password_msg = document.querySelector("#confirm_password_msg")
// const btn_msg = document.querySelector("#administrative_btn_msg")
const inputs = [administrative_document, permissions, password, confirm_password]

if (is_update) {

    form.action = `/php/actions/administrative/administrative_update.php?code=${code}`
    title.textContent = 'Actualizar Administrativo'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadAdministrative()
} else {

    form.action = '/php/actions/administrative/administrative_register.php'
    title.textContent = 'Registrar Administrativo'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


administrative_document.addEventListener('input', () => {

    if (administrative_document.value.trim() !== '') {

        return registerValidator.documentIdInput(administrative_document, administrative_document_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(administrative_document, administrative_document_msg)
    }
    
    return formTools.setInvalid(administrative_document, administrative_document_msg, 'Este campo es obligatorio')
})

permissions.addEventListener('change', () => {

    if (permissions.value.trim() !== '') {

        return registerValidator.selectsInput(permissions, permissions_msg)
    }

    if(is_update) {

        return formTools.setValid(permissions, permissions_msg)
    }

    return formTools.setInvalid(permissions, permissions_msg, 'Este campo es obligatorio')
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
            notificacion("Debe completar al menos un campo", "warning")
            return
        }
    }

    if (administrative_document.value.trim() === '') {

        if (!is_update) {

            notificacion("La cédula es obligatoria", "warning")
            return
        }
    } else {

        if (!registerValidator.documentIdInput(administrative_document, administrative_document_msg)) {
            notificacion("La cédula no es válida", "warning")
            return
        }
    }

    if (permissions.value.trim() === '') {

        if (!is_update) {

            notificacion("Los permisos son obligatorios", "warning")
            return
        }
    } else {

        if (!registerValidator.selectsInput(permissions, permissions_msg)) {

            notificacion("Los permisos no son válidos", "warning")
            return
        }
    }


    if (password.value.trim() === '') {

        if (!is_update) {

            notificacion("La contraseña es obligatoria", "warning")
            return
        }
    } else {

        if (!registerValidator.passwordInput(password, password_msg)) {

            notificacion("La contraseña no es válida", "warning")
            return
        }
    }
    
    if (confirm_password.value.trim() === '') {

        if (!is_update) {

            notificacion("La validación de la contraseña es obligatoria", "warning")
            return
        }
    } else {

        if (!registerValidator.passwordMatch(confirm_password, confirm_password_msg, password)) {

            notificacion("Las contraseñas no coinciden", "warning")
            return
        }
    }

    form.submit()
})

lucide.createIcons()
