import formTools from "/js/library.js"
const { registerValidator } = formTools
import { getUbications, createCurrentMarkerNoDraggable } from '/js/functions.js'

const id = Number(new URLSearchParams(window.location.search).get('id'))
const confirm_button = document.querySelector('#confirm_address')
const form = document.querySelector("#route_form")
const is_update = Number.isInteger(id) && id > 0
const btn = document.querySelector("#route_btn")
const title = document.querySelector('h2')

const name = document.querySelector("#route_name")
const origin = document.querySelector("#route_origin")
const intermedium = document.querySelector("#route_intermedium")
const destination = document.querySelector("#route_destination")

const name_msg = document.querySelector("#route_name_msg")
const origin_msg = document.querySelector("#route_origin_msg")
const intermedium_msg = document.querySelector("#route_intermedium_msg")
const destination_msg = document.querySelector("#route_destination_msg")

const origin_lat = document.querySelector('#origin_lat')
const finish_lat = document.querySelector('#finish_lat')
const intermedium_lat = document.querySelector('#intermedium_lat')

const origin_long = document.querySelector('#origin_long')
const finish_long = document.querySelector('#finish_long')
const intermedium_long = document.querySelector('#intermedium_long')

const route_distance = document.querySelector('#route_distance')
const route_time = document.querySelector('#route_time')
const inputs = [origin, intermedium, destination]

const map = L.map('map').setView([-34.9011, -56.1645], 13)

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

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map)

let originMarker = null
let intermediumMarker = null
let finishMarker = null
let routeLayer = null
let routeConfirmed = false

let originalName = ''
let originalOrigin = ''
let originalIntermedium = ''
let originalDestination = ''

try {

    const ubications = await getUbications(
        '/php/actions/ubication/get_ubications.php?phrase=null&id_state=1')

    ubications.forEach(ubication => {

        for (const select of inputs) {
            const opt = document.createElement('option');

            opt.value = ubication.id_ubicacion;
            opt.textContent = ubication.nombre;
            opt.dataset.lat = ubication.latitud;
            opt.dataset.lng = ubication.longitud;

            select.append(opt);
        }
    })
} catch (error) {

    console.error('Ha ocurrido un error al cargar las ubicaciones')
    console.error(error.message)
}

function clearRoute() {
    routeConfirmed = false

    if (routeLayer) {
        map.removeLayer(routeLayer)
        routeLayer = null
    }

    route_distance.textContent = '—'
    route_time.textContent = '—'
}

async function loadRoute() {

    try {

        const response = await fetch(`/php/actions/route/get_route.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {
            console.error('No se pudo cargar la ruta')
            return
        }

        const item = result.item
        const ubications = await getUbications(
            '/php/actions/ubication/get_ubications.php?phrase=null&id_state=3')

        name.placeholder = item.nombre

        const origin_name = ubications.find(u =>
            Number(u.id_ubicacion) === Number(item.id_ubicacion_origen))

        const destination_name = ubications.find(u =>
            Number(u.id_ubicacion) === Number(item.id_ubicacion_destino))

        if (!origin_name || !destination_name) {
            console.error('No se encontraron las ubicaciones de la ruta')
            return
        }

        origin.value = origin_name.id_ubicacion
        destination.value = destination_name.id_ubicacion

        if (item.id_ubicacion_intermedia !== null && item.id_ubicacion_intermedia !== '') {

            const intermedium_name = ubications.find(u =>
                Number(u.id_ubicacion) === Number(item.id_ubicacion_intermedia))
            
            if (!intermedium_name) {
                console.error('No se encontró la ubicación intermedia de la ruta')
                return
            }

            intermedium.value = intermedium_name.id_ubicacion

            intermediumMarker = createCurrentMarkerNoDraggable(intermedium_name.latitud,
                intermedium_name.longitud, intemediumMarkerIcon, map, intermediumMarker)
        }

        originMarker = createCurrentMarkerNoDraggable(origin_name.latitud,
            origin_name.longitud, originMarkerIcon, map, originMarker)

        finishMarker = createCurrentMarkerNoDraggable(destination_name.latitud,
            destination_name.longitud, finishMarkerIcon, map, finishMarker)

        originalName = item.nombre
        originalOrigin = origin.value
        originalIntermedium = intermedium.value
        originalDestination = destination.value

    } catch (error) {

        console.error('Ha ocurrido un error al cargar la ruta', error.message)
    }
}

if (is_update) {

    form.action = `/php/actions/route/route_update.php?id=${id}`
    title.textContent = 'Actualizar Ruta'
    btn.innerHTML = '<i data-lucide="refresh-cw"></i>Actualizar'
    await loadRoute()
} else {

    form.action = '/php/actions/route/route_register.php'
    title.textContent = 'Registrar Ruta'
    btn.innerHTML = '<i data-lucide="square-plus"></i>Registrar'
}

name.addEventListener('input', () => {

    if (name.value.trim() !== '') {

        return registerValidator.largeStringsInput(name, name_msg)
    } 
    
    if(is_update) {

        return formTools.setValid(name, name_msg)
    }

    return formTools.setInvalid(name, name_msg, 'Este campo es obligatorio')
})

origin.addEventListener('change', () => {
    clearRoute()

    if (origin.value.trim() !== '') {

        return registerValidator.selectsInput(origin, origin_msg)
    } 
    
    if(is_update) {

        return formTools.setValid(origin, origin_msg)
    }

    return formTools.setInvalid(origin, origin_msg, 'Este campo es obligatorio')
})

intermedium.addEventListener('change', () => {
    clearRoute()

    if (intermedium.value.trim() !== '') {

        return registerValidator.selectsInput(intermedium, intermedium_msg)
    } 
    
    return formTools.setValid(intermedium, intermedium_msg)
})

destination.addEventListener('change', () => {
    clearRoute()

    if (destination.value.trim() !== '') {

        return registerValidator.selectsInput(destination, destination_msg)
    }

    if(is_update) {

        return formTools.setValid(destination, destination_msg)
    }

    return formTools.setInvalid(destination, destination_msg, 'Este campo es obligatorio')
})

confirm_button.addEventListener('click', async () => {
    clearRoute()

    if (origin.value.trim() === '' || destination.value.trim() === '') {
        notificacion('Debe seleccionar el origen y el destino', 'warning')
        return
    }

    const originOption = origin.selectedOptions[0]
    const destinationOption = destination.selectedOptions[0]
    const intermediumOption = intermedium.selectedOptions[0]

    if (!originOption || !destinationOption) {
        notificacion('Debe seleccionar un origen y un destino válidos', 'error')
        return
    }

    if (intermedium.value.trim() !== '' && !intermediumOption) {
        notificacion('Debe seleccionar un punto intermedio válido', 'error')
        return
    }

    const originId = Number(origin.value)
    const destinationId = Number(destination.value)
    const intermediumId = intermedium.value.trim() !== '' ? Number(intermedium.value) : null

    if (originId === destinationId &&
    (!intermediumId || intermediumId === originId)) {

        notificacion('El origen y el destino deben ser diferentes, o debe seleccionar un punto intermedio distinto', 'error')
        return
    }

    if (intermediumId !== null &&
    (intermediumId === originId || intermediumId === destinationId)) {

        notificacion('El punto intermedio debe ser diferente del origen y del destino', 'error')
        return
    }

    const originLat = Number(originOption.dataset.lat)
    const originLng = Number(originOption.dataset.lng)

    const destinationLat = Number(destinationOption.dataset.lat)
    const destinationLng = Number(destinationOption.dataset.lng)

    if (!Number.isFinite(originLat) || !Number.isFinite(originLng) ||
        !Number.isFinite(destinationLat) || !Number.isFinite(destinationLng)) {
        notificacion('Las coordenadas del origen o destino no son válidas', 'error')
        return
    }

    originMarker = createCurrentMarkerNoDraggable(
        originLat, originLng, originMarkerIcon, map, originMarker)

    finishMarker = createCurrentMarkerNoDraggable(
        destinationLat, destinationLng, finishMarkerIcon, map, finishMarker)
    
    const routePoints = [`${originLat},${originLng}`]

    if (intermediumId !== null) {
        const intermediumLat = Number(intermediumOption.dataset.lat)
        const intermediumLng = Number(intermediumOption.dataset.lng)

        if (!Number.isFinite(intermediumLat) || !Number.isFinite(intermediumLng)) {
            notificacion('Las coordenadas del punto intermedio no son válidas', 'error')
            return
        }

        intermediumMarker = createCurrentMarkerNoDraggable(
            intermediumLat, intermediumLng, intemediumMarkerIcon, map, intermediumMarker)

        routePoints.push(`${intermediumLat},${intermediumLng}`)
    } else if (intermediumMarker) {

        map.removeLayer(intermediumMarker)
        intermediumMarker = null
    }

    const calculatedOrigin = origin.value
    const calculatedIntermedium = intermedium.value
    const calculatedDestination = destination.value
    routePoints.push(`${destinationLat},${destinationLng}`)

    try {

        confirm_button.disabled = true

        const url = new URL('https://api.geoapify.com/v1/routing')
        url.searchParams.set('waypoints', routePoints.join('|'))
        url.searchParams.set('mode', 'drive')
        url.searchParams.set('units', 'metric')
        url.searchParams.set('apiKey', 'b36d19d4a4d14f198f0c5fe1d627b037')

        const response = await fetch(url)
        const result = await response.json()

        if (!response.ok || !result.features || result.features.length === 0) {
            notificacion('No se pudo calcular la ruta. Verifique las ubicaciones e intente nuevamente', 'error')
            console.error('No se pudo calcular la ruta')
            return
        }

        const route = result.features[0]
        const distance = route.properties.distance
        const time = route.properties.time

        if (!route.geometry || !Number.isFinite(distance) || !Number.isFinite(time)) {
            notificacion('La respuesta de la ruta no es válida. Intente nuevamente', 'error')
            console.error('La respuesta de la ruta no es válida')
            return
        }

        if (routeLayer) {
            map.removeLayer(routeLayer)
        }

        routeLayer = L.geoJSON(route, { 
            style: {
                color: '#2e53b8',
                weight: 5,
                opacity: 0.8 
            }
        }).addTo(map)

        map.fitBounds(routeLayer.getBounds(), {
            padding: [30, 30]
        })

        document.querySelector('#route_distance').textContent = `${(distance / 1000).toFixed(2)} km`
        document.querySelector('#route_time').textContent = `${Math.ceil(time / 60)} minutos`

        if (origin.value !== calculatedOrigin || intermedium.value !== calculatedIntermedium || destination.value !== calculatedDestination) {
            clearRoute()
            return
        }

        routeConfirmed = true

    } catch (error) {

        console.error('Error al calcular la ruta:', error.message)
        notificacion('No se pudo calcular la ruta. Intente nuevamente', 'error')

    } finally { confirm_button.disabled = false }
})

form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (is_update) {

        const hasChanges = origin.value !== originalOrigin || intermedium.value !== originalIntermedium ||
        destination.value !== originalDestination || name.value !== originalName

        if (!hasChanges) {
            notificacion('No se realizaron cambios', 'info')
            return
        }
    }

    if (name.value.trim() === '') {

        if (!is_update) {

            notificacion("El nombre es obligatorio", 'warning')
            return
        }
    } else {

        if (!registerValidator.largeStringsInput(name, name_msg)) {
            notificacion("El nombre no es válido", 'error')
            return
        }
    }

    if (origin.value.trim() === '') {

        if (!is_update) {

            notificacion("El origen es obligatorio", 'warning')
            return
        }
    } else {

        if (!registerValidator.selectsInput(origin, origin_msg)) {
            notificacion("El origen no es válido", 'error')
            return
        }
    }

    if (intermedium.value.trim() !== '') {
        if (!registerValidator.selectsInput(intermedium, intermedium_msg)) {
            notificacion("El punto intermedio no es válido", 'error');
            return;
        }
    }

    if (destination.value.trim() === '') {

        if (!is_update) {

            notificacion("El destino es obligatorio", 'warning')
            return
        }
    } else {

        if (!registerValidator.selectsInput(destination, destination_msg)) {

            notificacion("El destino no es válido", 'error')
            return
        }
    }

    if (!routeConfirmed) {

        notificacion('Debe calcular y confirmar la ruta antes de continuar', 'warning')
        return
    }

    const originOption = origin.selectedOptions[0]
    const destinationOption = destination.selectedOptions[0]
    const intermediumOption = intermedium.selectedOptions[0]

    if (!originOption || !destinationOption) {
        notificacion('Debe seleccionar un origen y un destino válidos', 'error')
        return
    }

    origin_lat.value = originOption.dataset.lat
    origin_long.value = originOption.dataset.lng

    finish_lat.value = destinationOption.dataset.lat
    finish_long.value = destinationOption.dataset.lng

    if (intermedium.value.trim() !== '' && intermediumOption) {
        intermedium_lat.value = intermediumOption.dataset.lat
        intermedium_long.value = intermediumOption.dataset.lng
    } else {
        intermedium_lat.value = ''
        intermedium_long.value = ''
    }

    form.submit()
})

lucide.createIcons()