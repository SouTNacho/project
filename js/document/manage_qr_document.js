import formTools from "/js/library.js"
const { registerValidator } = formTools

const form_container = document.querySelector("#satisfaction_container_form")
const satisfaction_form = document.querySelector("#satisfaction_form")
const user_document = document.querySelector("#user_document")
const document_button = document.querySelector("#user_document_btn")
const user_first_name = document.querySelector("#user_first_name")
const user_last_name = document.querySelector("#user_last_name")
const phone_container = document.querySelector(".phone_container")
const user_cellphone_code = document.querySelector("#user_cellphone_code")
const user_cellphone_number = document.querySelector("#user_cellphone_number")
const user_address = document.querySelector("#user_address")
const user_email = document.querySelector("#user_email")
const satisfaction_value = document.querySelector("#satisfaction_value")
const send_form_btn = document.querySelector("#send_form_btn")


const document_msg = document.querySelector("#user_document_msg")
const user_first_name_msg = document.querySelector("#user_first_name_msg")
const user_last_name_msg = document.querySelector("#user_last_name_msg")
const user_cellphone_number_msg = document.querySelector("#user_cellphone_number_msg")
const satisfaction_value_msg = document.querySelector("#satisfaction_value_msg")
const user_address_msg = document.querySelector("#user_address_msg")
const user_email_msg = document.querySelector("#user_email_msg")

const download_btn = document.querySelector("#download_btn")
const form_button = document.querySelector("#form_button")

const token = new URLSearchParams("token")

download_btn.addEventListener('click', () => {

    form_container.classList.add("hidden")
    location.href = "/php/action/down_doc${token}"
})

form_button.addEventListener('click', () => form_container.classList.remove("hidden"))

formTools.loadPhoneCodesSelect(user_cellphone_code)
user_document.addEventListener('input', () => registerValidator.documentIdInput(user_document, document_msg))
user_first_name.addEventListener('input', () => registerValidator.namesInput(user_first_name, user_first_name_msg))
user_last_name.addEventListener('input', () => registerValidator.namesInput(user_last_name, user_last_name_msg))
user_cellphone_code.addEventListener('change', () => registerValidator.selectsInput(user_cellphone_code, user_cellphone_number_msg))
satisfaction_value.addEventListener('change', () => registerValidator.selectsInput(satisfaction_value, satisfaction_value_msg))
user_cellphone_number.addEventListener('input', () => registerValidator.phoneNumberInput(user_cellphone_number, user_cellphone_number_msg))
user_address.addEventListener('input', () => registerValidator.emailInput(user_address, user_address_msg))
user_email.addEventListener('input', () => registerValidator.stringsInput(user_email, user_email_msg))

document_button.addEventListener('click', async () => {
    const document = user_document.value.trim()

    if (!document.registerValidator.documentIdInput(user_document, document_msg)) {
        alert("El documento ingresado no es correcto")
        return
    }

    const formData = new FormData()
    formData.append("document", document)

    try {

        const response = await fetch('/php/actions/get_user.php', {
            method: "POST",
            body:formData
        })

        const result = await response.json()

        if (!response.ok || !result.succes) {
            alert("Ha ocurrido un error al conectar con el servidor")
            return
        }

        if (result.message !== "El usuario solicitado existe") {
            alert("Usted no esta registrado, por favor complete el formulario")
            return
        }

        phone_container.classList.add("hidden")

        user_first_name.disabled = true
        user_last_name.disabled = true
        user_address.disabled = true
        user_email.disabled = true

        user_first_name.textContent = result.first_name
        user_last_name.textContent = result.last_name
        user_address.textContent = result.address
        user_email.textContent = result.email

        user_first_name.value = result.first_name
        user_last_name.value = result.last_name
        user_address.value = result.address
        user_email.value = result.email

    } catch (error) {

        alert("Ha ocurrido un error, confirme nuevamente")
        return
    }
})

send_form_btn.addEventListener('click', async () => {

    if (!registerValidator.phoneNumberInput(user_cellphone_number, user_cellphone_number_msg) ||
        !registerValidator.selectsInput(satisfaction_value, satisfaction_value_msg) ||
        !registerValidator.selectsInput(user_cellphone_code, user_cellphone_number_msg) ||
        !registerValidator.namesInput(user_last_name, user_last_name_msg) ||
        !registerValidator.namesInput(user_first_name, user_first_name_msg) ||
        !registerValidator.documentIdInput(user_document, document_msg) ||
        !registerValidator.emailInput(user_address, user_address_msg) ||
        !registerValidator.stringsInput(user_email, user_email_msg)) {

        alert("Todos los campos son obligatorios")
        return
    }

    const satFormData = new FormData(satisfaction_form)
    satFormData.append('token', token)

    try {

        const response = await fetch('/php/actions/user_register.php', {
            method: "POST",
            body:satFormData
        })

        const result = await response.json()

        if (!response.ok || !result.succes) {
            alert(result.message || "Ha ocurrido un error al conectar con el servidor")
            return
        }

        alert("Felicidades, ha sido registrado correctamente")
        location.href = `/php/action/download_document?id=${token}`
        location.href = "/php/screen_documents.php"

    } catch (error) {

        alert("Ha ocurrido un error, confirme nuevamente")
        return
    }
})


