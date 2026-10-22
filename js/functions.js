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

export async function getAdministrative(url) {

    const response = await fetch(url)
    const result = await response.json()

    if (!response.ok || !result.success) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error en la petic. de estados')
        return []
    }

    const administrative = result.item

    if (administrative === '') {

        return false
    }

    return administrative
}

export async function getCopilot(url) {

    const response = await fetch(url)
    const result = await response.json()

    if (!response.ok || !result.success) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error en la petic. de estados')
        return []
    }

    const copilot = result.item

    if (copilot === '') {

        return false
    }

    return copilot
}

export async function getDriver(url) {

    const response = await fetch(url)
    const result = await response.json()

    if (!response.ok || !result.success) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error en la petic. de estados')
        return []
    }

    const driver = result.item

    if (driver === '') {

        return false
    }

    return driver
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

export function createCurrentMarker(lat, lon, iconChoice, map, marker) {

    if (lat == null || lon == null || !iconChoice) {
        console.error('Los datos recibidos no son válidos')
        return
    }

    if (marker) {
        map.removeLayer(marker)
    }

    marker = L.marker([lat, lon],
        {
        draggable: true,
        icon: iconChoice
        }).addTo(map)

    map.setView([lat, lon], 15)
    return marker
}

export function createCurrentMarkerNoDraggable(lat, lon, iconChoice, map, marker) {

    if (lat == null || lon == null || !iconChoice) {
        console.error('Los datos recibidos no son válidos')
        return
    }

    if (marker) {
        map.removeLayer(marker)
    }

    marker = L.marker([lat, lon],
        { icon: iconChoice }).addTo(map)

    map.setView([lat, lon], 15)
    return marker
}

export async function getUbications(url) {

    const response = await fetch(url)
    const result = await response.json()

    if (!response.ok || !result.success) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error en la petic. de estados')
        return []
    }

    return result.item
}

export async function getAmbulancesForTraslete(durationMinutes, start_date) {
    
    try {

        const response = await fetch(`/php/actions/traslete/get_ambulances.php?
            time_start=${encodeURIComponent(start_date)}&duration=${durationMinutes}`)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {
        
        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}

export async function getPatientsForTraslete(durationMinutes, start_date) {

    try {

        const response = await fetch(`/php/actions/traslete/get_patients.php?
            time_start=${start_date}&duration=${durationMinutes}`)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {

        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}

export async function getCopilotsForTraslete(durationMinutes, start_date) {
    try {
        const response = await fetch(`/php/actions/traslete/get_copilots.php?
            time_start=${start_date}&duration=${durationMinutes}`)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {

        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}

export async function getDriversForTraslete(durationMinutes, start_date) {

    try {

        const response = await fetch(`/php/actions/traslete/get_drivers.php?
            time_start=${start_date}&duration=${durationMinutes}`)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {

        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}

export async function getElementsForTraslete() {
    try {
        const response = await fetch('/php/actions/traslete/get_elements.php')
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {
        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}

export async function getSamplesForTraslete() {

    try {

        const response = await fetch('/php/actions/traslete/get_samples.php')
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {

        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}

export async function getCompanionsForTraslete(durationMinutes, start_date) {

    try {

        const response = await fetch(`/php/actions/traslete/get_companions.php?
            time_start=${start_date} &duration=${durationMinutes}`)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {

        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}

export async function getActiveRoutes() {
    
    try {

        const response = await fetch('/php/actions/route/get_routes.php?phrase=null&id_state=0')
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {

        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}

export async function getUbication(id_ubication) {

    try {

        const response = await fetch(`/php/actions/ubication/get_ubication.php?id=${id_ubication}`)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error en la solicitud')
            return null
        }

        return result.item
    } catch (error) {
        
        // DESPUÉS QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return null
    }
}