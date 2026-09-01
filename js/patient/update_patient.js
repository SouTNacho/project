import formTools from "/js/library.js"
const { registerValidator } = formTools

const update_form = document.querySelector("#patient_update_form")

const first_name = document.querySelector("#patient_first_name")
const last_name = document.querySelector("#patient_last_name")
const patient_new_document = document.querySelector("#patient_new_document")
const patient_current_document = document.querySelector("#patient_current_document")
const birthdate = document.querySelector("#patient_birthdate")
const address = document.querySelector("#patient_address")
const email = document.querySelector("#patient_email")
const cellphone_code = document.querySelector("#patient_cellphone_code")
const cellphone_number = document.querySelector("#patient_cellphone_number")
const update_btn = document.querySelector("#patient_update_btn")

const first_name_msg = document.querySelector("#patient_first_name_msg")
const last_name_msg = document.querySelector("#patient_last_name_msg")
const patient_new_document_msg = document.querySelector("#patient_new_document_msg")
const patient_current_document_msg = document.querySelector("#patient_current_document_msg")
const birthdate_msg = document.querySelector("#patient_birthdate_msg")
const address_msg = document.querySelector("#patient_address_msg")
const email_msg = document.querySelector("#patient_email_msg")
const cellphone_code_msg = document.querySelector("#patient_cellphone_number_msg")
const cellphone_number_msg = document.querySelector("#patient_cellphone_number_msg")
const update_btn_msg = document.querySelector("#patient_update_btn_msg")

formTools.loadPhoneCodesSelect(cellphone_code)

if (patient_current_document.value.trim() !== "") {

    registerValidator.documentIdInput(patient_current_document, patient_current_document_msg)
}

first_name.addEventListener('input', () => {
    
    if (first_name.value.trim() !== "") {

        registerValidator.namesInput(first_name, first_name_msg)
    } else {

        return formTools.setValid(first_name, first_name_msg)
    }

})

last_name.addEventListener('input', () => {

    if (last_name.value.trim() !== "") {

        registerValidator.namesInput(last_name, last_name_msg)
    } else {

        return formTools.setValid(last_name, last_name_msg)
    }
})

patient_new_document.addEventListener('input', () => {

    if (patient_new_document.value.trim() !== "") {

        registerValidator.documentIdInput(patient_new_document, patient_new_document_msg)
    } else {

        return formTools.setValid(patient_new_document, patient_new_document_msg)
    }
})

birthdate.addEventListener('input', () => {

    if (birthdate.value.trim() !== "") {

        registerValidator.dateInput(birthdate, birthdate_msg)
    } else {

        return formTools.setValid(birthdate, birthdate_msg)
    }
})

address.addEventListener('input', () => {

    if (address.value.trim() !== "") {

        registerValidator.stringsInput(address, address_msg)
    } else {

        return formTools.setValid(address, address_msg)
    }
})

email.addEventListener('input', () => {
    
    if (email.value.trim() !== "") {

        registerValidator.emailInput(email, email_msg)
    } else {

        return formTools.setValid(email, email_msg)
    }
})

cellphone_code.addEventListener('input', () => {

    if (cellphone_code.value.trim() !== "") {

        registerValidator.selectsInput(cellphone_code, cellphone_code_msg)
    } else {

        return formTools.setValid(cellphone_code, cellphone_code_msg)
    }
})

cellphone_number.addEventListener('input', () => {

    if (cellphone_number.value.trim() !== "") {

        registerValidator.phoneNumberInput(cellphone_number, cellphone_number_msg)
    } else {

        return formTools.setValid(cellphone_number, cellphone_number_msg)
    }
})

update_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.documentIdInput(patient_current_document, patient_current_document_msg)) {

            alert("El nombre debe ser válido o estar vacío")
            return
        }

    if (first_name.value.trim() !== "") {

        if (!registerValidator.namesInput(first_name, first_name_msg)) {

            alert("El nombre debe ser válido o estar vacío")
            return
        }
    }

    if (last_name.value.trim() !== "") {

        if (!registerValidator.namesInput(last_name, last_name_msg)) {

            alert("El apellido debe ser válido o estar vacío")
            return
        }
    }

    if (patient_new_document.value.trim() !== "") {

        if (!registerValidator.documentIdInput(patient_new_document, patient_new_document_msg)) {

            alert("La nueva cédula debe ser válida o estar vacía")
            return
        }
    }

    if (birthdate.value.trim() !== "") {

        if (!registerValidator.dateInput(birthdate, birthdate_msg)) {

            alert("La fecha de nacimiento debe ser válida o estar vacía")
            return
        }
    }

    if (address.value.trim() !== "") {

        if (!registerValidator.stringsInput(address, address_msg)) {

            alert("La dirección debe ser válida o estar vacía")
            return
        }
    }
    
    if (email.value.trim() !== "") {

        if (!registerValidator.emailInput(email, email_msg)) {

            alert("El email debe ser válido o estar vacío")
            return
        }
    }

    if (cellphone_code.value.trim() !== "" || cellphone_number.value.trim() !== "") {

        if (!registerValidator.fullPhoneNumber(cellphone_code, cellphone_number)) {

            alert("El teléfono debe ser válido o estar vacío")
            return
        }
    }

    update_form.submit()
})