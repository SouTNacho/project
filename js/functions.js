export function loadStateName(state_id, states, id_property) {

    for (const state of states) {

        if (state_id === Number(state[id_property])) {
            return state.nombre
        }
    }

    return 'Desconocido'
}

export function loadStates(state_id, states, id_property) {

    let options = ''

    for (const state of states) {

        if (state_id === Number(state[id_property])) {
            continue
        }

        options += `
            <option value="${state[id_property]}">
                ${state.nombre}
            </option>
        `
    }

    return options
}

export async function getStates(url) {

    const response = await fetch(url)
    const result = await response.json()

    if (!response.ok || !result.success) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error en la petic. de estados')
        return []
    }

    return result.item
}

export function createViewActions(container, backUrl, updateUrl) {

    const action_container = document.createElement('div')
    action_container.classList.add('action-container')

    const back = document.createElement('button')
    back.innerHTML = `<i data-lucide="arrow-left"></i>Volver `
    back.classList.add('back-btn')

    const upd = document.createElement('button')
    upd.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    upd.classList.add('upd-btn')

    action_container.append(back, upd)
    container.append(action_container)

    lucide.createIcons()

    back.addEventListener('click', () =>  location.href = backUrl)

    upd.addEventListener('click', () => location.href = updateUrl)
}

export function showAlert(message, type) {
    const container = document.querySelector('#alert_container')

    const alert = document.createElement('div')
    alert.classList.add('alert', `alert-${type}`)

    alert.textContent = message

    container.prepend(alert)
}

export function showNoResults(container, message, registerUrl) {
    container.innerHTML = ''

    const message_container = document.createElement('div')
    message_container.classList.add('message-container')

    const message_element = document.createElement('p')
    message_element.textContent = message

    const link = document.createElement('a')
    link.innerHTML = `<i data-lucide="square-plus"></i>REGISTRAR`
    link.href = registerUrl

    message_container.append(message_element)
    message_container.append(link)

    container.append(message_container)

    lucide.createIcons()
}