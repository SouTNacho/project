import formTools from "/js/library.js"
const { registerValidator } = formTools

import { getAmbulancesForTransfer, getPatientsForTransfer,
    getCopilotsForTransfer, getDriversForTransfer,
    getElementsForTransfer, getSamplesForTransfer,
    getCompanionsForTransfer, getActiveRoutes,
    getUbication, createCurrentMarkerNoDraggable } from "/js/functions.js"

function createOptions(options, item, id_key, name_property) {
    item.innerHTML = ''

    const default_option = document.createElement('option')
    default_option.textContent = 'Seleccione una opción'
    default_option.value = ''

    item.append(default_option)

    if (!Array.isArray(options)) {
        return
    }

    options.forEach(option => {
        const opt = document.createElement('option')

        opt.textContent = option[name_property]
        opt.value = option[id_key]
        item.append(opt)
    })
}

const originMarkerIcon = L.icon({
    iconUrl: '/src/origin.svg',
    iconSize: [35, 40]
})

const finishMarkerIcon = L.icon({
    iconUrl: '/src/finish.svg',
    iconSize: [35, 40]
})

const intemediumMarkerIcon = L.icon({
    iconUrl: '/src/intemedium.svg',
    iconSize: [35, 40]
})

const form = document.querySelector("#transfer_form")
const button = document.querySelector('#transfer_btn')

const transfer_patient = document.querySelector('#transfer_patient')
const transfer_driver = document.querySelector('#transfer_driver')
const transfer_copilot = document.querySelector('#transfer_copilot')
const transfer_companion = document.querySelector('#transfer_companion')
const transfer_ambulance = document.querySelector('#transfer_ambulance')

let patients = null
let drivers = null
let copilots = null
let companions = null
let ambulances = null
let elements = null
let samples = null
let confirmTimeRoute = false

const start_time_msg = document.querySelector('#transfer_start_time_msg')
const transfer_route_msg = document.querySelector('#transfer_route_msg')
const transfer_patient_msg = document.querySelector('#transfer_patient_msg')
const transfer_driver_msg = document.querySelector('#transfer_driver_msg')
const transfer_copilot_msg = document.querySelector('#transfer_copilot_msg')
const transfer_companion_msg = document.querySelector('#transfer_companion_msg')
const transfer_ambulance_msg = document.querySelector('#transfer_ambulance_msg')

const route_time_btn = document.querySelector('#confirm_route_datetime_btn')
const start_time = document.querySelector('#transfer_start_time')
const transfer_route = document.querySelector('#transfer_route')

try {

    elements = await getElementsForTransfer()
    samples = await getSamplesForTransfer()

    const routes = await getActiveRoutes()
    createOptions(routes, transfer_route, 'id_ruta', 'nombre')
} catch (error) {

    console.error('Ha ocurrido un error al solicitar los registros')
}

let map = null
let duration = null
let originMarker = null
let finishMarker = null
let intermediumMarker = null
let routeLayer = null

const routeDistance = document.querySelector('#transfer_distance')
const routeTime = document.querySelector('#transfer_time')

function initializeMap() {

    if (map) {
        return
    }

    map = L.map('map')

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map)

    map.setView([-34.9011, -56.1645], 12)
}

function clearRouteMap() {

    if (!map) {
        return
    }

    if (originMarker) {

        map.removeLayer(originMarker)
        originMarker = null
    }

    if (finishMarker) {

        map.removeLayer(finishMarker)
        finishMarker = null
    }

    if (intermediumMarker) {

        map.removeLayer(intermediumMarker)
        intermediumMarker = null
    }

    if (routeLayer) {

        map.removeLayer(routeLayer)
        routeLayer = null
    }
}

function resetRouteConfirmation() {
    confirmTimeRoute = false
    clearRouteMap()

    routeDistance.textContent = 'No disponible'
    routeTime.textContent = 'No disponible'

    createOptions([], transfer_driver, 'codigo', 'codigo')
    createOptions([], transfer_patient, 'id_paciente', 'cedula')
    createOptions([], transfer_copilot, 'codigo', 'codigo')
    createOptions([], transfer_ambulance, 'id_ambulancia', 'matricula')
    createOptions([], transfer_companion, 'id_acompaniante', 'cedula')
}

function validCoordinates(location) {

    if (!location) {
        return false
    }

    if (location.latitud == null || location.longitud == null ||
    String(location.latitud).trim() === '' || String(location.longitud).trim() === '') {
        return false
    }

    const lat = Number(location.latitud)
    const lng = Number(location.longitud)

    return (Number.isFinite(lat) && Number.isFinite(lng) &&
        lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180
    )
}

start_time.addEventListener('change', () => {
    resetRouteConfirmation()

    if (start_time.value.trim() !== '') {

        return registerValidator.selectsInput(start_time, start_time_msg)
    } 
    
    return formTools.setInvalid(start_time, start_time_msg, 'Este campo es obligatorio')
})

transfer_route.addEventListener('change', () => {
    resetRouteConfirmation()

    if (transfer_route.value.trim() !== '') {

        return registerValidator.selectsInput(transfer_route, transfer_route_msg)
    } 
    
    return formTools.setInvalid(transfer_route, transfer_route_msg, 'Este campo es obligatorio')
})

route_time_btn.addEventListener('click', async () => {

    const route_id = transfer_route.value.trim()
    const start_date = start_time.value.trim()

    if (route_id === '' || start_date === '') {
        alert('Debe seleccionar una ruta e ingresar la fecha y hora del traslado.')
        return
    }

    resetRouteConfirmation()
    route_time_btn.disabled = true

    try {

        const responseRoute = await fetch(`/php/actions/route/get_route.php?id=${route_id}`)
        const resultRoute = await responseRoute.json()

        if (!responseRoute.ok || !resultRoute.success || !resultRoute.item) {
            alert('No se pudo obtener la ruta seleccionada.')
            return
        }

        const route = resultRoute.item
        if (route.id_ubicacion_origen == null || route.id_ubicacion_destino == null) {
            alert('La ruta no tiene origen o destino.')
            return
        }

        const origin = await getUbication(route.id_ubicacion_origen)
        const finish = await getUbication(route.id_ubicacion_destino)

        const hasIntermedium = route.id_ubicacion_intermedia !== null &&
        route.id_ubicacion_intermedia !== undefined && String(route.id_ubicacion_intermedia).trim() !== ''

        let intermedium = null
        if (hasIntermedium) {
            intermedium = await getUbication(route.id_ubicacion_intermedia)
        }

        if (!validCoordinates(origin) || !validCoordinates(finish) ||
            (hasIntermedium && !validCoordinates(intermedium))) {

            alert('Una o más ubicaciones no existen o tienen coordenadas inválidas.')
            return
        }

        initializeMap()
        clearRouteMap()

        originMarker = createCurrentMarkerNoDraggable(
            Number(origin.latitud), Number(origin.longitud), originMarkerIcon, map, null)

        finishMarker = createCurrentMarkerNoDraggable(
            Number(finish.latitud), Number(finish.longitud), finishMarkerIcon, map, null)

        if (hasIntermedium) {
            intermediumMarker = createCurrentMarkerNoDraggable(
                Number(intermedium.latitud), Number(intermedium.longitud), intemediumMarkerIcon, map, null)
        }

        const points = [`${Number(origin.latitud)},${Number(origin.longitud)}`]

        if (hasIntermedium) {
            points.push(`${Number(intermedium.latitud)},${Number(intermedium.longitud)}`)
        }

        points.push(`${Number(finish.latitud)},${Number(finish.longitud)}`)
        const url = new URL('https://api.geoapify.com/v1/routing')

        url.searchParams.set('waypoints', points.join('|'))
        url.searchParams.set('mode', 'drive')
        url.searchParams.set('units', 'metric')
        url.searchParams.set('apiKey', 'b36d19d4a4d14f198f0c5fe1d627b037')

        const response = await fetch(url)
        const result = await response.json()

        if (!response.ok || !Array.isArray(result.features) || result.features.length === 0) {
            
            resetRouteConfirmation()
            alert('Geoapify no pudo calcular la ruta para estas ubicaciones.')
            return
        }

        const calculatedRoute = result.features[0]
        const distance = calculatedRoute.properties?.distance
        const time = calculatedRoute.properties?.time

        if (!calculatedRoute.geometry || !Number.isFinite(distance) ||
            !Number.isFinite(time) || distance < 0 || time < 0) {

            resetRouteConfirmation()
            alert('Geoapify devolvió datos de ruta inválidos.')
            return
        }

        routeLayer = L.geoJSON(calculatedRoute, {
            style: {
                color: '#2e53b8',
                weight: 5,
                opacity: 0.8
            }
        }).addTo(map)

        const bounds = routeLayer.getBounds()

        if (bounds.isValid()) {
            map.fitBounds(bounds, {
                padding: [30, 30]
            })
        }

        routeDistance.textContent = `${(distance / 1000).toFixed(2)} km`
        routeTime.textContent = `${Math.ceil(time / 60)} minutos`

        const durationMinutes = Math.ceil(time / 60)
        duration = durationMinutes;
        
        [drivers, patients, copilots, ambulances, companions] = await Promise.all([
            getDriversForTransfer(durationMinutes, start_date),
            getPatientsForTransfer(durationMinutes, start_date),
            getCopilotsForTransfer(durationMinutes, start_date),
            getAmbulancesForTransfer(durationMinutes, start_date),
            getCompanionsForTransfer(durationMinutes, start_date)])

        createOptions(drivers, transfer_driver, 'codigo', 'codigo')
        createOptions(patients, transfer_patient, 'id_paciente', 'cedula')
        createOptions(copilots, transfer_copilot, 'codigo', 'codigo')
        createOptions(ambulances, transfer_ambulance, 'id_ambulancia', 'matricula')
        createOptions(companions, transfer_companion, 'id_acompaniante', 'cedula')

        const resources = [drivers, patients, copilots, ambulances, companions]

        if (!resources.every(Array.isArray)) {
            alert('La respuesta de los recursos no tiene un formato válido')
            return
        }

        if (resources.some(resource => resource.length === 0)) {
            alert('Algún recurso no tiene registros disponibles.')
            return
        }

        confirmTimeRoute = true
    } catch (error) {

        resetRouteConfirmation()
        console.error('Error al confirmar la ruta:', error)
        alert(error.message || 'Ocurrió un error al calcular la ruta.')
    } finally {

        route_time_btn.disabled = false
    }
})

const elements_container = document.querySelector('#transfer_elements_list')
const elements_msg = document.querySelector('#transfer_elements_msg')
const add_element = document.querySelector('#add_element_btn')
let elementCount = 0

add_element.addEventListener('click', () => {

    const div = document.createElement('div')
    div.classList.add('transfer-element-item')

    const label = document.createElement('label')
    label.textContent = `Elemento ${elementCount + 1}`
    label.htmlFor = `transfer_element${elementCount}`

    const select = document.createElement('select')
    select.id = `transfer_element${elementCount}`
    select.name = 'transfer_elements[]'

    createOptions(elements, select, 'id_elemento', 'nombre')

    const button = document.createElement('button')
    button.classList.add('remove-element-btn')
    button.type = 'button'

    button.innerHTML = '<i data-lucide="trash-2"></i> Quitar'
    button.addEventListener('click', () => div.remove())

    div.append(label, select, button)
    elements_container.append(div)

    elementCount++
    lucide.createIcons()
})

const samples_container = document.querySelector('#transfer_samples_list')
const samples_msg = document.querySelector('#transfer_samples_msg')
const add_sample = document.querySelector('#add_sample_btn')
let sampleCount = 0

add_sample.addEventListener('click', () => {

    const div = document.createElement('div')
    div.classList.add('transfer-sample-item')

    const label = document.createElement('label')
    label.textContent = `Muestra ${sampleCount + 1}`
    label.htmlFor = `transfer_sample${sampleCount}`

    const select = document.createElement('select')
    select.id = `transfer_sample${sampleCount}`
    select.name = 'transfer_samples[]'

    createOptions(samples, select, 'id_muestra', 'descripcion')

    const button = document.createElement('button')
    button.classList.add('remove-sample-btn')
    button.type = 'button'

    button.innerHTML = '<i data-lucide="trash-2"></i> Quitar'
    button.addEventListener('click', () => div.remove())

    div.append(label, select, button)
    samples_container.append(div)

    sampleCount++
    lucide.createIcons();
})

transfer_patient.addEventListener('change', () => {
    
    if (transfer_patient.value.trim() !== '') {
        return registerValidator.selectsInput(transfer_patient, transfer_patient_msg)
    }

    return formTools.setValid(transfer_patient, transfer_patient_msg)
})

transfer_ambulance.addEventListener('change', () => {

    if (transfer_ambulance.value.trim() !== '') {
        return registerValidator.selectsInput(transfer_ambulance, transfer_ambulance_msg)
    }

    return formTools.setInvalid(transfer_ambulance, transfer_ambulance_msg, 'Este campo es obligatorio.')
})

transfer_companion.addEventListener('change', () => {

    if (transfer_companion.value.trim() !== '') {
        return registerValidator.selectsInput(transfer_companion, transfer_companion_msg)
    }

    return formTools.setValid(transfer_companion, transfer_companion_msg)
})

transfer_driver.addEventListener('change', () => {

    if (transfer_driver.value.trim() !== '') {
        return registerValidator.selectsInput(transfer_driver, transfer_driver_msg)
    }

    return formTools.setInvalid(transfer_driver, transfer_driver_msg, 'Este campo es obligatorio.')
})

transfer_copilot.addEventListener('change', () => {

    if (transfer_copilot.value.trim() !== '') {
        return registerValidator.selectsInput(transfer_copilot, transfer_copilot_msg)
    }

    return formTools.setValid(transfer_copilot, transfer_copilot_msg)
})

function validateDynamicSelects(container, messageElement, message) {
    const selects = container.querySelectorAll('select')

    const hasEmptySelection = Array.from(selects).some(
        select => select.value.trim() === ''
    )

    if (hasEmptySelection) {
        messageElement.textContent = message
        return false
    }

    messageElement.textContent = ''
    return true
}

form.addEventListener('submit', async (e) => {
    e.preventDefault()

    button.disabled = true

    if (!confirmTimeRoute) {
        alert('Aun no ha confirmado la ruta.')
        button.disabled = false
        return
    }

    if (!registerValidator.selectsInput(transfer_ambulance, transfer_ambulance_msg)) {
        alert('La ambulancia seleccionada no es válida.')
        button.disabled = false
        return
    }
    
    if (!registerValidator.selectsInput(transfer_driver, transfer_driver_msg)) {
        alert('El conductor seleccionado no es válido.')
        button.disabled = false
        return
    }

    if (!registerValidator.selectsInput(transfer_route, transfer_route_msg)) {
        alert('La ruta seleccionada no es válida.')
        button.disabled = false
        return
    }

    if (!registerValidator.selectsInput(start_time, start_time_msg)) {
        alert('La fecha y hora de inicio seleccionada no es válida.')
        button.disabled = false
        return
    }

    if (transfer_companion.value.trim() !== '') {
        if (!registerValidator.selectsInput(transfer_companion, transfer_companion_msg)) {
            alert('El acompañante seleccionado no es válido')
            button.disabled = false
            return
        }
    }

    if (transfer_copilot.value.trim() !== '') {
        if (!registerValidator.selectsInput(transfer_copilot, transfer_copilot_msg)) {
            alert('El copiloto seleccionado no es válido')
            button.disabled = false
            return
        }
    }

    if (transfer_patient.value.trim() !== '') {
        if (!registerValidator.selectsInput(transfer_patient, transfer_patient_msg)) {
            alert('El paciente seleccionado no es válido')
            button.disabled = false
            return
        }
    }

    const elementsValid = validateDynamicSelects(
        elements_container, elements_msg, 'Seleccione un elemento en cada campo agregado.')

    const samplesValid = validateDynamicSelects(
        samples_container, samples_msg, 'Seleccione una muestra en cada campo agregado.')

    if (!elementsValid || !samplesValid) {
        alert('Todos los elementos y muestras, creadas tienen que ser válidas.')
        button.disabled = false
        return
    }

    if (transfer_companion.value.trim() !== '' && transfer_patient.value.trim() === '') {
        alert('Para seleccionar un acompañante debe seleccionar un paciente.')
        button.disabled = false
        return
    }

    if (transfer_patient.value.trim() === '' && !samplesValid) {
        alert('Debe seleccionar un paciente o una muestra para trasladar.')
        button.disabled = false
        return
    }

    if (transfer_patient.value.trim() === '' && !samplesValid && !elementsValid) {
        alert('Debe seleccionar un pacientem, muestra o elemento para trasladar.')
        button.disabled = false
        return
    }

    const formTransfer = new FormData(form)
    formTransfer.append('transfer_duration', duration)

    const transfer = await fetch('/php/actions/transfer/create_transfer.php', {
        method: 'POST',
        body: formTransfer
    })

    const resultTransfer = await transfer.json()

    if (!transfer.ok || !resultTransfer.success) {
        alert('Ha ocurrido un error al crear el traslado.')
        button.disabled = false
        return
    }

    alert(resultTransfer.message || 'La creacion del traslado ha sido exitosa.')

    const formTransferPending = new FormData(form)
    formTransferPending.append('transfer_id', resultTransfer.item)
    formTransferPending.append('transfer_duration', duration)

    const transferPending = await fetch('/php/actions/transfer/save_pendings.php', {
        method: 'POST',
        body: formTransferPending
    })

    const resultTransferPending = await transferPending.json()

    if (!transferPending.ok || !resultTransferPending.success) {
        alert('Ha ocurrido un error al crear el traslado.')
        button.disabled = false
        return
    }

    alert(resultTransferPending.message || 'La creacion de la reserva ha sido exitosa.')

    if (transfer_patient !== '' && transfer_companion !== '') {

        const formPatientCompanion = new FormData()
        formPatientCompanion.append('transfer_patient', transfer_patient.value.trim())
        formPatientCompanion.append('transfer_companion', transfer_companion.value.trim())

        const transferPatientCompanion = await fetch('/php/actions/transfer/save_patient_companion.php', {
            method: 'POST',
            body: formPatientCompanion
        })

        const resultPatientCompanion = await transferPatientCompanion.json()

        if (!transferPatientCompanion.ok || !resultPatientCompanion.success) {
            alert('Ha ocurrido un error al registrar el acompañante.')
            button.disabled = false
            return
        }

        alert(resultPatientCompanion.message || 'Los acomp y paci se registraton.')
    }

    if (elementsValid) {

        const formElements = new FormData(form)
        formElements.append('transfer_id', resultTransfer.item)

        const transferElements = await fetch('/php/actions/transfer/save_element.php', {
            method: 'POST',
            body: formElements
        })

        const resultElements = await transferElements.json()

        if (!transferElements.ok || !resultElements.success) {
            alert('Ha ocurrido un error al registrar los elementos.')
            button.disabled = false
            return
        }

        alert(resultElements.message || 'Los elementos se registraron.')
    }

    if (samplesValid) {

        const formSamples = new FormData(form)
        formSamples.append('transfer_id', resultTransfer.item)

        const transferSamples = await fetch('/php/actions/transfer/save_sample.php', {
            method: 'POST',
            body: formSamples
        })

        const resultSamples = await transferSamples.json()

        if (!transferSamples.ok || !resultSamples.success) {
            alert('Ha ocurrido un error al registrar las muestras.')
            button.disabled = false
            return
        }

        alert(resultSamples.message || 'Las muestras se registraron.')
    }

    const formTransferHistorial = new FormData()
    formTransferHistorial.append('transfer_id', resultTransfer.item)
    formTransferHistorial.append('transfer_description', 'El traslado está pendiente')

    const transferHistorial = await fetch('/php/actions/transfer/save_historial.php', {
        method: 'POST',
        body: formTransferHistorial
    })

    const resultHistorial = await transferHistorial.json()

    if (!transferHistorial.ok || !resultHistorial.success) {
        alert('Ha ocurrido un error al registrar la historia del traslado.')
        button.disabled = false
        return
    }

    alert(resultHistorial.message || 'El historial comenzo.')

    button.disabled = false
})

lucide.createIcons()