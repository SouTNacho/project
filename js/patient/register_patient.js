import formTools from "/js/library.js"
const { registerValidator } = formTools

const register_form = document.querySelector("#patient_register_form")

const first_name = document.querySelector("#patient_first_name")
const last_name = document.querySelector("#patient_last_name")
const patient_document = document.querySelector("#patient_document")
const birthdate = document.querySelector("#patient_birthdate")
const address = document.querySelector("#patient_address")
const email = document.querySelector("#patient_email")
const cellphone_code = document.querySelector("#patient_cellphone_code")
const cellphone_number = document.querySelector("#patient_cellphone_number")
const register_btn = document.querySelector("#patient_register_btn")

const first_name_msg = document.querySelector("#patient_first_name_msg")
const last_name_msg = document.querySelector("#patient_last_name_msg")
const patient_document_msg = document.querySelector("#patient_document_msg")
const birthdate_msg = document.querySelector("#patient_birthdate_msg")
const address_msg = document.querySelector("#patient_address_msg")
const email_msg = document.querySelector("#patient_email_msg")
const cellphone_code_msg = document.querySelector("#patient_cellphone_number_msg")
const cellphone_number_msg = document.querySelector("#patient_cellphone_number_msg")
const register_btn_msg = document.querySelector("#patient_register_btn_msg")

formTools.loadPhoneCodesSelect(cellphone_code)

first_name.addEventListener('input', () => registerValidator.namesInput(first_name, first_name_msg))
last_name.addEventListener('input', () => registerValidator.namesInput(last_name, last_name_msg))
patient_document.addEventListener('input', () => registerValidator.documentIdInput(patient_document, patient_document_msg))
birthdate.addEventListener('input', () => registerValidator.dateInput(birthdate, birthdate_msg))
address.addEventListener('input', () => registerValidator.stringsInput(address, address_msg))
email.addEventListener('input', () => registerValidator.emailInput(email, email_msg))
cellphone_code.addEventListener('change', () => registerValidator.selectsInput(cellphone_code, cellphone_code_msg))
cellphone_number.addEventListener('input', () => registerValidator.phoneNumberInput(cellphone_number, cellphone_number_msg))

register_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.namesInput(first_name, first_name_msg) ||
        !registerValidator.namesInput(last_name, last_name_msg) ||
        !registerValidator.documentIdInput(patient_document, patient_document_msg) ||
        !registerValidator.dateInput(birthdate, birthdate_msg) ||
        !registerValidator.stringsInput(address, address_msg) ||
        !registerValidator.emailInput(email, email_msg) ||
        !registerValidator.selectsInput(cellphone_code, cellphone_code_msg) ||
        !registerValidator.phoneNumberInput(cellphone_number, cellphone_number_msg)
    ) {

        alert("Debe completar todos los campos")
        return
    }

    if (!registerValidator.fullPhoneNumber(cellphone_code, cellphone_number)) {

        alert("El celular ingresado no es válido")
        return
    }

    register_form.submit()
})