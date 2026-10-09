import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#element_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

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

const TYPE_NAMES = { 1: 'Biológico', 2: 'No Biológico' }
const EMPTY_OPTION = '<option value="">Seleccione una opción</option>'

// El select de tipo puede traer el id (1 / 2) o el texto (Biológico / No Biológico)
function getTypeId(value) {

    const text = String(value ?? '').trim().toLowerCase()

    if (text === '1' || text === 'biológico' || text === 'biologico') {
        return 1
    }

    if (text === '2' || text === 'no biológico' || text === 'no biologico') {
        return 2
    }

    return null
}

// Devuelve la lista de subtipos de un tipo, o null si no se pudo cargar
async function loadSubtypes(type_id) {

    if (type_id === null) {
        return null
    }

    try {

        const response = await fetch(`/php/actions/element/get_element_subtype_by_type.php?type=${type_id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !Array.isArray(result.item)) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar los subtipos')
            return null
        }

        return result.item
    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        return null
    }
}

// Cada opción guarda el id del subtipo (es lo que se envía y lo que guarda la base)
function createOptions(options, select) {

    select.innerHTML = EMPTY_OPTION

    options.forEach(option => {

        const opt = document.createElement('option')
        opt.textContent = option.nombre
        opt.value = option.id_subtipo

        select.append(opt)
    })
}

// Los valores del select de subtipo ahora son ids numéricos
function validateSubtype() {

    if (!/^[1-9]\d*$/.test(subtype.value)) {

        formTools.setInvalid(subtype, subtype_msg, 'Seleccione un subtipo válido')
        return false
    }

    formTools.setValid(subtype, subtype_msg)
    return true
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

        const type_id = getTypeId(item.tipo)

        type.options[0].textContent = item.nombre_tipo ?? TYPE_NAMES[type_id] ?? item.tipo

        // Se cargan los subtipos del tipo actual para poder cambiar solo el subtipo
        const subtypes = await loadSubtypes(type_id)

        if (subtypes) {

            createOptions(subtypes, subtype)

            const current = subtypes.find(s => String(s.id_subtipo) === String(item.subtipo))
            subtype.options[0].textContent = item.nombre_subtipo ?? (current ? current.nombre : item.subtipo)
            return
        }

        subtype.options[0].textContent = item.nombre_subtipo ?? item.subtipo

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

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

        const selected_type = type.value
        const subtypes = await loadSubtypes(getTypeId(selected_type))

        // Si mientras cargaba el usuario cambió de tipo, se descarta esta respuesta
        if (type.value !== selected_type) {

            return
        }

        if (subtypes === null) {

            subtype.innerHTML = EMPTY_OPTION
            return formTools.setInvalid(subtype, subtype_msg, 'No se pudieron cargar los subtipos')
        }

        createOptions(subtypes, subtype)

        if (subtypes.length === 0) {

            return formTools.setInvalid(subtype, subtype_msg,
                'No hay subtipos registrados para este tipo')
        }

        return formTools.setInvalid(subtype, subtype_msg,
            'Si selecciona tipo el subtipo es obligatorio')
    }

    subtype.innerHTML = EMPTY_OPTION

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

    return validateSubtype()
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

        if (!registerValidator.selectsInput(type, type_msg) || !validateSubtype()) {

            alert("El tipo y subtipo no son válidos")
            return
        }
        
    }

    form.submit()
})

lucide.createIcons()