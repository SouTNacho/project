import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#companion_form")
const title = document.querySelector('h2')
const is_update = Number.isInteger(id) && id > 0

async function loadCompanion() {

    try {

        const response = await fetch(`/php/actions/companion/get_companion.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el acompañante')
            return
        }

        const item = result.item

        companion_document.placeholder = item.cedula
        first_name.placeholder = item.nombre
        last_name.placeholder = item.apellido

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const companion_document = document.querySelector("#companion_document")
const first_name = document.querySelector("#companion_first_name")
const last_name = document.querySelector("#companion_last_name")
const btn = document.querySelector("#companion_btn")

const document_msg = document.querySelector("#companion_document_msg")
const first_name_msg = document.querySelector("#companion_first_name_msg")
const last_name_msg = document.querySelector("#companion_last_name_msg")
// const btn_msg = document.querySelector("#companion_btn_msg")
const inputs = [companion_document, first_name, last_name]

if (is_update) {

    form.action = `/php/actions/companion/companion_form.php?id=${id}`
    title.textContent = 'Actualizar Acompañante'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadCompanion()
} else {

    form.action = '/php/actions/companion/companion_form.php'
    title.textContent = 'Registrar Acompañante'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}


companion_document.addEventListener('input', () => {

    if (companion_document.value.trim() !== '') {

        return registerValidator.documentIdInput(companion_document, document_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(companion_document, document_msg)
    }
    
    return formTools.setInvalid(companion_document, document_msg, 'Este campo es obligatorio')
})

first_name.addEventListener('input', () => {

    if (first_name.value.trim() !== '') {

        return registerValidator.namesInput(first_name, first_name_msg)
    }

    if(is_update) {

        return formTools.setValid(first_name, first_name_msg)
    }

    return formTools.setInvalid(first_name, first_name_msg, 'Este campo es obligatorio')
})

last_name.addEventListener('input', () => {

    if (last_name.value.trim() !== '') {

        return registerValidator.namesInput(last_name, last_name_msg)
    }

    if(is_update) {

        return formTools.setValid(last_name, last_name_msg)
    }

    return formTools.setInvalid(last_name, last_name_msg, 'Este campo es obligatorio')
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

    if (companion_document.value.trim() === '') {

        if (!is_update) {

            alert("La cédula es obligatoria")
            return
        }
    } else {

        if (!registerValidator.documentIdInput(companion_document, document_msg)) {
            alert("La cédula no es válida")
            return
        }
    }

    if (first_name.value.trim() === '') {

        if (!is_update) {

            alert("El nombre es obligatorio")
            return
        }
    } else {

        if (!registerValidator.namesInput(first_name, first_name_msg)) {

            alert("El nombre no es válido")
            return
        }
    }

    if (last_name.value.trim() === '') {

        if (!is_update) {

            alert("El apellido es obligatorio")
            return
        }
    } else {

        if (!registerValidator.namesInput(last_name, last_name_msg)) {

            alert("El apellido no es válido")
            return
        }
    }

    form.submit()
})

lucide.createIcons()