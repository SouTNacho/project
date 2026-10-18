import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#patient_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

const first_name = document.querySelector("#patient_first_name")
const last_name = document.querySelector("#patient_last_name")
const patient_document = document.querySelector("#patient_document")
const birthdate = document.querySelector("#patient_birthdate")
const address = document.querySelector("#patient_address")
const email = document.querySelector("#patient_email")
const cellphone_code = document.querySelector("#patient_cellphone_code")
const cellphone_number = document.querySelector("#patient_cellphone_number")
const btn = document.querySelector("#patient_btn")

const first_name_msg = document.querySelector("#patient_first_name_msg")
const last_name_msg = document.querySelector("#patient_last_name_msg")
const patient_document_msg = document.querySelector("#patient_document_msg")
const birthdate_msg = document.querySelector("#patient_birthdate_msg")
const address_msg = document.querySelector("#patient_address_msg")
const email_msg = document.querySelector("#patient_email_msg")
const cellphone_msg = document.querySelector("#patient_cellphone_number_msg")
// const btn_msg = document.querySelector("#patient_btn_msg")

formTools.loadPhoneCodesSelect(cellphone_code)
const inputs = [first_name, last_name, patient_document,
    birthdate, address, email, cellphone_code, cellphone_number]

async function loadPatient() {

    try {

        const response = await fetch(`/php/actions/patient/get_patient.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el paciente')
            return
        }

        const item = result.item

        first_name.placeholder = item.nombre
        last_name.placeholder = item.apellido
        patient_document.placeholder = item.cedula
        address.placeholder = item.direccion
        email.placeholder = item.email
        const code = item.telefono.slice(0, 4)
        const cellphone = item.telefono.slice(4)
        cellphone_code[0].textContent = code
        cellphone_number.placeholder = cellphone

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

if (is_update) {

    form.action = `/php/actions/patient/patient_update.php?id=${id}`
    title.textContent = 'Actualizar Paciente'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadPatient()
} else {

    form.action = '/php/actions/patient/patient_register.php'
    title.textContent = 'Registrar Paciente'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}

first_name.addEventListener('input', () => {

    if (first_name.value.trim() !== '') {

        return registerValidator.namesInput(first_name, first_name_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(first_name, first_name_msg)
    }
    
    return formTools.setInvalid(first_name, first_name_msg, 'Este campo es obligatorio')
})

last_name.addEventListener('input', () => {

    if (last_name.value.trim() !== '') {

        return registerValidator.namesInput(last_name, last_name_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(last_name, last_name_msg)
    }
    
    return formTools.setInvalid(last_name, last_name_msg, 'Este campo es obligatorio')
})

patient_document.addEventListener('input', () => {

    if (patient_document.value.trim() !== '') {

        return registerValidator.documentIdInput(patient_document, patient_document_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(patient_document, patient_document_msg)
    }
    
    return formTools.setInvalid(patient_document, patient_document_msg, 'Este campo es obligatorio')
})

birthdate.addEventListener('change', () => {

    if (birthdate.value.trim() !== '') {

        return registerValidator.dateInput(birthdate, birthdate_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(birthdate, birthdate_msg)
    }
    
    return formTools.setInvalid(birthdate, birthdate_msg, 'Este campo es obligatorio')
})

address.addEventListener('input', () => {

    if (address.value.trim() !== '') {

        return registerValidator.stringsInput(address, address_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(address, address_msg)
    }
    
    return formTools.setInvalid(address, address_msg, 'Este campo es obligatorio')
})

email.addEventListener('input', () => {

    if (email.value.trim() !== '') {

        return registerValidator.emailInput(email, email_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(email, email_msg)
    }
    
    return formTools.setInvalid(email, email_msg, 'Este campo es obligatorio')
})

cellphone_code.addEventListener('change', () => {

    if (cellphone_code.value.trim() !== '') {

        if (!registerValidator.selectsInput( cellphone_code, cellphone_msg)) return

        if (cellphone_number.value.trim() === '') {

            return formTools.setInvalid(cellphone_number, cellphone_msg,
                'Si selecciona un código, el número es obligatorio')
        }

        return registerValidator.phoneNumberInput( cellphone_number, cellphone_msg)
    }

    cellphone_number.value = ''

    if (is_update) {
        formTools.setValid(cellphone_code, cellphone_msg)
        return formTools.setValid(cellphone_number, cellphone_msg)
    }

    formTools.setInvalid(cellphone_code, cellphone_msg, 'Este campo es obligatorio')
    return formTools.setInvalid(cellphone_number, cellphone_msg, 'Este campo es obligatorio')
})

cellphone_number.addEventListener('input', () => {

    if (cellphone_code.value.trim() === '') {

        if (cellphone_number.value.trim() !== '') {

            return formTools.setInvalid(cellphone_number, cellphone_msg,
                'Para ingresar el número, debe seleccionar el código.')
        }

        return formTools.setValid(cellphone_number, cellphone_msg)
    }

    if (cellphone_number.value.trim() === '') {

        return formTools.setInvalid(cellphone_number, cellphone_msg,
            'El número de celular es obligatorio')
    }

    return registerValidator.phoneNumberInput(cellphone_number, cellphone_msg
    )
})

form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (is_update) {

        let hasChanges = false
        for (const input of inputs) {
            if (input.value.trim() !== '') {
                hasChanges = true
                break
            }
        }

        if (!hasChanges) {
            alert("Debe completar al menos un campo")
            return
        }
    }

    if (first_name.value.trim() === '') {

        if (!is_update) {
            alert("El nombre es obligatorio")
            return
        }
    } else {

        if (!registerValidator.namesInput(first_name, first_name_msg)) {
            alert("El nombre no es válido")
            return
        }
    }

    if (last_name.value.trim() === '') {

        if (!is_update) {
            alert("El apellido es obligatorio")
            return
        }
    } else {

        if (!registerValidator.namesInput(last_name, last_name_msg)) {
            alert("El apellido no es válido")
            return
        }
    }

    if (patient_document.value.trim() === '') {

        if (!is_update) {
            alert("La cédula es obligatoria")
            return
        }
    } else {

        if (!registerValidator.documentIdInput(patient_document, patient_document_msg)) {
            alert("La cédula no es válida")
            return
        }
    }

    if (birthdate.value.trim() === '') {

        if (!is_update) {
            alert("La fecha de nacimiento es obligatoria")
            return
        }
    } else {

        if (!registerValidator.dateInput(birthdate, birthdate_msg)) {
            alert("La fecha de nacimiento no es válida")
            return
        }
    }

    if (address.value.trim() === '') {

        if (!is_update) {
            alert("La dirección es obligatoria")
            return
        }
    } else {

        if (!registerValidator.stringsInput(address, address_msg)) {
            alert("La dirección no es válida")
            return
        }
    }

    if (email.value.trim() === '') {

        if (!is_update) {
            alert("El email es obligatorio")
            return
        }
    } else {

        if (!registerValidator.emailInput(email, email_msg)) {
            alert("El email no es válido")
            return
        }
    }

    if (cellphone_code.value.trim() === '') {

        if (cellphone_number.value.trim() !== '') {
            alert("Para ingresar el número, debe seleccionar el código")
            return
        }

        if (!is_update) {
            alert("El código de celular es obligatorio")
            return
        }

    } else {

        if (!registerValidator.selectsInput(cellphone_code, cellphone_msg)) {
            alert("El código de celular no es válido")
            return
        }

        if (cellphone_number.value.trim() === '') {
            alert("El número de celular es obligatorio")
            return
        }

        if (!registerValidator.phoneNumberInput(cellphone_number, cellphone_msg)) {
            alert("El número de celular no es válido")
            return
        }

        if (!registerValidator.fullPhoneNumber(cellphone_code, cellphone_number)) {
            alert("El celular ingresado no es válido")
            return
        }
    }

    form.submit()
})

lucide.createIcons()