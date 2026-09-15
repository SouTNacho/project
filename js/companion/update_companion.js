import formTools from "/js/library.js"
const { registerValidator } = formTools

const update_form = document.querySelector("#companion_update_form")

const current_document = document.querySelector("#companion_current_document")
const new_document = document.querySelector("#companion_new_document")
const first_name = document.querySelector("#companion_first_name")
const last_name = document.querySelector("#companion_last_name")
const update_btn = document.querySelector("#companion_update_btn")

const current_document_msg = document.querySelector("#companion_current_document_msg")
const new_document_msg = document.querySelector("#companion_new_document_msg")
const first_name_msg = document.querySelector("#companion_first_name_msg")
const last_name_msg = document.querySelector("#companion_last_name_msg")
const update_btn_msg = document.querySelector("#companion_update_btn_msg")

current_document.addEventListener('input', () =>
    registerValidator.documentIdInput(current_document, current_document_msg))

new_document.addEventListener('input', () => {

    if (new_document.value.trim() !== '') {
        registerValidator.documentIdInput(new_document, new_document_msg)

    } else {
        return formTools.setValid(new_document, new_document_msg)
    }
})

first_name.addEventListener('input', () => {

    if (first_name.value.trim() !== '') {
        registerValidator.namesInput(first_name, first_name_msg)

    } else {
        return formTools.setValid(first_name, first_name_msg)
    }
})

last_name.addEventListener('input', () => {

    if (last_name.value.trim() !== '') {
        registerValidator.namesInput(last_name, last_name_msg)

    } else {
        return formTools.setValid(last_name, last_name_msg)
    }
})

update_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.documentIdInput(current_document, current_document_msg)) {

        alert("La cédula actual es obligatoria")
        return
    }

    if (new_document.value.trim() !== "") {

        if (!registerValidator.documentIdInput(new_document, new_document_msg)) {

            alert("La nueva cédula debe ser válida o estar vacía")
            return
        }
    }

    if (first_name.value.trim() !== "") {

        if (!registerValidator.namesInput(first_name, first_name_msg)) {

            alert("El nombre debe ser válido o estar vacío")
            return
        }
    }

    if (last_name.value.trim() !== '') {

        if (!registerValidator.namesInput(last_name, last_name_msg)) {

            alert("El apellido debe ser válido o estar vacío")
            return
        }
        
    }

    update_form.submit()
})