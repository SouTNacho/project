import formTools from "/js/library.js"
const { registerValidator } = formTools

const register_form = document.querySelector("#sample_register_form")

const code = document.querySelector('#sample_code')
const patient_document = document.querySelector("#sample_document_patient")
const type = document.querySelector("#sample_type")
const description = document.querySelector("#sample_description")
const register_btn = document.querySelector("#sample_register_btn")

const code_msg = document.querySelector('#sample_code_msg')
const document_msg = document.querySelector("#sample_document_patient_msg")
const type_msg = document.querySelector("#sample_type_msg")
const description_msg = document.querySelector("#sample_description_msg")
const register_btn_msg = document.querySelector("#sample_register_btn_msg")

code.addEventListener('input', () => registerValidator.sampleCodeInput(code, code_msg))
patient_document.addEventListener('input', () => registerValidator.documentIdInput(patient_document, document_msg))
type.addEventListener('input', () => registerValidator.selectsInput(type, type_msg))
description.addEventListener('input', () => registerValidator.largeStringsInput(description, description_msg))

register_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.documentIdInput(patient_document, document_msg) ||
        !registerValidator.selectsInput(type, type_msg) ||
        !registerValidator.largeStringsInput(description, description_msg) ||
        !registerValidator.sampleCodeInput(code, code_msg)) {

        alert("Debe completar todos los campos")
        return
    }

    register_form.submit()
})