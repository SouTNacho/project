import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#sample_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

const defaultTypes = [
    { id_tipo_muestra: 1, nombre: 'Sangre' },
    { id_tipo_muestra: 2, nombre: 'Orina' },
    { id_tipo_muestra: 3, nombre: 'Heces' },
    { id_tipo_muestra: 4, nombre: 'Secreciones' },
    { id_tipo_muestra: 5, nombre: 'Esputo' },
    { id_tipo_muestra: 6, nombre: 'Saliva' },
    { id_tipo_muestra: 7, nombre: 'Líquidos corporales' },
    { id_tipo_muestra: 8, nombre: 'Tejidos' },
    { id_tipo_muestra: 9, nombre: 'Células' },
    { id_tipo_muestra: 10, nombre: 'Médula ósea' },
    { id_tipo_muestra: 11, nombre: 'Material reproductivo' },
    { id_tipo_muestra: 12, nombre: 'Material dermatológico' },
    { id_tipo_muestra: 13, nombre: 'Material de heridas' },
    { id_tipo_muestra: 14, nombre: 'Otros' }
]

async function loadTypes() {
    
    try {

        const response = await fetch('/php/actions/sample/get_types.php')
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Error en la solicitud')
            return defaultTypes
        }

        return result.item
    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return defaultTypes
    }
}

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
        description.placeholder = item.descripcion

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

function createOptions(options, item) {
    item.innerHTML = '<option value="">Seleccione una opción</option>'

    options.forEach(option => {
        
        const opt = document.createElement('option')
        opt.textContent = option.nombre
        opt.value = option.id_tipo_muestra

        item.append(opt)
    })
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

const types = await loadTypes()
createOptions(types, type)

if (is_update) {

    form.action = `/php/actions/sample/sample_update.php?id=${id}`
    title.textContent = 'Actualizar Muestra'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadSample()
} else {

    form.action = '/php/actions/sample/sample_register.php'
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
            notificacion("Debe completar al menos un campo", 'warning')
            return
        }
    }

    if (code.value.trim() === '') {

        if (!is_update) {

            notificacion("El código es obligatorio", 'warning')
            return
        }
    } else {

        if (!registerValidator.sampleCodeInput(code, code_msg)) {
            notificacion("El código no es válido", 'error')
            return
        }
    }

    if (patient_document.value.trim() === '') {

        if (!is_update) {

            notificacion("La cédula del paciente es obligatoria", 'warning')
            return
        }
    } else {

        if (!registerValidator.documentIdInput(patient_document, document_msg)) {

            notificacion("La cédula del paciente no es válida", 'error')
            return
        }
    }

    if (type.value.trim() === '') {

        if (!is_update) {

            notificacion("El tipo es obligatorio", 'warning')
            return
        }
    } else {

        if (!registerValidator.selectsInput(type, type_msg)) {

            notificacion("El tipo no es válido", 'error')
            return
        }
    }
    
    if (description.value.trim() === '') {

        if (!is_update) {

            notificacion("La descripción es obligatoria", 'warning')
            return
        }
    } else {

        if (!registerValidator.largeStringsInput(description, description_msg)) {

            notificacion("La descripción no es válida", 'error')
            return
        }
    }

    form.submit()
})

lucide.createIcons()