import formTools from "/js/library.js"

// Mismas reglas que subtype_validations.php (el servidor siempre vuelve a validar)
const NAME_MAX_LENGTH = 80
const NAME_PATTERN = /^[\p{L}\p{N} .,\-()\/&]+$/u

const id = Number(new URLSearchParams(window.location.search).get('id'))
const is_update = Number.isInteger(id) && id > 0

const form = document.querySelector('#subtype_form')
const title = document.querySelector('#subtype_title')
const type_select = document.querySelector('#subtype_type')
const name_input = document.querySelector('#subtype')
const btn = document.querySelector('#subtype_btn')

const type_msg = document.querySelector('#subtype_type_msg')
const name_msg = document.querySelector('#subtype_msg')
const btn_msg = document.querySelector('#subtype_btn_msg')


function validateType() {

    // En actualización el tipo no se puede cambiar (el select queda deshabilitado)
    if (type_select.disabled) {
        return true
    }

    if (!['1', '2'].includes(type_select.value)) {
        formTools.setInvalid(type_select, type_msg, 'Este campo es obligatorio')
        return false
    }

    formTools.setValid(type_select, type_msg)
    return true
}

function validateName() {

    const value = name_input.value.trim().replace(/\s+/g, ' ')

    if (value === '') {
        formTools.setInvalid(name_input, name_msg, 'Este campo es obligatorio')
        return false
    }

    if (value.length > NAME_MAX_LENGTH) {
        formTools.setInvalid(name_input, name_msg,
            `El nombre no puede superar los ${NAME_MAX_LENGTH} caracteres`)
        return false
    }

    if (!NAME_PATTERN.test(value)) {
        formTools.setInvalid(name_input, name_msg,
            'Solo se permiten letras, números, espacios y . , - ( ) / &')
        return false
    }

    formTools.setValid(name_input, name_msg)
    return true
}

async function loadSubtype() {

    try {

        const response = await fetch(`/php/actions/element/get_element_subtype_by_id.php?subtype_id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {
            throw new Error(result.message || 'No se pudo cargar el subtipo.')
        }

        // Un solo campo editable: se precarga el nombre actual
        type_select.value = String(result.item.tipo)
        type_select.disabled = true
        name_input.value = result.item.nombre

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)

        btn.disabled = true
        btn_msg.textContent = 'No se pudo cargar el subtipo.'
    }
}


if (is_update) {

    form.action = `/php/actions/element/subtype_update.php?id=${id}`
    title.textContent = 'Actualizar Subtipo'
    btn.value = 'ACTUALIZAR'

    await loadSubtype()
}

type_select.addEventListener('change', validateType)
name_input.addEventListener('input', validateName)

form.addEventListener('submit', (e) => {
    e.preventDefault()

    // Se ejecutan las dos para que se muestren todos los mensajes a la vez
    const type_ok = validateType()
    const name_ok = validateName()

    if (!type_ok || !name_ok) {
        alert('Revise los campos marcados.')
        return
    }

    form.submit()
})