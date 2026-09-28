import formTools from "/js/library.js"
const { registerValidator } = formTools

const update_form = document.querySelector("#sample_update_form")

const current_code = document.querySelector("#sample_current_code")
const new_code = document.querySelector("#sample_new_code")
const patient_document = document.querySelector("#sample_document_patient")
const type = document.querySelector("#sample_type")
const description = document.querySelector("#sample_description")
const update_btn = document.querySelector("#sample_update_btn")

const current_code_msg = document.querySelector("#sample_current_code_msg")
const new_code_msg = document.querySelector("#sample_new_code_msg")
const document_msg = document.querySelector("#sample_document_patient_msg")
const type_msg = document.querySelector("#sample_type_msg")
const description_msg = document.querySelector("#sample_description_msg")
const update_btn_msg = document.querySelector("#sample_update_btn_msg")

current_code.addEventListener('input', () =>
    registerValidator.sampleCodeInput(current_code, current_code_msg))

new_code.addEventListener('input', () => {

    if (new_code.value.trim() !== '') {
        registerValidator.sampleCodeInput(new_code, new_code_msg)

    } else {
        return formTools.setValid(new_code, new_code_msg)
    }
})

patient_document.addEventListener('input', () => {

    if (patient_document.value.trim() !== '') {
        registerValidator.documentIdInput(patient_document, document_msg)

    } else {
        return formTools.setValid(patient_document, document_msg)
    }
})

type.addEventListener('input', () => {

    if (type.value.trim() !== '') {
        registerValidator.selectsInput(type, type_msg)

    } else {
        return formTools.setValid(type, type_msg)
    }
})

description.addEventListener('input', () => {

    if (description.value.trim() !== '') {
        registerValidator.largeStringsInput(description, description_msg)

    } else {
        return formTools.setValid(description, description_msg)
    }
})

update_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.sampleCodeInput(current_code, current_code_msg)) {

        alert("El código actual es obligatorio")
        return
    }

    if (new_code.value.trim() !== "") {

        if (!registerValidator.sampleCodeInput(new_code, new_code_msg)) {

            alert("El nuevo código debe ser válido o estar vacío")
            return
        }
    }

    if (patient_document.value.trim() !== "") {

        if (!registerValidator.documentIdInput(patient_document, document_msg)) {

            alert("La cédula debe ser válida o estar vacía")
            return
        }
    }

    if (type.value.trim() !== "") {

        if (!registerValidator.selectsInput(type, type_msg)) {

            alert("El tipo debe ser válido o estar vacío")
            return
        }
    }

    if (description.value.trim() !== '') {

        if (!registerValidator.largeStringsInput(description, description_msg)) {

            alert("La descripción debe ser válida o estar vacía")
            return
        }
        
    }

    update_form.submit()
})