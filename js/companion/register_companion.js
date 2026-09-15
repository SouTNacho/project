import formTools from "/js/library.js"
const { registerValidator } = formTools

const register_form = document.querySelector("#companion_register_form")

const companion_document = document.querySelector("#companion_document")
const first_name = document.querySelector("#companion_first_name")
const last_name = document.querySelector("#companion_last_name")
const register_btn = document.querySelector("#companion_register_btn")

const document_msg = document.querySelector("#companion_document_msg")
const first_name_msg = document.querySelector("#companion_first_name_msg")
const last_name_msg = document.querySelector("#companion_last_name_msg")
const register_btn_msg = document.querySelector("#companion_register_btn_msg")

companion_document.addEventListener('input', () => registerValidator.documentIdInput(companion_document, document_msg))
first_name.addEventListener('input', () => registerValidator.namesInput(first_name, first_name_msg))
last_name.addEventListener('input', () => registerValidator.namesInput(last_name, last_name_msg))

register_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.documentIdInput(companion_document, document_msg) ||
        !registerValidator.namesInput(first_name, first_name_msg) ||
        !registerValidator.namesInput(last_name, last_name_msg)) {

        alert("Debe completar todos los campos")
        return
    }

    register_form.submit()
})