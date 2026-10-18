import { createViewActions } from '/js/functions.js'

const code = new URLSearchParams(window.location.search).get('code')
const view_container = document.querySelector('.view-container')

async function loadData(container) {

    try {
    
        const response = await fetch(`/php/actions/administrative/get_administrative.php?code=${code}`)
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Ha ocurrido un error en la petic. del administrativo', result.message)
            return
        }

        if (!result.item) {

            const message_container = document.createElement('div')
            message_container.classList.add('message-container')

            const message = document.createElement('p')
            message.textContent = "Error al cargar el administrativo"

            message_container.append(message)
            container.append(message_container)
            return
        }

        const item = result.item
        const other_response = await fetch(`/php/actions/employee/get_employee.php?id=${item.id_funcionario}`)
        const other_result = await other_response.json()

        if (!other_response.ok || !other_result.success) {

            console.error('Ha ocurrido un error en la petic. del administrativo', result.message)
            return
        }

        let permisos = item.permisos
        switch(permisos) {
            case 'Low':
                permisos = 'Bajos'
                break
            case 'Mid':
                permisos = 'Medios'
                break
            case 'High':
                permisos = 'Altos'
                break
        }
            
        container.innerHTML = 
        `
            <div>
            <h2>Visualizar Administrativo</h2>
            <p><span>Código:</span> ${item.codigo}</p>
            <p><span>Cédula:</span> ${other_result.item.cedula}</p>
            <p><span>Permisos:</span> ${permisos}</p>
            </div>
        `

        createViewActions(container, '/php/pages/administrative/manage_administratives.php',
            `/php/pages/administrative/administrative_form.php?code=${code}`)

    } catch (error) {

        // DESPUES SACAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

if (!code || code.trim() === '') {
    
    const div = document.createElement('div')

    div.classList.add('message-container')
    div.innerHTML = '<p>Error: Código inválido.</p>'

    const back = document.createElement('button')

    back.innerHTML = `<i data-lucide="arrow-left"></i>Volver `
    back.classList.add('back-btn')
    div.append(back)

    view_container.append(div)
    lucide.createIcons()

    back.addEventListener('click', () =>
        location.href = '/php/pages/administrative/manage_administratives.php')

} else {
    
    await loadData(view_container)
}