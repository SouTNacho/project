import formTools from "/js/library.js"
const { registerValidator } = formTools

import { getAmbulancesForTraslete, getPatientsForTraslete,
    getCopilotsForTraslete, getDriversForTraslete,
    getElementsForTraslete, getSamplesForTraslete,
    getCompanionsForTraslete, getActiveRoutes,
    getUbication, createCurrentMarkerNoDraggable } from "/js/functions.js"

function createOptions(options, item, id_key) {
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

        opt.textContent = option.nombre
        opt.value = option[id_key]

        item.append(opt);
    });
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

const form = document.querySelector("#traslate_form")

const traslate_patient = document.querySelector('#traslate_patient')
const traslate_driver = document.querySelector('#traslate_driver')
const traslate_copilot = document.querySelector('#traslate_copilot')
const traslate_companion = document.querySelector('#traslate_companion')
const traslate_ambulance = document.querySelector('#traslate_ambulance')

let patients = null
let drivers = null
let copilots = null
let companions = null
let ambulances = null
let elements = null
let samples = null
let confirmTimeRoute = false

const start_time_msg = document.querySelector('#traslate_start_time_msg')
const traslate_route_msg = document.querySelector('#traslate_route_msg')
const traslate_patient_msg = document.querySelector('#traslate_patient_msg')
const traslate_driver_msg = document.querySelector('#traslate_driver_msg')
const traslate_copilot_msg = document.querySelector('#traslate_copilot_msg')
const traslate_companion_msg = document.querySelector('#traslate_companion_msg')
const traslate_ambulance_msg = document.querySelector('#traslate_ambulance_msg')

const route_time_btn = document.querySelector('#confirm_route_datetime_btn')
const start_time = document.querySelector('#traslate_start_time')
const traslate_route = document.querySelector('#traslate_route')

try {

    elements = await getElementsForTraslete()
    samples = await getSamplesForTraslete()

    const routes = await getActiveRoutes()
    createOptions(routes, traslate_route, 'id_ruta')
} catch (error) {

    console.error('Ha ocurrido un error al solicitar los registros')
}

let map = null
let originMarker = null
let finishMarker = null
let intermediumMarker = null
let routeLayer = null

const routeDistance = document.querySelector('#traslete_distance')
const routeTime = document.querySelector('#traslete_time')

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

    //routeDistance.textContent = 'No disponible'
    //routeTime.textContent = 'No disponible'

    createOptions([], traslate_driver, 'id_conductor')
    createOptions([], traslate_patient, 'id_paciente')
    createOptions([], traslate_copilot, 'id_copiloto')
    createOptions([], traslate_ambulance, 'id_ambulancia')
    createOptions([], traslate_companion, 'id_acompaniante')
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

traslate_route.addEventListener('change', () => {
    resetRouteConfirmation()

    if (traslate_route.value.trim() !== '') {

        return registerValidator.selectsInput(traslate_route, traslate_route_msg)
    } 
    
    return formTools.setInvalid(traslate_route, traslate_route_msg, 'Este campo es obligatorio')
})

route_time_btn.addEventListener('click', async () => {

    const route_id = traslate_route.value.trim()
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
        console.log(hasIntermedium)
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

        //routeDistance.textContent = `${(distance / 1000).toFixed(2)} km`
        //routeTime.textContent = `${Math.ceil(time / 60)} minutos`

        // const durationMinutes = Math.ceil(time / 60)
        
        // [drivers, patients, copilots, ambulances, companions] = await Promise.all([
        //     getDriversForTraslete(durationMinutes, start_date),
        //     getPatientsForTraslete(durationMinutes, start_date),
        //     getCopilotsForTraslete(durationMinutes, start_date),
        //     getAmbulancesForTraslete(durationMinutes, start_date),
        //     getCompanionsForTraslete(durationMinutes, start_date)])

        // createOptions(drivers, traslate_driver, 'id_conductor')
        // createOptions(patients, traslate_patient, 'id_paciente')
        // createOptions(copilots, traslate_copilot, 'id_copiloto')
        // createOptions(ambulances, traslate_ambulance, 'id_ambulancia')
        // createOptions(companions, traslate_companion, 'id_acompaniante')

        // const resources = [drivers, patients, copilots, ambulances, companions]

        // if (!resources.every(Array.isArray)) {
        //     alert('La respuesta de los recursos no tiene un formato válido')
        //     return
        // }

        // if (resources.some(resource => resource.length === 0)) {
        //     alert('Algún recurso no tiene registros disponibles.')
        //     return
        // }

        confirmTimeRoute = true
    } catch (error) {

        resetRouteConfirmation()
        console.error('Error al confirmar la ruta:', error)
        alert(error.message || 'Ocurrió un error al calcular la ruta.')
    } finally {

        route_time_btn.disabled = false
    }
})

const elements_container = document.querySelector('#traslate_element_list')
const elements_msg = document.querySelector('#traslate_element_msg')
const add_element = document.querySelector('#add_element_btn')
let elementCount = 0

add_element.addEventListener('click', () => {

    const div = document.createElement('div')
    div.classList.add('traslate-element-item')

    const label = document.createElement('label')
    label.textContent = `Elemento ${elementCount + 1}`
    label.htmlFor = `traslate_element${elementCount}`

    const select = document.createElement('select')
    select.id = `traslate_element${elementCount}`
    select.name = 'traslate_elements[]'

    createOptions(elements, select, 'id_elemento')

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

const samples_container = document.querySelector('#traslate_sample_list')
const samples_msg = document.querySelector('#traslate_sample_msg')
const add_sample = document.querySelector('#add_sample_btn')
let sampleCount = 0

add_sample.addEventListener('click', () => {

    const div = document.createElement('div')
    div.classList.add('traslate-sample-item')

    const label = document.createElement('label')
    label.textContent = `Muestra ${sampleCount + 1}`
    label.htmlFor = `traslate_sample${sampleCount}`

    const select = document.createElement('select')
    select.id = `traslate_sample${sampleCount}`
    select.name = 'traslate_samples[]'

    createOptions(samples, select, 'id_muestra')

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

traslate_patient.addEventListener('change', () => {
    
    if (traslate_patient.value.trim() !== '') {
        return registerValidator.selectsInput(traslate_patient, traslate_patient_msg)
    }

    return formTools.setValid(traslate_patient, traslate_patient_msg)
})

traslate_ambulance.addEventListener('change', () => {

    if (traslate_ambulance.value.trim() !== '') {
        return registerValidator.selectsInput(traslate_ambulance, traslate_ambulance_msg)
    }

    return formTools.setInvalid(traslate_ambulance, traslate_ambulance_msg, 'Este campo es obligatorio.')
})

traslate_companion.addEventListener('change', () => {

    if (traslate_companion.value.trim() !== '') {
        return registerValidator.selectsInput(traslate_companion, traslate_companion_msg)
    }

    return formTools.setValid(traslate_companion, traslate_companion_msg)
})

traslate_driver.addEventListener('change', () => {

    if (traslate_driver.value.trim() !== '') {
        return registerValidator.selectsInput(traslate_driver, traslate_driver_msg)
    }

    return formTools.setInvalid(traslate_driver, traslate_driver_msg, 'Este campo es obligatorio.')
})

traslate_copilot.addEventListener('change', () => {

    if (traslate_copilot.value.trim() !== '') {
        return registerValidator.selectsInput(traslate_copilot, traslate_copilot_msg)
    }

    return formTools.setValid(traslate_copilot, traslate_copilot_msg)
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

    if (!confirmTimeRoute) {
        alert('Aun no ha confirmado la ruta.')
        return
    }

    if (!registerValidator.selectsInput(traslate_ambulance, traslate_ambulance_msg)) {
        alert('La ambulancia seleccionada no es válida.')
        return
    }
    
    if (!registerValidator.selectsInput(traslate_driver, traslate_driver_msg)) {
        alert('El conductor seleccionado no es válido.')
        return
    }

    if (!registerValidator.selectsInput(traslate_route, traslate_route_msg)) {
        alert('La ruta seleccionada no es válida.')
        return
    }

    if (!registerValidator.selectsInput(start_time, start_time_msg)) {
        alert('La fecha y hora de inicio seleccionada no es válida.')
        return
    }

    if (traslate_companion.value.trim() !== '') {
        if (!registerValidator.selectsInput(traslate_companion, traslate_companion_msg)) {
            alert('El acompañante seleccionado no es válido')
            return
        }
    }

    if (traslate_copilot.value.trim() !== '') {
        if (!registerValidator.selectsInput(traslate_copilot, traslate_copilot_msg)) {
            alert('El copiloto seleccionado no es válido')
            return
        }
    }

    if (traslate_patient.value.trim() !== '') {
        if (!registerValidator.selectsInput(traslate_patient, traslate_patient_msg)) {
            alert('El paciente seleccionado no es válido')
            return
        }
    }

    const elementsValid = validateDynamicSelects(
        elements_container, elements_msg, 'Seleccione un elemento en cada campo agregado.')

    const samplesValid = validateDynamicSelects(
        samples_container, samples_msg, 'Seleccione una muestra en cada campo agregado.')

    if (!elementsValid || !samplesValid) {
        alert('Todos los elementos y muestras, creadas tienen que ser válidas.')
        return
    }

    if (traslate_companion.value.trim() !== '' && traslate_patient.value.trim() === '') {
        alert('Para seleccionar un acompañante debe seleccionar un paciente.')
        return
    }

    if (traslate_patient.value.trim() === '' && !samplesValid) {
        alert('Debe seleccionar un paciente o una muestra para trasladar.')
        return
    }

    if (traslate_patient.value.trim() === '' && !samplesValid && !elementsValid) {
        alert('Debe seleccionar un pacientem, muestra o elemento para trasladar.')
        return
    }

    // const formTranslateHistorial = new FormData()
    // const formElementTranslate = new FormData()
    // const formSampleTranslate = new FormData()
    // const formTranslate = new FormData(form)
    // const formPending = new FormData()

    // const translate = await fetch('/php/actions/translete/create_translete.php', {
    //     method: 'POST',
    //     body: formTranslate
    // })

    // const resultTranslate = await translate.json()

    // if (!translate.ok || !resultTranslate.success) {
    //     alert('Ha ocurrido un error al crear el traslado 1')
    //     return
    // }

})

lucide.createIcons()