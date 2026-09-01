import formTools from "/js/library.js"
const { registerValidator } = formTools

const update_form = document.querySelector("#ambulance_update_form")

const current_registration = document.querySelector("#ambulance_current_registration")
const new_registration = document.querySelector("#ambulance_new_registration")
const brand = document.querySelector("#ambulance_brand")
const model = document.querySelector("#ambulance_model")
const year = document.querySelector("#ambulance_year")
const description = document.querySelector("#ambulance_description")
const update_btn = document.querySelector("#ambulance_update_btn")

const current_registration_msg = document.querySelector("#ambulance_current_registration_msg")
const new_registration_msg = document.querySelector("#ambulance_new_registration_msg")
const brand_msg = document.querySelector("#ambulance_brand_msg")
const model_msg = document.querySelector("#ambulance_model_msg")
const year_msg = document.querySelector("#ambulance_year_msg")
const description_msg = document.querySelector("#ambulance_description_msg")
const update_btn_msg = document.querySelector("#ambulance_update_btn_msg")

current_registration.addEventListener('input', () =>
    registerValidator.idAmbulanceInput(current_registration, current_registration_msg))

new_registration.addEventListener('input', () => {

    if (new_registration.value.trim() !== '') {
        registerValidator.idAmbulanceInput(new_registration, new_registration_msg)

    } else {
        return formTools.setValid(new_registration, new_registration_msg)
    }
})

brand.addEventListener('input', () => {

    if (brand.value.trim() !== '') {
        registerValidator.stringsInput(brand, brand_msg)

    } else {
        return formTools.setValid(brand, brand_msg)
    }
})

model.addEventListener('change', () => {

    if (brand.value.trim() !== '') {
        registerValidator.stringsInput(brand, brand_msg)

    } else {
        return formTools.setValid(brand, brand_msg)
    }
})

year.addEventListener('change', () => {

    if (brand.value.trim() !== '') {
        registerValidator.yearInput(brand, brand_msg)

    } else {
        return formTools.setValid(brand, brand_msg)
    }
})

description.addEventListener('input', () => {

    if (description.value.trim() !== '') {
        registerValidator.largeStringsInput(description, description_msg)

    } else {
        return formTools.setValid(description, description_msg)
    }
})

update_form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (!registerValidator.idAmbulanceInput(current_registration, current_registration_msg)) {

        alert("La matricula actual es obligatoria")
        return
    }

    if (new_registration.value.trim() !== "") {

        if (!registerValidator.idAmbulanceInput(new_registration, new_registration_msg)) {

            alert("La nueva matricula debe ser válida o estar vacía")
            return
        }
    }

    if (brand.value.trim() !== "") {

        if (!registerValidator.stringsInput(brand, brand_msg)) {

            alert("La marca debe ser válida o estar vacía")
            return
        }
    }

    if (model.value.trim() !== '') {

        if (!registerValidator.stringsInput(model, model_msg)) {

            alert("El modelo debe ser válido o estar vacío")
            return
        }
        
    }

    if (year.value.trim() !== '') {

        if (!registerValidator.yearInput(year, year_msg)) {

            alert("El año debe ser válido o estar vacío")
            return
        }
        
    }

    if (description.value.trim() !== "") {

        if (!registerValidator.largeStringsInput(description, description_msg)) {

            alert("La descripción debe ser válida o estar vacía")
            return
        }
    }

    update_form.submit()
})