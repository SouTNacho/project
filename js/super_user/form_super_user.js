import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#super_user_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

async function loadSuperUser() {

    try {

        const response = await fetch(`/php/actions/super_user/get_super_user.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el Super Usuario')
            return
        }

        const item = result.item

        name.placeholder = item.nombre
        permissions[0].textContent = item.permisos

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const name = document.querySelector('#super_user_name')
const password = document.querySelector('#super_user_password')
const permissions = document.querySelector('#super_user_permissions')
const confirm_password = document.querySelector('#super_user_confirm_password')
const btn = document.querySelector("#super_user_btn")

const name_msg = document.querySelector('#super_user_name_msg')
const password_msg = document.querySelector('#super_user_password_msg')
const permissions_msg = document.querySelector('#super_user_permissions_msg')
const confirm_password_msg = document.querySelector('#super_user_confirm_password_msg')
//const btn_msg = document.querySelector("#super_user_btn_msg")
const inputs = [name, permissions, password, confirm_password]

if (is_update) {

    form.action = `/php/actions/super_user/super_user_update.php?id=${id}`
    title.textContent = 'Actualizar Admin'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadSuperUser()
} else {

    form.action = '/php/actions/super_user/super_user_register.php'
    title.textContent = 'Registrar Admin'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


name.addEventListener('input', () => {

    if (name.value.trim() !== '') {

        return registerValidator.stringsInput(name, name_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(name, name_msg)
    }
    
    return formTools.setInvalid(name, name_msg, 'Este campo es obligatorio')
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

    if(is_update) {

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

    if (name.value.trim() === '') {

        if (!is_update) {

            alert("El nombre es obligatorio")
            return
        }
    } else {

        if (!registerValidator.stringsInput(name, name_msg)) {
            alert("El nombre no es válido")
            return
        }
    }

    if (permissions.value.trim() === '') {

        if (!is_update) {

            alert("Los permisos son obligatorios")
            return
        }
    } else {

        if (!registerValidator.selectsInput(permissions, permissions_msg)) {

            alert("Los permisos no son válidos")
            return
        }
    }

    if (password.value.trim() === '') {

        if (confirm_password.value.trim() !== '') {         
            alert("Las contraseñas no coinciden") 
            return 
        }

        if (!is_update) {
            alert("La contraseña es obligatoria")
            return
        }
    } else {
        if (!registerValidator.passwordInput(password, password_msg)) {
            alert("La contraseña no es válida")
            return
        }
        
        if (confirm_password.value.trim() === '') {
            alert("Debe confirmar la contraseña")
            return
        }
            
        if (!registerValidator.passwordMatch( confirm_password, confirm_password_msg, password )) {
            alert("Las contraseñas no coinciden")
            return
        }
    }
    
    form.submit()
})

lucide.createIcons()