import { createViewActions } from '/js/functions.js'

const id = Number(new URLSearchParams(window.location.search).get('id'))
const view_container = document.querySelector('.view-container')

async function loadData(container) {

    try {
    
        const response = await fetch(`/php/actions/cellphone/get_cellphone.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Ha ocurrido un error en la petic. del teléfono', result.message)
            return
        }

        if (!result.item) {

            const message_container = document.createElement('div')
            message_container.classList.add('message-container')

            const message = document.createElement('p')
            message.textContent = "Error al cargar el teléfono"

            message_container.append(message)
            container.append(message_container)
            return
        }

        const item = result.item
        const other_response = await fetch(`/php/actions/employee/get_employee.php?id=${item.id_funcionario}`)
        const other_result = await other_response.json()

        if (!other_response.ok || !other_result.success) {

            console.error('Ha ocurrido un error en la petic. del teléfono', result.message)
            return
        }
            
        container.innerHTML = 
        `
            <div>
            <h2>Visualizar teléfono</h2>
            <p><span>Cédula:</span> ${other_result.item.cedula}</p>
            <p><span>Teléfono:</span> ${item.telefono}</p>
            </div>
        `

        createViewActions(container, '/php/pages/cellphone/manage_cellphones.php',
            `/php/pages/cellphone/cellphone_form.php?id=${id}`)

    } catch (error) {

        // DESPUES SACAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

if (!Number.isInteger(id) || id <= 0) {
    
    const div = document.createElement('div')

    div.classList.add('message-container')
    div.innerHTML = '<p>Error: ID inválido.</p>'

    const back = document.createElement('button')

    back.innerHTML = `<i data-lucide="arrow-left"></i>Volver `
    back.classList.add('back-btn')
    div.append(back)

    view_container.append(div)
    lucide.createIcons()

    back.addEventListener('click', () =>
        location.href = '/php/pages/cellphone/manage_cellphones.php')

} else {
    
    await loadData(view_container)
}