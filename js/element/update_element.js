import formTools from "/js/library.js"
const { registerValidator } = formTools

const elementSubtypes = {
    bio: 
    [
        "Medicamentos e insumos de origen biológico",
        "Productos derivados de la sangre",
        "Productos derivados de tejidos",
        "Material biológico para investigación",
        "Otros"
    ],

    non_bio: 
    [
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

function createOptions(options, item) {
    item.innerHTML = '<option value="">Seleccione una opción</option>'

    options.forEach(option => {
        
        const opt = document.createElement('option')
        opt.textContent = option
        opt.value = option

        item.append(opt)
    })
}

const update_form = document.querySelector("#element_update_form")

const current_cod = document.querySelector("#element_current_cod")
const new_cod = document.querySelector("#element_new_cod")
const name = document.querySelector("#element_name")
const type = document.querySelector("#element_type")
const subtype = document.querySelector("#element_subtype")
const description = document.querySelector("#element_description")
const update_btn = document.querySelector("#element_update_btn")

const current_cod_msg = document.querySelector("#element_current_cod_msg")
const new_cod_msg = document.querySelector("#element_new_cod_msg")
const name_msg = document.querySelector("#element_name_msg")
const type_msg = document.querySelector("#element_type_msg")
const subtype_msg = document.querySelector("#element_subtype_msg")
const description_msg = document.querySelector("#element_description_msg")
const update_btn_msg = document.querySelector("#element_update_btn_msg")

current_cod.addEventListener('input', () =>
    registerValidator.idElementInput(current_cod, current_cod_msg))

new_cod.addEventListener('input', () => {

    if (new_cod.value.trim() !== '') {
        registerValidator.idElementInput(new_cod, new_cod_msg)

    } else {
        return formTools.setValid(new_cod, new_cod_msg)

    }
})

name.addEventListener('input', () => {

    if (name.value.trim() !== '') {
        registerValidator.stringsInput(name, name_msg)

    } else {
        return formTools.setValid(name, name_msg)

    }
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
        registerValidator.largeStringsInput(description, description_msg)

    } else {
        return formTools.setValid(description, description_msg)

    }
})

type.addEventListener('change', () => {

    if (type.value.trim() === '') {

        createOptions([], subtype)
        formTools.setValid(type, type_msg)
        return formTools.setValid(subtype, subtype_msg)
    }

    registerValidator.selectsInput(type, type_msg)

    if (type.value === "Biológico")
        createOptions(elementSubtypes.bio, subtype)

    if (type.value === "No Biológico")
        createOptions(elementSubtypes.non_bio, subtype)

    return formTools.setInvalid(subtype, subtype_msg,
        'Este campo es obligatorio')
})

update_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.idElementInput(current_cod, current_cod_msg)) {

        alert("El código actual es obligatorio")
        return
    }

    if (name.value.trim() !== "") {

        if (!registerValidator.stringsInput(name, name_msg)) {

            alert("El nombre debe ser válido o estar vacío")
            return
        }
    }

    if (new_cod.value.trim() !== "") {

        if (!registerValidator.idElementInput(new_cod, new_cod_msg)) {

            alert("El nuevo código debe ser válido o estar vacío")
            return
        }
    }

    if (description.value.trim() !== "") {

        if (!registerValidator.largeStringsInput(description, description_msg)) {

            alert("El nombre debe ser válido o estar vacío")
            return
        }
    }

    if (type.value.trim() !== '') {

        if (!registerValidator.selectsInput(type, type_msg) || !registerValidator.selectsInput(subtype, subtype_msg)) {

            alert("El tipo y subtipo seleccionados no son válidos")
            return
        }
        
    }

    if (type.value.trim() === '' && subtype.value.trim() !== '') {

            alert("Para modificar el subtipo debe seleccionar un tipo válido")
            return
    }

    update_form.submit()
})