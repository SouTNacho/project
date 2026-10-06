import formTools from "/js/library.js"

const { loginValidator } = formTools

const login_form = document.querySelector("#employee_login_form")
const employee_id = document.querySelector("#employee_id")
const employee_password = document.querySelector("#employee_password")

const employee_id_msg = document.querySelector("#employee_id_msg")
const password_msg = document.querySelector("#employee_password_msg")

const password_toggle = document.querySelector("#password_toggle")

employee_id.addEventListener("input", () => {
    employee_id.value = employee_id.value.toUpperCase()

    loginValidator.idEmployeeInput(
        employee_id,
        employee_id_msg
    )
})

employee_password.addEventListener("input", () => {
    loginValidator.emptyInput(
        employee_password,
        password_msg
    )
})

password_toggle.addEventListener("click", () => {

    const showPassword = employee_password.type === "password"

    employee_password.type = showPassword
        ? "text"
        : "password"

    password_toggle.querySelector("span").textContent =
        showPassword
            ? "visibility_off"
            : "visibility"

    password_toggle.setAttribute(
        "aria-label",
        showPassword
            ? "Ocultar contraseña"
            : "Mostrar contraseña"
    )
})

login_form.addEventListener("submit", (event) => {

    event.preventDefault()

    const valid_id = loginValidator.idEmployeeInput(
        employee_id,
        employee_id_msg
    )

    const valid_password = loginValidator.emptyInput(
        employee_password,
        password_msg
    )

    if (!valid_id || !valid_password) {
        return
    }

    login_form.submit()
})
