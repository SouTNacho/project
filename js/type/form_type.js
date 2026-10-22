import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#types_form")
const is_update = Number.isInteger(id) && id > 0
const title = document.querySelector('h2')

async function loadSubtype() {

    try {

        const response = await fetch(`/php/actions/type/get_type.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el tipo')
            return
        }

        const item = result.item

        name.placeholder = item.nombre

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

const name = document.querySelector("#type_name")
const btn = document.querySelector("#type_btn")

const name_msg = document.querySelector("#type_name_msg")
//const btn_msg = document.querySelector("#type_btn_msg")
const inputs = [name]

if (is_update) {

    form.action = `/php/actions/type/type_update.php?id=${id}`
    title.textContent = 'Actualizar Tipo'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadSubtype()
} else {

    form.action = '/php/actions/type/type_register.php'
    title.textContent = 'Registrar Tipo'
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

    form.submit()
})

lucide.createIcons()