import formTools from "/js/library.js"
const { registerValidator } = formTools
const { loginValidator } = formTools


const update_form = document.querySelector("#super_user_update_form")

const update_btn = document.querySelector("#super_user_update_btn")
const update_btn_msg = document.querySelector("#super_user_update_btn_msg")

const code = document.querySelector('#super_user_code')
const code_msg = document.querySelector('#super_user_code_msg')

const name = document.querySelector('#super_user_name')
const name_msg = document.querySelector('#super_user_name_msg')

const permissions = document.querySelector('#super_user_permissions')
const permissions_msg = document.querySelector('#super_user_permissions_msg')

const password = document.querySelector('#super_user_password')
const password_msg = document.querySelector('#super_user_password_msg')

const confirm_password = document.querySelector('#super_user_confirm_password')
const confirm_password_msg = document.querySelector('#super_user_confirm_password_msg')


code.addEventListener('input', () =>
    loginValidator.idEmployeeInput(code, code_msg))

name.addEventListener('input', () => {

    if (name.value.trim() !== '') {
        registerValidator.stringsInput(name, name_msg)

    } else {
        return formTools.setValid(name, name_msg)

    }
})

permissions.addEventListener('change', () => {

    if (permissions.value.trim() !== '') {
        registerValidator.selectsInput(permissions, permissions_msg)

    } else {
        return formTools.setValid(permissions, permissions_msg)

    }
})

password.addEventListener('input', () => {

    if (password.value.trim() !== '') {
        registerValidator.passwordInput(password, password_msg)
        formTools.setInvalid(confirm_password, confirm_password_msg, 'Las contraseñas deben coincidir')
    }

    if (confirm_password.value.trim() === '' && password.value.trim() === '') {

        formTools.setValid(password, password_msg)
        return formTools.setValid(confirm_password, confirm_password_msg)
    }

})

confirm_password.addEventListener('input', () => {

    if (confirm_password.value.trim() === '' && password.value.trim() === '') {

        formTools.setValid(password, password_msg)
        return formTools.setValid(confirm_password, confirm_password_msg)
    }

    if (confirm_password.value.trim() !== '') {
        if (password.value.trim() === '') {
            
            formTools.setInvalid(confirm_password, confirm_password_msg, 'Debe ingresar la contraseña')
        } else {

            return registerValidator.passwordMatch(confirm_password, confirm_password_msg, password)
        }
    }
})

update_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!loginValidator.idEmployeeInput(code, code_msg)) {

        alert("El código es obligatorio")
        return
    }

    if (name.value.trim() !== "") {

        if (!registerValidator.stringsInput(name, name_msg)) {

            alert("El nombre debe ser válido o estar vacío")
            return
        }
    }

    if (permissions.value.trim() !== "") {

        if (!registerValidator.selectsInput(permissions, permissions_msg)) {

            alert("Los permisos deben ser válidos o estar vacíos")
            return
        }
    }

    if (password.value.trim() !== "" || confirm_password.value.trim() !== "") {

        if (!registerValidator.passwordMatch(confirm_password, confirm_password_msg, password)) {

            alert("Las contraseñas ingresadas no son válidas o no coinciden")
            return
        }
    }

    update_form.submit()
})