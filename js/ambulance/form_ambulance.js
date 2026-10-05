import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#ambulance_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

async function loadAmbulance() {

    try {

        const response = await fetch(`/php/actions/ambulance/get_ambulance.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar la ambulancia')
            return
        }

        const item = result.item

        registration.placeholder = item.matricula
        brand.placeholder = item.marca
        model.placeholder = item.modelo
        year.placeholder = item.anio
        description.placeholder = item.descripcion

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const registration = document.querySelector("#ambulance_registration")
const brand = document.querySelector("#ambulance_brand")
const model = document.querySelector("#ambulance_model")
const year = document.querySelector("#ambulance_year")
const description = document.querySelector("#ambulance_description")
const btn = document.querySelector("#ambulance_btn")

const registration_msg = document.querySelector("#ambulance_registration_msg")
const brand_msg = document.querySelector("#ambulance_brand_msg")
const model_msg = document.querySelector("#ambulance_model_msg")
const year_msg = document.querySelector("#ambulance_year_msg")
const description_msg = document.querySelector("#ambulance_description_msg")
//const btn_msg = document.querySelector("#ambulance_btn_msg")
const inputs = [registration, brand, model, year, description]

if (is_update) {

    form.action = `/php/actions/ambulance/ambulance_update.php?id=${id}`
    title.textContent = 'Actualizar Ambulancia'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadAmbulance()
} else {

    form.action = '/php/actions/ambulance/ambulance_register.php'
    title.textContent = 'Registrar Ambulancia'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


registration.addEventListener('input', () => {

    if (registration.value.trim() !== '') {

        return registerValidator.idAmbulanceInput(registration, registration_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(registration, registration_msg)
    }
    
    return formTools.setInvalid(registration, registration_msg, 'Este campo es obligatorio')
})

brand.addEventListener('input', () => {

    if (brand.value.trim() !== '') {

        return registerValidator.stringsInput(brand, brand_msg)
    }

    if(is_update) {

        return formTools.setValid(brand, brand_msg)
    }

    return formTools.setInvalid(brand, brand_msg, 'Este campo es obligatorio')
})

model.addEventListener('input', () => {

    if (model.value.trim() !== '') {

        return registerValidator.stringsInput(model, model_msg)
    }

    if(is_update) {

        return formTools.setValid(model, model_msg)
    }

    return formTools.setInvalid(model, model_msg, 'Este campo es obligatorio')
})

year.addEventListener('input', () => {

    if (year.value.trim() !== '') {

        return registerValidator.yearInput(year, year_msg)
    }

    if(is_update) {

        return formTools.setValid(year, year_msg)
    }

    return formTools.setInvalid(year, year_msg, 'Este campo es obligatorio')
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

    if (registration.value.trim() === '') {

        if (!is_update) {

            alert("La matricula es obligatoria")
            return
        }
    } else {

        if (!registerValidator.idAmbulanceInput(registration, registration_msg)) {
            alert("La matricula no es válida")
            return
        }
    }

    if (brand.value.trim() === '') {

        if (!is_update) {

            alert("La marca es obligatoria")
            return
        }
    } else {

        if (!registerValidator.stringsInput(brand, brand_msg)) {

            alert("La marca no es válida")
            return
        }
    }

    if (model.value.trim() === '') {

        if (!is_update) {

            alert("El modelo es obligatorio")
            return
        }
    } else {

        if (!registerValidator.stringsInput(model, model_msg)) {

            alert("El modelo no es válido")
            return
        }
    }

    if (year.value.trim() === '') {

        if (!is_update) {

            alert("El año es obligatorio")
            return
        }
    } else {

        if (!registerValidator.yearInput(year, year_msg)) {

            alert("El año no es válido")
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

    form.submit()
})

lucide.createIcons()