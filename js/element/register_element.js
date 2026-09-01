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

const register_form = document.querySelector("#element_register_form")

const cod = document.querySelector("#element_cod")
const name = document.querySelector("#element_name")
const type = document.querySelector("#element_type")
const subtype = document.querySelector("#element_subtype")
const description = document.querySelector("#element_description")
const register_btn = document.querySelector("#element_register_btn")

const cod_msg = document.querySelector("#element_cod_msg")
const name_msg = document.querySelector("#element_name_msg")
const type_msg = document.querySelector("#element_type_msg")
const subtype_msg = document.querySelector("#element_subtype_msg")
const description_msg = document.querySelector("#element_description_msg")
const register_btn_msg = document.querySelector("#element_register_btn_msg")

cod.addEventListener('input', () => registerValidator.idElementInput(cod, cod_msg))
name.addEventListener('input', () => registerValidator.stringsInput(name, name_msg))
subtype.addEventListener('change', () => registerValidator.selectsInput(subtype, subtype_msg))
description.addEventListener('input', () => registerValidator.largeStringsInput(description, description_msg))

type.addEventListener('change', () => {
    registerValidator.selectsInput(type, type_msg)
    if (type.value.trim() === "Biológico") createOptions(elementSubtypes.bio, subtype)
    if (type.value.trim() === "No Biológico") createOptions(elementSubtypes.non_bio, subtype)
})

register_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.idElementInput(cod, cod_msg) ||
        !registerValidator.stringsInput(name, name_msg) ||
        !registerValidator.selectsInput(type, type_msg) ||
        !registerValidator.selectsInput(subtype, subtype_msg) ||
        !registerValidator.largeStringsInput(description, description_msg)
    ) {

        alert("Debe completar todos los campos")
        return
    }

    register_form.submit()
})