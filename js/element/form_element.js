import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#element_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

const defaultSubtypes = {
    bio: [
        "Medicamentos e insumos de origen biológico",
        "Productos derivados de la sangre",
        "Productos derivados de tejidos",
        "Material biológico para investigación",
        "Otros"
    ],

    non_bio: [
        "Medicamentos e insumos de origen no biológico",
        "Instrumental médico",
        "Equipamiento médico",
        "Equipamiento tecnológico",
        "Mobiliario",
        "Material de mantenimiento",
        "Material de limpieza",
        "Elementos de protección",
        "Otros"
    ]
}

async function loadSubtypes() {
    
    try {

        const response = await fetch('/php/actions/element/get_subtypes.php')
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Error en la solicitud')
            return defaultSubtypes
        }

        return result.item
    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return defaultSubtypes
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

        type.options[0].textContent = item.tipo

        if (item.tipo === "Biológico") {
            createOptions(elementSubtypes.bio, subtype)
        }

        if (item.tipo === "No Biológico") {
            createOptions(elementSubtypes.non_bio, subtype)
        }

        subtype.options[0].textContent = item.subtipo

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

function createOptions(options, item) {
    item.innerHTML = '<option value="">Seleccione una opción</option>'

    options.forEach(option => {
        
        const opt = document.createElement('option')
        opt.textContent = option
        opt.value = option

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

const elementSubtypes = await loadSubtypes()

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

type.addEventListener('change', () => {

    if (type.value.trim() !== '') {

        if(!registerValidator.selectsInput(type, type_msg)) {

            return
        }

        if (type.value === "Biológico") {

            createOptions(elementSubtypes.bio, subtype)
        }

        if (type.value === "No Biológico") {

            createOptions(elementSubtypes.non_bio, subtype)
        }

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

        if (!registerValidator.idElementInput(code, code_msg)) {
            alert("El código no es válido")
            return
        }
    }

    if (name.value.trim() === '') {

        if (!is_update) {

            alert("El nombre es obligatorio")
            return
        }
    } else {

        if (!registerValidator.stringsInput(name, name_msg)) {

            alert("El nombre no es válido")
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

    if (type.value.trim() === '') {

        if (subtype.value.trim() !== '') {
            alert("Para seleccionar el subtipo, el tipo es obligatorio")
            return
        }

        if (!is_update) {

            alert("El tipo es obligatorio")
            return
        }
    } else {

        if (subtype.value.trim() === '') {

            alert('El subtipo es obligatorio')
            return
        }

        if (!registerValidator.selectsInput(type, type_msg) ||
            !registerValidator.selectsInput(subtype, subtype_msg)) {

            alert("El tipo y subtipo no son válidos")
            return
        }
        
    }

    form.submit()
})

lucide.createIcons()