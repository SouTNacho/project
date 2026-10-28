import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#element_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

const defaultSubtypes = {
    bio: [
        { id_subtipo_elemento: 1, nombre: 'Medicamentos e insumos de origen biológico' },
        { id_subtipo_elemento: 2, nombre: 'Productos derivados de la sangre' },
        { id_subtipo_elemento: 3, nombre: 'Productos derivados de tejidos' },
        { id_subtipo_elemento: 4, nombre: 'Material biológico para investigación' },
        { id_subtipo_elemento: 5, nombre: 'Otros' },
    ],

    non_bio: [
        { id_subtipo_elemento: 1, nombre: 'Medicamentos e insumos de origen no biológico' },
        { id_subtipo_elemento: 2, nombre: 'Instrumental médico' },
        { id_subtipo_elemento: 3, nombre: 'Equipamiento médico' },
        { id_subtipo_elemento: 4, nombre: 'Equipamiento tecnológico' },
        { id_subtipo_elemento: 5, nombre: 'Mobiliario' },
        { id_subtipo_elemento: 6, nombre: 'Material de mantenimiento' },
        { id_subtipo_elemento: 7, nombre: 'Material de limpieza' },
        { id_subtipo_elemento: 8, nombre: 'Elementos de protección' },
        { id_subtipo_elemento: 9, nombre: 'Otros' }
    ]
}

async function loadSubtypes(type) {

    const typeOption = type === 'Biológico' ? 'bio' : 'non_bio'
    const typeOptionNumber = type === 'Biológico' ? 1 : 2
    
    try {

        const response = await fetch(`/php/actions/element/get_subtypes.php?type=${typeOptionNumber}`)
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Error en la solicitud')
            return defaultSubtypes.typeOption
        }

        return result.item
    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return defaultSubtypes.typeOption
    }
}

async function loadElement() {

    try {

        const response = await fetch(`/php/actions/element/get_element.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el elemento')
            return
        }

        const item = result.item

        code.placeholder = item.codigo
        name.placeholder = item.nombre
        description.placeholder = item.descripcion
        type[0].textContent = item.tipo

        const subResponse = await fetch(`/php/actions/subtype/get_subtype.php?id=${item.id_subtipo_elemento}`)
        const subResult = await subResponse.json()

        if (!subResponse.ok || !subResult.success) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el subtipo')
            return
        }

        subtype[0].textContent = subResult.item.nombre

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
        opt.value = option.id_subtipo_elemento

        item.append(opt)
    })
}

const code = document.querySelector("#element_code")
const name = document.querySelector("#element_name")
const type = document.querySelector("#element_type")
const subtype = document.querySelector("#element_subtype")
const description = document.querySelector("#element_description")
const btn = document.querySelector("#element_btn")

const code_msg = document.querySelector("#element_code_msg")
const name_msg = document.querySelector("#element_name_msg")
const type_msg = document.querySelector("#element_type_msg")
const subtype_msg = document.querySelector("#element_subtype_msg")
const description_msg = document.querySelector("#element_description_msg")
//const btn_msg = document.querySelector("#element_btn_msg")
const inputs = [code, name, type, subtype, description]

if (is_update) {

    form.action = `/php/actions/element/element_update.php?id=${id}`
    title.textContent = 'Actualizar Elemento'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadElement()
} else {

    form.action = '/php/actions/element/element_register.php'
    title.textContent = 'Registrar Elemento'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


code.addEventListener('input', () => {

    if (code.value.trim() !== '') {

        return registerValidator.idElementInput(code, code_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(code, code_msg)
    }
    
    return formTools.setInvalid(code, code_msg, 'Este campo es obligatorio')
})

name.addEventListener('input', () => {

    if (name.value.trim() !== '') {

        return registerValidator.stringsInput(name, name_msg)
    }

    if(is_update) {

        return formTools.setValid(name, name_msg)
    }

    return formTools.setInvalid(name, name_msg, 'Este campo es obligatorio')
})

type.addEventListener('change', async () => {

    if (type.value.trim() !== '') {

        if(!registerValidator.selectsInput(type, type_msg)) {

            return
        }

        const elementSubtypes = await loadSubtypes(type.value.trim())
        createOptions(elementSubtypes, subtype)
        return formTools.setInvalid(subtype, subtype_msg,
            'Si selecciona tipo el subtipo es obligatorio')
    }

    subtype.innerHTML = '<option value="">Seleccione una opción</option>'

    if (is_update) {

        formTools.setValid(subtype, subtype_msg)
        return formTools.setValid(type, type_msg)
    }

    formTools.setInvalid(subtype, subtype_msg, 'Este campo es obligatorio')
    return formTools.setInvalid(type, type_msg, 'Este campo es obligatorio')
})

subtype.addEventListener('change', () => {

    if (type.value.trim() === '') {

        if (subtype.value.trim() !== '') {

            return formTools.setInvalid(subtype, subtype_msg,
                'Para seleccionar el subtipo debe seleccionar el tipo')
        }

        return formTools.setValid(subtype, subtype_msg)
    }

    if (subtype.value.trim() === '') {

        return formTools.setInvalid(subtype, subtype_msg,
            'Este campo es obligatorio')
    }

    return registerValidator.selectsInput(subtype, subtype_msg)
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

        if (!registerValidator.idElementInput(code, code_msg)) {
            notificacion("El código no es válido", 'error')
            return
        }
    }

    if (name.value.trim() === '') {

        if (!is_update) {

            notificacion("El nombre es obligatorio", 'warning')
            return
        }
    } else {

        if (!registerValidator.stringsInput(name, name_msg)) {

            notificacion("El nombre no es válido", 'error')
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

    if (type.value.trim() === '') {

        if (subtype.value.trim() !== '') {
            notificacion("Para seleccionar el subtipo, el tipo es obligatorio", 'warning')
            return
        }

        if (!is_update) {

            notificacion("El tipo es obligatorio", 'warning')
            return
        }
    } else {

        if (subtype.value.trim() === '') {

            notificacion('El subtipo es obligatorio', 'warning')
            return
        }

        if (!registerValidator.selectsInput(type, type_msg) ||
            !registerValidator.selectsInput(subtype, subtype_msg)) {

            notificacion("El tipo y subtipo no son válidos", 'error')
            return
        }
        
    }

    form.submit()
})

lucide.createIcons()