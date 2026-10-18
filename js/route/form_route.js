import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#route_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

async function loadRoute() {

    try {

        const response = await fetch(`/php/actions/route/get_route.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar la ruta')
            return
        }

        const item = result.item

        origin.placeholder = item.origen
        destination.placeholder = item.destino

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const origin = document.querySelector("#route_origin")
const destination = document.querySelector("#route_destination")
const btn = document.querySelector("#route_btn")

const origin_msg = document.querySelector("#route_origin_msg")
const destination_msg = document.querySelector("#route_destination_msg")
//const btn_msg = document.querySelector("#route_btn_msg")
const inputs = [origin, destination]

if (is_update) {

    form.action = `/php/actions/route/route_update.php?id=${id}`
    title.textContent = 'Actualizar Ruta'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadRoute()
} else {

    form.action = '/php/actions/route/route_register.php'
    title.textContent = 'Registrar Ruta'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}

origin.addEventListener('input', () => {

    if (origin.value.trim() !== '') {

        return registerValidator.stringsInput(origin, origin_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(origin, origin_msg)
    }
    
    return formTools.setInvalid(origin, origin_msg, 'Este campo es obligatorio')
})

destination.addEventListener('input', () => {

    if (destination.value.trim() !== '') {

        return registerValidator.stringsInput(destination, destination_msg)
    }

    if(is_update) {

        return formTools.setValid(destination, destination_msg)
    }

    return formTools.setInvalid(destination, destination_msg, 'Este campo es obligatorio')
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

    if (origin.value.trim() === '') {

        if (!is_update) {

            alert("El origen es obligatorio")
            return
        }
    } else {

        if (!registerValidator.stringsInput(origin, origin_msg)) {
            alert("El origen no es válido")
            return
        }
    }

    if (destination.value.trim() === '') {

        if (!is_update) {

            alert("El destino es obligatorio")
            return
        }
    } else {

        if (!registerValidator.stringsInput(destination, destination_msg)) {

            alert("El destino no es válido")
            return
        }
    }

    form.submit()
})

lucide.createIcons()