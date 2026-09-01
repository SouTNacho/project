import formTools from "/js/library.js"
const { registerValidator } = formTools

const register_form = document.querySelector("#ambulance_register_form")

const registration = document.querySelector("#ambulance_registration")
const brand = document.querySelector("#ambulance_brand")
const model = document.querySelector("#ambulance_model")
const year = document.querySelector("#ambulance_year")
const description = document.querySelector("#ambulance_description")
const register_btn = document.querySelector("#ambulance_register_btn")

const registration_msg = document.querySelector("#ambulance_registration_msg")
const brand_msg = document.querySelector("#ambulance_brand_msg")
const model_msg = document.querySelector("#ambulance_model_msg")
const year_msg = document.querySelector("#ambulance_year_msg")
const description_msg = document.querySelector("#ambulance_description_msg")
const register_btn_msg = document.querySelector("#ambulance_register_btn_msg")

registration.addEventListener('input', () => registerValidator.idAmbulanceInput(registration, registration_msg))
brand.addEventListener('input', () => registerValidator.stringsInput(brand, brand_msg))
model.addEventListener('change', () => registerValidator.stringsInput(model, model_msg))
year.addEventListener('change', () => registerValidator.yearInput(year, year_msg))
description.addEventListener('input', () => registerValidator.largeStringsInput(description, description_msg))

register_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.idAmbulanceInput(registration, registration_msg) ||
        !registerValidator.stringsInput(brand, brand_msg) ||
        !registerValidator.stringsInput(model, model_msg) ||
        !registerValidator.yearInput(year, year_msg) ||
        !registerValidator.largeStringsInput(description, description_msg)
    ) {

        alert("Debe completar todos los campos")
        return
    }

    register_form.submit()
})