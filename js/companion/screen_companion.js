import { loadStateName, getStates, createViewActions } from '/js/functions.js'

const id = Number(new URLSearchParams(window.location.search).get('id'))
const view_container = document.querySelector('.view-container')

async function loadData(container) {

    try {
    
        const response = await fetch(`/php/actions/companion/get_companion.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Ha ocurrido un error en la petic. del acompañante', result.message)
            return
        }

        if (!result.item) {

            const message_container = document.createElement('div')
            message_container.classList.add('message-container')

            const message = document.createElement('p')
            message.textContent = "Error al cargar el acompañante"

            message_container.append(message)
            container.append(message_container)
            return
        }

        const item = result.item
        const state_id = Number(item.id_estado_acompaniante)
        const states = await getStates('/php/actions/companion/get_companions_states.php')
            
        container.innerHTML = 
        `
            <div>
            <h2>Visualizar acompañante</h2>
            <p><span>Cédula:</span> ${item.cedula}</p>
            <p><span>Nombre:</span> ${item.nombre}</p>
            <p><span>Apellido:</span> ${item.apellido}</p>
            <p><span>Estado:</span> ${loadStateName(state_id, states, 'id_estado_acompaniante')}</p>
            </div>
        `

        createViewActions(container, '/php/pages/companion/manage_companions.php',
            `/php/pages/companion/companion_form.php?id=${id}`)

    } catch (error) {

        // DESPUES SACAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

if (!Number.isInteger(id) || id <= 0) {
    
    const div = document.createElement('div')

    div.classList.add('message-container')
    div.innerHTML = '<p>Error: ID inválida.</p>'

    const back = document.createElement('button')

    back.innerHTML = `<i data-lucide="arrow-left"></i>Volver `
    back.classList.add('back-btn')
    div.append(back)

    view_container.append(div)
    lucide.createIcons()

    back.addEventListener('click', () =>
        location.href = '/php/pages/companion/manage_companions.php')

} else {
    
    await loadData(view_container)
}