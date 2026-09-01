import formTools from "/js/library.js"
const { loginValidator } = formTools

const login_form = document.querySelector("#employee_login_form")
const employee_id = document.querySelector("#employee_id")
const employee_password = document.querySelector("#employee_password")

const employee_id_msg = document.querySelector("#employee_id_msg")
const password_msg = document.querySelector("#employee_password_msg")

employee_id.addEventListener('input', () =>
    loginValidator.idEmployeeInput(employee_id, employee_id_msg))

employee_password.addEventListener('input', () =>
    loginValidator.emptyInput(employee_password, password_msg))

login_form.addEventListener('submit', (event) => {
    event.preventDefault()

    if (!loginValidator.idEmployeeInput(employee_id, employee_id_msg) ||
        !loginValidator.emptyInput(employee_password, password_msg)) {

            alert("Debe completar todos los campos.")
            return
        }
    
    login_form.submit()
})
