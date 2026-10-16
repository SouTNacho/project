import { loadStateName, getStates, createViewActions, getAdministrative, getCopilot, getDriver } from '/js/functions.js'

const id = Number(new URLSearchParams(window.location.search).get('id'))
const view_container = document.querySelector('.view-container')

async function loadData(container) {

    try {
    
        const response = await fetch(`/php/actions/employee/get_employee.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Ha ocurrido un error en la petic. del empleado', result.message)
            return
        }

        if (!result.item) {

            const message_container = document.createElement('div')
            message_container.classList.add('message-container')

            const message = document.createElement('p')
            message.textContent = "Error al cargar el empleado"

            message_container.append(message)
            container.append(message_container)
            return
        }

        const item = result.item
        const state_id = Number(item.id_estado_funcionario)
        const states = await getStates('/php/actions/employee/get_employees_states.php')
            
        container.innerHTML = 
        `
            <div>
            <h2>Visualizar Empleado</h2>
            <p><span>Nombre:</span> ${item.nombre}</p>
            <p><span>Apellido:</span> ${item.apellido}</p>
            <p><span>Cédula:</span> ${item.cedula}</p>
            <p><span>Nacionalidad:</span> ${item.nacionalidad}</p>
            <p><span>Fecha de Nacimiento:</span> ${item.fecha_nacimiento}</p>
            <p><span>Departamento:</span> ${item.departamento}</p>
            <p><span>Localidad:</span> ${item.localidad}</p>
            <p><span>Dirección:</span> ${item.direccion}</p>
            <p><span>Número de Puerta:</span> ${item.numero_puerta}</p>
            <p><span>Email:</span> ${item.email}</p>
            <p><span>Fecha de Ingreso:</span> ${item.fecha_ingreso}</p>
            <p><span>Estado:</span> ${loadStateName(state_id, states, 'id_estado_funcionario')}</p>
            </div>
        `

        const administrative = await getAdministrative(`/php/actions/employee/get_administrative.php?id=${id}`)
        const copilot = await getCopilot(`/php/actions/employee/get_copilot.php?id=${id}`)
        const driver = await getDriver(`/php/actions/employee/get_driver.php?id=${id}`)

        if (administrative) {

            container.innerHTML += 
            `
                <div>
                <h2>Cargo Administrativo</h2>
                <p><span>Nombre:</span> ${item.codigo}</p>
                <p><span>Apellido:</span> ${item.permisos}</p>
                <p><span>Estado:</span> ${loadStateName(state_id, states, 'id_estado_funcionario')}</p>
                </div>
            `
        }

        if (copilot) {

            container.innerHTML += 
            `
                <div>
                <h2>Cargo Copiloto</h2>
                <p><span>Nombre:</span> ${item.codigo}</p>
                <p><span>Apellido:</span> ${item.especialidad}</p>
                <p><span>Estado:</span> ${loadStateName(state_id, states, 'id_estado_funcionario')}</p>
                </div>
            `
        }

        if (driver) {

            container.innerHTML += 
            `
                <div>
                <h2>Cargo Conductor</h2>
                <p><span>Nombre:</span> ${item.codigo}</p>
                <p><span>Apellido:</span> ${item.categoria_carnet}</p>
                <p><span>Estado:</span> ${loadStateName(state_id, states, 'id_estado_funcionario')}</p>
                </div>
            `
        }

        createViewActions(container, '/php/pages/employee/manage_employees.php',
            `/php/pages/employee/employee_form.php?id=${id}`)

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
        location.href = '/php/pages/employee/manage_employees.php')

} else {
    
    await loadData(view_container)
}