import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = new URLSearchParams(window.location.search).get('id')
const form = document.querySelector("#cellphone_form")
const title = document.querySelector('h2')
const is_update = id !== null && id.trim() !== ''

async function loadCellphone() {

    try {

        const response = await fetch(`/php/actions/cellphone/get_cellphone.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el teléfono')
            return
        }

        const item = result.item
        const code = item.telefono.slice(0, 4)
        const number = item.telefono.slice(4)

        cellphone_document.placeholder = item.cedula
        cellphone_code.value = code
        cellphone_number.placeholder = number

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const cellphone_document = document.querySelector("#employee_document")
const cellphone_code = document.querySelector("#cellphone_code")
const cellphone_number = document.querySelector("#cellphone_number")
const btn = document.querySelector("#cellphone_btn")

const cellphone_document_msg = document.querySelector("#employee_document_msg")
const cellphone_msg = document.querySelector("#cellphone_msg")
// const btn_msg = document.querySelector("#cellphone_btn_msg")
const inputs = [cellphone_code, cellphone_number]

formTools.loadPhoneCodesSelect(cellphone_code)

if (is_update) {

    form.action = `/php/actions/cellphone/cellphone_update.php?id=${id}`
    title.textContent = 'Actualizar Teléfono'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    cellphone_document.readOnly = true
    await loadCellphone()
} else {

    form.action = '/php/actions/cellphone/cellphone_register.php'
    title.textContent = 'Registrar Teléfono'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


cellphone_document.addEventListener('input', () => {

    if (cellphone_document.value.trim() !== '') {

        return registerValidator.documentIdInput(cellphone_document, cellphone_document_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(cellphone_document, cellphone_document_msg)
    }
    
    return formTools.setInvalid(cellphone_document, cellphone_document_msg, 'Este campo es obligatorio')
})

cellphone_code.addEventListener('change', () => {

    if (cellphone_code.value.trim() !== '') {

        return registerValidator.selectsInput(cellphone_code, cellphone_msg)
    }

    if(is_update) {

        return formTools.setValid(cellphone_code, cellphone_msg)
    }

    return formTools.setInvalid(cellphone_code, cellphone_msg, 'Este campo es obligatorio')
})

cellphone_number.addEventListener('input', () => {

    if (cellphone_number.value.trim() !== '') {

        return registerValidator.cellphone_numberInput(cellphone_number, cellphone_msg)
    }

    if(is_update) {

        return formTools.setValid(cellphone_number, cellphone_msg)
    }

    return formTools.setInvalid(cellphone_number, cellphone_msg, 'Este campo es obligatorio')
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

    if (cellphone_document.value.trim() === '') {

        if (!is_update) {

            alert("La cédula es obligatoria")
            return
        }
    } else {

        if (!registerValidator.documentIdInput(cellphone_document, cellphone_document_msg)) {
            alert("La cédula no es válida")
            return
        }
    }
    
    if (cellphone_number.value.trim() !== '' || cellphone_code.value.trim() !== '') {

        if (!registerValidator.fullPhoneNumber(cellphone_code, cellphone_number)) {
            alert("El teléfono ingresado no es válido")
            return
        }
    }

    form.submit()
})

lucide.createIcons()