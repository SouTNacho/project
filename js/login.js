import formTools from "/js/library.js"
const { loginValidator } = formTools

const form = document.querySelector("#login_form")
const back_btn = document.querySelector("#back_btn")
const employee_id = document.querySelector("#employee_id")
const password_toggle = document.querySelector("#password_toggle")
const employee_password = document.querySelector("#employee_password")

const employee_id_msg = document.querySelector("#employee_id_msg")
const password_msg = document.querySelector("#employee_password_msg")

employee_id.addEventListener('input', () =>
    loginValidator.idEmployeeInput(employee_id, employee_id_msg))

employee_password.addEventListener('input', () =>
    loginValidator.emptyInput(employee_password, password_msg))

password_toggle.addEventListener("click", () => {

    const showPassword = employee_password.type === "password"
    employee_password.type = showPassword ? "text" : "password"

    password_toggle.querySelector("span").textContent =
        showPassword ? "visibility_off" : "visibility"

    password_toggle.setAttribute("aria-label",
        showPassword ? "Ocultar contraseña" : "Mostrar contraseña")
})

back_btn.addEventListener('click', () =>
    location.href = '/index.php')

form.addEventListener('submit', (event) => {
    event.preventDefault()

    if (!loginValidator.idEmployeeInput(employee_id, employee_id_msg) ||
        !loginValidator.emptyInput(employee_password, password_msg)) {

            window.BYPNotify("Debe completar todos los campos.");
            return;
        }
    
    form.submit()
})
