import formTools from "/js/library.js"

const name_validation = 50
const pattern_name = /^[\p{L}\p{N} .,\-()\/&]+$/u

const id = Number(new URLSearchParams(window.location.search).get('id'))
const is_update = Number.isInteger(id) && id > 0

const form = document.querySelector('#sample_type_register_form')
const title = document.querySelector('#sample_type_title')
const name_input = document.querySelector('#sample_type')
const btn = document.querySelector('#sample_type_register_btn')

//const type_msg = document.querySelector('#type_type_msg')
const name_msg = document.querySelector('#sample_type_msg')
const btn_msg = document.querySelector('#sample_type_register_btn_msg')

/*
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
}*/

function validateName() {

    const value = name_input.value.trim().replace(/\s+/g, ' ')

    if (value === '') {
        formTools.setInvalid(name_input, name_msg, 'Este campo es obligatorio')
        return false
    }

    if (value.length > name_validation) {
        formTools.setInvalid(name_input, name_msg,
            `El nombre no puede superar los ${name_validation} caracteres`)
        return false
    }

    if (!pattern_name.test(value)) {
        formTools.setInvalid(name_input, name_msg,
            'Solo se permiten letras, números, espacios y . , - ( ) / &')
        return false
    }

    formTools.setValid(name_input, name_msg)
    return true
}

async function loadType() {

    try {

        const response = await fetch(`/php/actions/sample/get_type_by_id.php?type_id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {
            throw new Error(result.message || 'No se pudo cargar el tipo.')
        }

        // Un solo campo editable: se precarga el nombre actual

        name_input.value = result.item.nombre

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)

        btn.disabled = true
        btn_msg.textContent = 'No se pudo cargar el tipo.'
    }
}


if (is_update) {

    form.action = `/php/actions/sample/type_update.php?id=${id}`
    title.textContent = 'Actualizar Tipo'
    btn.value = 'ACTUALIZAR'

    await loadType()
}

name_input.addEventListener('input', validateName)

form.addEventListener('submit', (e) => {
    e.preventDefault()

    // Se ejecutan las dos para que se muestren todos los mensajes a la vez
    //const type_ok = validateType()
    const name_ok = validateName()

    if (!name_ok) {
        alert('Revise los campos marcados.')
        return
    }

    form.submit()
})