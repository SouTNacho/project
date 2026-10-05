import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#ubication_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

async function loadUbication() {

    try {

        const response = await fetch(`/php/actions/ubication/get_ubication.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar la ubicación')
            return
        }

        const item = result.item

        name.placeholder = item.nombre
        address.placeholder = item.direccion
        latitude.placeholder = item.latitud
        longitude.placeholder = item.longitud

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const name = document.querySelector("#ubication_name")
const address = document.querySelector("#ubication_address")
const latitude = document.querySelector("#ubication_latitude")
const longitude = document.querySelector("#ubication_longitude")
const btn = document.querySelector("#ubication_btn")

const name_msg = document.querySelector("#ubication_name_msg")
const address_msg = document.querySelector("#ubication_address_msg")
const latitude_msg= document.querySelector("#ubication_latitude_msg")
const longitude_msg = document.querySelector("#ubication_longitude_msg")
const btn_msg = document.querySelector("#ubication_btn_msg")
//const btn_msg = document.querySelector("#ubication_btn_msg")
const inputs = [name, address, latitude, longitude]

if (is_update) {

    form.action = `/php/actions/ubication/ubication_update.php?id=${id}`
    title.textContent = 'Actualizar Ubicación'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadUbication()
} else {

    form.action = '/php/actions/ubication/ubication_register.php'
    title.textContent = 'Registrar Ubicación'
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

address.addEventListener('input', () => {

    if (address.value.trim() !== '') {

        return registerValidator.stringsInput(address, address_msg)
    }

    if(is_update) {

        return formTools.setValid(address, address_msg)
    }

    return formTools.setInvalid(address, address_msg, 'Este campo es obligatorio')
})

latitude.addEventListener('input', () => {

    if (latitude.value.trim() !== '') {

        return registerValidator.selectsInput(latitude, latitude_msg)
    }

    if(is_update) {

        return formTools.setValid(latitude, latitude_msg)
    }

    return formTools.setInvalid(latitude, latitude_msg, 'Este campo es obligatorio')
})

longitude.addEventListener('input', () => {

    if (longitude.value.trim() !== '') {

        return registerValidator.selectsInput(longitude, longitude_msg)
    }

    if(is_update) {

        return formTools.setValid(longitude, longitude_msg)
    }

    return formTools.setInvalid(longitude, longitude_msg, 'Este campo es obligatorio')
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

    if (address.value.trim() === '') {

        if (!is_update) {

            alert("La dirección es obligatoria")
            return
        }
    } else {

        if (!registerValidator.stringsInput(address, address_msg)) {

            alert("La dirección no es válida")
            return
        }
    }

    if (latitude.value.trim() === '') {

        if (!is_update) {

            alert("El latitud es obligatorio")
            return
        }
    } else {

        if (!registerValidator.selectsInput(latitude, latitude_msg)) {

            alert("El latitud no es válido")
            return
        }
    }

    if (longitude.value.trim() === '') {

        if (!is_update) {

            alert("El longitud es obligatorio")
            return
        }
    } else {

        if (!registerValidator.selectsInput(longitude, longitude_msg)) {

            alert("El longitud no es válido")
            return
        }
    }

    form.submit()
})

lucide.createIcons()