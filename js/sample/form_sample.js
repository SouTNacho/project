import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#sample_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

async function loadSample() {

    try {

        const response = await fetch(`/php/actions/sample/get_sample.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar la muestra')
            return
        }

        const item = result.item

        code.placeholder = item.codigo
        patient_document.placeholder = item.cedula
        type[0].textContent = item.tipo
        description.placeholder = item.descripcion

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const code = document.querySelector('#sample_code')
const patient_document = document.querySelector("#sample_document_patient")
const type = document.querySelector("#sample_type")
const description = document.querySelector("#sample_description")
const btn = document.querySelector("#sample_btn")

const code_msg = document.querySelector('#sample_code_msg')
const document_msg = document.querySelector("#sample_document_patient_msg")
const type_msg = document.querySelector("#sample_type_msg")
const description_msg = document.querySelector("#sample_description_msg")
// const btn_msg = document.querySelector("#sample_btn_msg")
const inputs = [code, patient_document, type, description]

if (is_update) {

    form.action = `/php/actions/sample/sample_form.php?id=${id}`
    title.textContent = 'Actualizar Muestra'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadSample()
} else {

    form.action = '/php/actions/sample/sample_form.php'
    title.textContent = 'Registrar Muestra'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}

code.addEventListener('input', () => {

    if (code.value.trim() !== '') {

        return registerValidator.sampleCodeInput(code, code_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(code, code_msg)
    }
    
    return formTools.setInvalid(code, code_msg, 'Este campo es obligatorio')
})

patient_document.addEventListener('input', () => {

    if (patient_document.value.trim() !== '') {

        return registerValidator.documentIdInput(patient_document, document_msg)
    }

    if(is_update) {

        return formTools.setValid(patient_document, document_msg)
    }

    return formTools.setInvalid(patient_document, document_msg, 'Este campo es obligatorio')
})

type.addEventListener('change', () => {

    if (type.value.trim() !== '') {

        return registerValidator.selectsInput(type, type_msg)
    }

    if(is_update) {

        return formTools.setValid(type, type_msg)
    }

    return formTools.setInvalid(type, type_msg, 'Este campo es obligatorio')
})

description.addEventListener('input', () => {

    if (description.value.trim() !== '') {

        return registerValidator.largeStringsInput(description, description_msg)
    }
    
    if (is_update) {

        return formTools.setValid(description, description_msg)
    }

    return formTools.setInvalid(description, description_msg, 'Este campo es obligatorio')
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

    if (code.value.trim() === '') {

        if (!is_update) {

            alert("El código es obligatorio")
            return
        }
    } else {

        if (!registerValidator.sampleCodeInput(code, code_msg)) {
            alert("El código no es válido")
            return
        }
    }

    if (patient_document.value.trim() === '') {

        if (!is_update) {

            alert("La cédula del paciente es obligatoria")
            return
        }
    } else {

        if (!registerValidator.documentIdInput(patient_document, document_msg)) {

            alert("La cédula del paciente no es válida")
            return
        }
    }

    if (type.value.trim() === '') {

        if (!is_update) {

            alert("El tipo es obligatorio")
            return
        }
    } else {

        if (!registerValidator.selectsInput(type, type_msg)) {

            alert("El tipo no es válido")
            return
        }
    }
    
    if (description.value.trim() === '') {

        if (!is_update) {

            alert("La descripción es obligatoria")
            return
        }
    } else {

        if (!registerValidator.largeStringsInput(description, description_msg)) {

            alert("La descripción no es válida")
            return
        }
    }

    form.submit()
})

lucide.createIcons()