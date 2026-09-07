import formTools from "/js/library.js"
const { registerValidator } = formTools

const name = document.querySelector("#companion_first_name")
const last_name = document.querySelector("#companion_last_name")
const companion_document = document.querySelector("#companion_document")
const register_btn = document.querySelector("#companion_register_btn")
const register_form = document.querySelector("#companion_register_form")

const name_msg = document.querySelector("#companion_first_name_msg")
const last_name_msg = document.querySelector("#companion_last_name_msg")
const companion_document_msg = document.querySelector("#companion_document_msg")
const register_btn_msg = document.querySelector("#companion_register_btn_msg")

name.addEventListener('input', () => registerValidator.namesInput(name, name_msg))
last_name.addEventListener('input', () => registerValidator.namesInput(last_name, last_name_msg))
companion_document.addEventListener('input', () => registerValidator.documentIdInput(companion_document, companion_document_msg))

register_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.namesInput(name, name_msg) ||
        !registerValidator.namesInput(last_name, last_name_msg) ||
        !registerValidator.documentIdInput(companion_document, companion_document_msg)
    ) {
        alert("Debe completar todos los campos")
        return
    }
    register_form.submit()
})