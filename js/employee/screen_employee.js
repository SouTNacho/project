import { loadStateName, getStates, createViewActions } from '/js/functions.js'

const id = Number(new URLSearchParams(window.location.search).get('id'))
const view_container = document.querySelector('.view-container')

function createDetailSection(title, fields) {
    const section = document.createElement('div')
    section.classList.add('employee-detail-section')

    const heading = document.createElement('h2')
    heading.textContent = title
    section.append(heading)

    for (const [label, value] of fields) {
        const row = document.createElement('p')
        const name = document.createElement('span')
        name.textContent = label
        row.append(name, document.createTextNode(
            value === null || value === undefined || value === '' ? 'Sin datos' : String(value)))
        section.append(row)
    }

    return section
}

async function getEmployeeRole(url) {
    const response = await fetch(url)
    const result = await response.json()

    if (!response.ok || !result.success) {
        throw new Error('No se pudo consultar el cargo.')
    }

    // Las acciones devuelven una cadena vacía si el funcionario no tiene el cargo.
    if (result.item === '' || result.item === null) return null
    if (!result.item || typeof result.item !== 'object' || Array.isArray(result.item)) {
        throw new Error('La respuesta del cargo no es válida.')
    }

    return result.item
}

async function loadData(container) {
    try {
        const response = await fetch(`/php/actions/employee/get_employee.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {
            throw new Error('No se pudo cargar el empleado.')
        }

        const item = result.item
        const states = await getStates('/php/actions/employee/get_employees_states.php')
        const state = loadStateName(Number(item.id_estado_funcionario), states, 'id_estado_funcionario')

        container.replaceChildren(createDetailSection('Visualizar Empleado', [
            ['Nombre:', item.nombre],
            ['Apellido:', item.apellido],
            ['Cédula:', item.cedula],
            ['Nacionalidad:', item.nacionalidad],
            ['Fecha de Nacimiento:', item.fecha_nacimiento],
            ['Departamento:', item.departamento],
            ['Localidad:', item.localidad],
            ['Dirección:', item.direccion],
            ['Número de Puerta:', item.numero_puerta],
            ['Email:', item.email],
            ['Fecha de Ingreso:', item.fecha_ingreso],
            ['Estado:', state]
        ]))

        const permissions = { low: 'Pasante', mid: 'Principiante', high: 'Titular' }
        const roles = [
            {
                title: 'Cargo Administrativo',
                url: `/php/actions/employee/get_administrative.php?id=${id}`,
                fields: role => [
                    ['Código:', role.codigo],
                    ['Permisos:', permissions[String(role.permisos).toLowerCase()] ?? role.permisos]
                ]
            },
            {
                title: 'Cargo Copiloto',
                url: `/php/actions/employee/get_copilot.php?id=${id}`,
                fields: role => [
                    ['Código:', role.codigo],
                    ['Especialidad:', role.especialidad]
                ]
            },
            {
                title: 'Cargo Conductor',
                url: `/php/actions/employee/get_driver.php?id=${id}`,
                fields: role => [
                    ['Código:', role.codigo],
                    ['Categoría de Libreta:', role.categoria_carnet],
                    ['Vencimiento de Libreta:', role.vencimiento_carnet]
                ]
            }
        ]

        const results = await Promise.allSettled(roles.map(role => getEmployeeRole(role.url)))
        results.forEach((result, index) => {
            const role = roles[index]
            if (result.status === 'rejected') {
                console.error(`Error al consultar ${role.title}:`, result.reason)
                const message = document.createElement('p')
                message.classList.add('employee-role-message')
                message.textContent = `No se pudo consultar ${role.title.toLowerCase()}. Intente nuevamente.`
                container.append(message)
                return
            }
            if (!result.value) return

            container.append(createDetailSection(role.title, [
                ...role.fields(result.value),
                ['Estado del funcionario:', state]
            ]))
        })

        createViewActions(container, '/php/pages/employee/manage_employees.php',
            `/php/pages/employee/employee_form.php?id=${id}`)
    } catch (error) {
        console.error('Ha ocurrido un error:', error.message)
        const message = document.createElement('p')
        message.classList.add('message-container')
        message.textContent = 'No se pudo cargar el empleado. Intente nuevamente.'
        container.replaceChildren(message)
        const back = document.createElement('a')
        back.textContent = 'Volver al listado'
        back.href = '/php/pages/employee/manage_employees.php'
        container.append(back)
    }
}

if (!Number.isInteger(id) || id <= 0) {
    const div = document.createElement('div')
    div.classList.add('message-container')
    const message = document.createElement('p')
    message.textContent = 'Error: ID inválida.'
    const back = document.createElement('a')
    back.textContent = 'Volver al listado'
    back.href = '/php/pages/employee/manage_employees.php'
    div.append(message, back)
    view_container.append(div)
} else {
    await loadData(view_container)
}
