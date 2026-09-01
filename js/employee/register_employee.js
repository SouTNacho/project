import formTools from "/js/library.js"
const { registerValidator } = formTools

const register_form = document.querySelector("#employee_register_form")
const dinamic_container = document.querySelector("#employee_extra_information")
const other_locality_container = document.querySelector("#other_locality_container")

const first_name = document.querySelector("#employee_first_name")
const last_name = document.querySelector("#employee_last_name")
const employee_document = document.querySelector("#employee_document")
const nationality = document.querySelector("#employee_nationality")
const birthdate = document.querySelector("#employee_birthdate")
const department = document.querySelector("#employee_department")
const locality = document.querySelector("#employee_locality")
const other_locality = document.querySelector("#other_locality")
const address = document.querySelector("#employee_address")
const address_number = document.querySelector("#employee_address_number")
const email = document.querySelector("#employee_email")
const cellphone_code = document.querySelector("#employee_cellphone_code")
const cellphone_number = document.querySelector("#employee_cellphone_number")
const position = document.querySelector("#employee_position")
const entry_date = document.querySelector("#employee_entry_date")
const password = document.querySelector("#employee_password")
const confirm_password = document.querySelector("#employee_confirm_password")
const register_btn = document.querySelector("#employee_register_btn")

const first_name_msg = document.querySelector("#employee_first_name_msg")
const last_name_msg = document.querySelector("#employee_last_name_msg")
const employee_document_msg = document.querySelector("#employee_document_msg")
const nationality_msg = document.querySelector("#employee_nationality_msg")
const birthdate_msg = document.querySelector("#employee_birthdate_msg")
const department_msg = document.querySelector("#employee_department_msg")
const locality_msg = document.querySelector("#employee_locality_msg")
const other_locality_msg = document.querySelector("#other_locality_msg")
const address_msg = document.querySelector("#employee_address_msg")
const address_number_msg = document.querySelector("#employee_address_number_msg")
const email_msg = document.querySelector("#employee_email_msg")
const cellphone_code_msg = document.querySelector("#employee_cellphone_number_msg")
const cellphone_number_msg = document.querySelector("#employee_cellphone_number_msg")
const position_msg = document.querySelector("#employee_position_msg")
const entry_date_msg = document.querySelector("#employee_entry_date_msg")
const password_msg = document.querySelector("#employee_password_msg")
const confirm_password_msg = document.querySelector("#employee_confirm_password_msg")
const register_btn_msg = document.querySelector("#employee_register_btn_msg")

let speciality
let speciality_msg
let license_category
let license_category_msg
let license_expiration
let license_expiration_msg
let permissions
let permissions_msg

formTools.loadNationalitiesSelect(nationality)
formTools.loadDepartmentsSelect(department)
formTools.loadPhoneCodesSelect(cellphone_code)

first_name.addEventListener('input', () => registerValidator.namesInput(first_name, first_name_msg))
last_name.addEventListener('input', () => registerValidator.namesInput(last_name, last_name_msg))
employee_document.addEventListener('input', () => registerValidator.documentIdInput(employee_document, employee_document_msg))
nationality.addEventListener('change', () => registerValidator.selectsInput(nationality, nationality_msg))
birthdate.addEventListener('input', () => registerValidator.dateInput(birthdate, birthdate_msg))
locality.addEventListener('change', () => registerValidator.localitySelect(locality, locality_msg, other_locality, other_locality_container))
other_locality.addEventListener('input', () => registerValidator.stringsInput(other_locality, other_locality_msg))
address.addEventListener('input', () => registerValidator.stringsInput(address, address_msg))
address_number.addEventListener('input', () => registerValidator.doorNumberInput(address_number, address_number_msg))
email.addEventListener('input', () => registerValidator.emailInput(email, email_msg))
cellphone_code.addEventListener('change', () => registerValidator.selectsInput(cellphone_code, cellphone_code_msg))
cellphone_number.addEventListener('input', () => registerValidator.phoneNumberInput(cellphone_number, cellphone_number_msg))
entry_date.addEventListener('input', () => registerValidator.dateInput(entry_date, entry_date_msg))
password.addEventListener('input', () => registerValidator.passwordInput(password, password_msg))
confirm_password.addEventListener('input', () => registerValidator.passwordMatch(confirm_password, confirm_password_msg, password))

department.addEventListener('change', () => { 

    formTools.loadLocationsSelect(locality, department)
    registerValidator.selectsInput(department, department_msg)
    registerValidator.selectsInput(locality, locality_msg)
})

position.addEventListener('change', async() => { 
    const value = position.value.trim()
    registerValidator.selectsInput(position, position_msg)

    if (value === "") dinamic_container.innerHTML = ""

    if (value === "FA") {

        try {

            const file = await fetch("/pages/administrator_specialist.html")
            dinamic_container.innerHTML = await file.text()

            permissions = document.querySelector("#employee_permissions")
            permissions_msg = document.querySelector("#employee_permissions_msg")

            permissions.addEventListener('change', () => 
                registerValidator.selectsInput(permissions, permissions_msg))
        } catch(error) {

            alert("Ha ocurrido un error, intente seleccionar el cargo nuevamente")
            console.error("Error:", error.message)
        }
    }

    if (value === "CO") {

        try {

            const file = await fetch("/pages/copilot_specialist.html")
            dinamic_container.innerHTML = await file.text()

            speciality = document.querySelector("#employee_speciality")
            speciality_msg = document.querySelector("#employee_speciality_msg")

            speciality.addEventListener('change', () => 
                registerValidator.selectsInput(speciality, speciality_msg))
        } catch(error) {

            alert("Ha ocurrido un error, intente seleccionar el cargo nuevamente")
            console.error("Error:", error.message)
        }
    }

    if (value === "DR") {

        try {

            const file = await fetch("/pages/driver_specialist.html")
            dinamic_container.innerHTML = await file.text()

            license_expiration = document.querySelector("#employee_license_expiration")
            license_category = document.querySelector("#employee_license_category")
            license_expiration_msg = document.querySelector("#employee_license_expiration_msg")
            license_category_msg = document.querySelector("#employee_license_category_msg")

            license_expiration.addEventListener('change', () => 
                registerValidator.selectsInput(license_expiration, license_category_msg))
            license_category.addEventListener('change', () => 
                registerValidator.selectsInput(license_category, license_category_msg))
        } catch(error) {

            alert("Ha ocurrido un error, intente seleccionar el cargo nuevamente")
            console.error("Error:", error.message)
        }
    }
})

register_form.addEventListener('submit', (event) => {
    event.preventDefault()

    if (!registerValidator.namesInput(first_name, first_name_msg) ||
        !registerValidator.namesInput(last_name, last_name_msg) ||
        !registerValidator.documentIdInput(employee_document, employee_document_msg) ||
        !registerValidator.selectsInput(nationality, nationality_msg) ||
        !registerValidator.dateInput(birthdate, birthdate_msg) ||
        !registerValidator.localitySelect(locality, locality_msg, other_locality, other_locality_container) ||
        !registerValidator.stringsInput(address, address_msg) ||
        !registerValidator.doorNumberInput(address_number, address_number_msg) ||
        !registerValidator.emailInput(email, email_msg) ||
        !registerValidator.selectsInput(cellphone_code, cellphone_code_msg) ||
        !registerValidator.phoneNumberInput(cellphone_number, cellphone_number_msg) ||
        !registerValidator.dateInput(entry_date, entry_date_msg) ||
        !registerValidator.passwordInput(password, password_msg) ||
        !registerValidator.passwordMatch(confirm_password, confirm_password_msg, password)) {

            alert("Debe completar todos los campos")
            return
        }

    if (locality.value.trim() === "Otra localidad") {
        if(!registerValidator.stringsInput(other_locality, other_locality_msg)) {

            alert("Debe completar todos los campos")
            return
        }
    }

    if (position.value === "") {

        alert("Debe seleccionar un cargo válido")
        return
    }

    if (position.value.trim() === "FA") {
        if(!registerValidator.selectsInput(permissions, permissions_msg)) {

            alert("Debe completar todos los campos")
            return
        }
    }

    if (position.value.trim() === "CO") {
        if(!registerValidator.selectsInput(speciality, speciality_msg)) {

            alert("Debe completar todos los campos")
            return
        }
    }

    if (position.value.trim() === "DR") {
        if(!registerValidator.selectsInput(license_expiration, license_expiration_msg) ||
            !registerValidator.selectsInput(license_category, license_category_msg)) {

            alert("Debe completar todos los campos")
            return
        }
    }

    if (other_locality.required) {
        if(!registerValidator.stringsInput(other_locality, other_locality_msg)) {

        alert("Debe completar todos los campos")
        return
        }
    }

    if (!registerValidator.fullPhoneNumber(cellphone_code, cellphone_number)) {

        alert("El celular ingresado no es válido")
        return
    }

    register_form.submit()
})