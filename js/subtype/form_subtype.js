import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#subtypes_form")
const is_update = Number.isInteger(id) && id > 0
const title = document.querySelector('h2')

async function loadsubtype() {

    try {

        const response = await fetch(`/php/actions/subtype/get_subtype.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el subtipo')
            return
        }

        const item = result.item

        name.placeholder = item.nombre
        type[0].textContent = Number(item.tipo) === 1 ? "Biológico" : "No Biológico"

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const name = document.querySelector("#subtype_name")
const type = document.querySelector("#subtype_type")
const btn = document.querySelector("#subtype_btn")

const name_msg = document.querySelector("#subtype_name_msg")
const type_msg = document.querySelector("#subtype_type_msg")
//const btn_msg = document.querySelector("#subtype_btn_msg")
const inputs = [name]

if (is_update) {

    form.action = `/php/actions/subtype/subtype_update.php?id=${id}`
    title.textContent = 'Actualizar Subtipo'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadsubtype()
} else {

    form.action = '/php/actions/subtype/subtype_register.php'
    title.textContent = 'Registrar Subtipo'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


name.addEventListener('input', () => {

    if (name.value.trim() !== '') {

        return registerValidator.stringsInput(name, name_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(name, name_msg)
    }
    
    return formTools.setInvalid(name, name_msg, 'Este campo es obligatorio')
})

type.addEventListener('change', () => {

    if (type.value.trim() !== '') {

        return registerValidator.selectsInput(type, type_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(type, type_msg)
    }
    
    return formTools.setInvalid(type, type_msg, 'Este campo es obligatorio')
})


form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (is_update) {

        const has_name = name.value.trim() !== ''
        const has_type = type.value.trim() !== ''

        if (!has_name && !has_type) {
            alert("Debe completar al menos un campo")
            return
        }

        if (has_type && !has_name) {
            alert("Debe completar el nombre para modificar el tipo")
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

    form.submit()
})


lucide.createIcons()