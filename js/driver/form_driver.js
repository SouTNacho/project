import formTools from "/js/library.js"
const { registerValidator } = formTools

const code = new URLSearchParams(window.location.search).get('code')
const form = document.querySelector("#driver_form")
const title = document.querySelector('h2')
const is_update = code !== null && code.trim() !== ''

async function loadDriver() {

    try {

        const response = await fetch(`/php/actions/driver/get_driver.php?code=${code}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el conductor')
            return
        }

        const item = result.item

        expiration.value = item.vencimiento_carnet
        category.value = item.categoria_carnet

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const driver_document = document.querySelector("#driver_document")
const expiration = document.querySelector("#driver_license_expiration")
const category = document.querySelector("#driver_license_category")
const password = document.querySelector("#driver_password")
const confirm_password = document.querySelector("#confirm_password")
const btn = document.querySelector("#driver_btn")

const driver_document_msg = document.querySelector("#driver_document_msg")
const expiration_msg = document.querySelector("#driver_license_expiration_msg")
const category_msg = document.querySelector("#driver_license_category_msg")
const password_msg = document.querySelector("#driver_password_msg")
const confirm_password_msg = document.querySelector("#confirm_password_msg")
// const btn_msg = document.querySelector("#driver_btn_msg")
const inputs = [driver_document, expiration, category, password, confirm_password]

if (is_update) {

    form.action = `/php/actions/driver/driver_update.php?code=${code}`
    title.textContent = 'Actualizar Conductor'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadDriver()
} else {

    form.action = '/php/actions/driver/driver_register.php'
    title.textContent = 'Registrar Conductor'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


driver_document.addEventListener('input', () => {

    if (driver_document.value.trim() !== '') {

        return registerValidator.documentIdInput(driver_document, driver_document_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(driver_document, driver_document_msg)
    }
    
    return formTools.setInvalid(driver_document, driver_document_msg, 'Este campo es obligatorio')
})

expiration.addEventListener('change', () => {

    if (expiration.value.trim() !== '') {

        return registerValidator.selectsInput(expiration, expiration_msg)
    }

    if(is_update) {

        return formTools.setValid(expiration, expiration_msg)
    }

    return formTools.setInvalid(expiration, expiration_msg, 'Este campo es obligatorio')
})

category.addEventListener('change', () => {

    if (category.value.trim() !== '') {

        return registerValidator.selectsInput(category, category_msg)
    }

    if(is_update) {

        return formTools.setValid(category, category_msg)
    }

    return formTools.setInvalid(category, category_msg, 'Este campo es obligatorio')
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

    if (driver_document.value.trim() === '') {

        if (!is_update) {

            alert("La cédula es obligatoria")
            return
        }
    } else {

        if (!registerValidator.documentIdInput(driver_document, driver_document_msg)) {
            alert("La cédula no es válida")
            return
        }
    }

    if (expiration.value.trim() === '') {

        if (!is_update) {

            alert("La fecha de vencimiento es obligatoria")
            return
        }
    } else {

        if (!registerValidator.selectsInput(expiration, expiration_msg)) {

            alert("La fecha de vencimiento no es válida")
            return
        }
    }

    if (category.value.trim() === '') {

        if (!is_update) {

            alert("La categoría de la licencia es obligatoria")
            return
        }
    } else {

        if (!registerValidator.selectsInput(category, category_msg)) {

            alert("La categoría de la licencia no es válida")
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