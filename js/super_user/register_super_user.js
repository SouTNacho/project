import formTools from "/js/library.js"
const { registerValidator } = formTools

const register_form = document.querySelector("#super_user_register_form")

const register_btn = document.querySelector("#super_user_register_btn")
const register_btn_msg = document.querySelector("#super_user_register_btn_msg")

const name = document.querySelector('#super_user_name')
const name_msg = document.querySelector('#super_user_name_msg')

const permissions = document.querySelector('#super_user_permissions')
const permissions_msg = document.querySelector('#super_user_permissions_msg')

const password = document.querySelector('#super_user_password')
const password_msg = document.querySelector('#super_user_password_msg')

const confirm_password = document.querySelector('#super_user_confirm_password')
const confirm_password_msg = document.querySelector('#super_user_confirm_password_msg')

name.addEventListener('input', () =>
    registerValidator.stringsInput(name, name_msg))

permissions.addEventListener('change', () =>
    registerValidator.selectsInput(permissions, permissions_msg))

password.addEventListener('input', () =>
    registerValidator.passwordInput(password, password_msg))

confirm_password.addEventListener('input', () =>
    registerValidator.passwordMatch(confirm_password, confirm_password_msg, password))

register_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.stringsInput(name, name_msg) ||
        !registerValidator.selectsInput(permissions, permissions_msg) ||
        !registerValidator.passwordInput(password, password_msg) ||
        !registerValidator.passwordMatch(confirm_password, confirm_password_msg, password)) {

        alert("Debe completar todos los campos")
        return
    }

    register_form.submit()
})