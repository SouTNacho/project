
import formTools from "/js/library.js"
const { registerValidator } = formTools
import { createCurrentMarker, createCurrentMarkerNoDraggable } from '/js/functions.js'

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#ubication_form")
const is_update = Number.isInteger(id) && id > 0
const title = document.querySelector('h2')

const name = document.querySelector("#ubication_name")
const address = document.querySelector("#ubication_address")
const department = document.querySelector("#ubication_department")
const btn = document.querySelector("#ubication_btn")

const name_msg = document.querySelector("#ubication_name_msg")
const address_msg = document.querySelector("#ubication_address_msg")
const department_msg = document.querySelector("#ubication_department_msg")

const inputs = [name]
let originalDepartment = ''
let originalAddress = ''

const ubication_lat = document.querySelector('#ubication_lat')
const ubication_long = document.querySelector('#ubication_long')

let currentMarker = null
let addressConfirmed = false

const confirm_button = document.querySelector('#confirm_address')
const map = L.map('map').setView([-34.9011, -56.1645], 13)

const AmbulanceIcon = L.icon({
    iconUrl: '/src/ambulance.png',
    iconSize: [35, 40]
})

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map)

async function loadUbication() {

    try {

        const response = await fetch(`/php/actions/ubication/get_ubication.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            console.error('Error al cargar la ubicación')
            return
        }

        const item = result.item

        name.placeholder = item.nombre
        address.placeholder = item.direccion
        department.value = item.departamento

        ubication_lat.value = item.latitud
        ubication_long.value = item.longitud

        originalDepartment = item.departamento
        originalAddress = item.direccion

        currentMarker = createCurrentMarkerNoDraggable(
            item.latitud, item.longitud, AmbulanceIcon, map, currentMarker)

    } catch (error) {

        console.error('Ha ocurrido un error:', error.message)
    }
}

if (is_update) {

    form.action = `/php/actions/ubication/ubication_update.php?id=${id}`
    title.textContent = 'Actualizar Ubicación'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadUbication()

} else {

    form.action = '/php/actions/ubication/ubication_register.php'
    title.textContent = 'Registrar Ubicación'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}

confirm_button.addEventListener('click', async () => {

    const departmentValue = department.value.trim()
    if (departmentValue === '') {

        notificacion('El departamento seleccionado no es válido', 'error')
        return
    }

    const addressValue = address.value.trim()
    if (address.value.trim() === '') {

        notificacion('La dirección ingresada no es válida', 'error')
        return
    }

    const text = `${addressValue}, ${departmentValue}, Uruguay`
    const url = 'https://api.geoapify.com/v1/geocode/search?' + `text=${encodeURIComponent(text)}` +
        '&filter=countrycode:uy' + '&lang=es' + '&limit=1' + '&format=json' + '&apiKey=b36d19d4a4d14f198f0c5fe1d627b037'

    try {

        const response = await fetch(url)
        const data = await response.json()

        if (!response.ok || !data.results || data.results.length === 0) {

            addressConfirmed = false
            console.error('No se encontró la dirección')
            return
        }

        const result = data.results[0]
        const lat = result.lat
        const lon = result.lon

        currentMarker = createCurrentMarker( lat, lon, AmbulanceIcon, map, currentMarker)
        addressConfirmed = true

    } catch (error) {

        addressConfirmed = false
        console.error('Ha ocurrido un error al crear el marcador', error.message)
    }
})

name.addEventListener('input', () => {

    if (name.value.trim() !== '') {

        return registerValidator.stringsInput(name, name_msg)
    }

    if (is_update) {

        return formTools.setValid(name, name_msg)
    }

    return formTools.setInvalid(name, name_msg, 'Este campo es obligatorio')
})

department.addEventListener('input', () => {

    addressConfirmed = false

    if (department.value.trim() === '') {
        if (address.value.trim() !== '') {

            return formTools.setInvalid(address, address_msg,
                'Para ingresar una dirección debe seleccionar un departamento válido')
        }
    }

    if (department.value.trim() !== '') {

        return registerValidator.selectsInput( department, department_msg)
    }

    if (is_update) {

        return formTools.setValid(department, department_msg)
    }

    return formTools.setInvalid( department, department_msg, 'Este campo es obligatorio')
})

address.addEventListener('input', () => {

    addressConfirmed = false

    if (address.value.trim() !== '') {

        return registerValidator.stringsInput(address, address_msg)
    }

    if (is_update) {

        return formTools.setValid(address, address_msg)
    }

    return formTools.setInvalid( address, address_msg, 'Este campo es obligatorio')
})

form.addEventListener('submit', (e) => {

    e.preventDefault()
    let locationChanged = false

    if (is_update) {

        let hasChanges = false
        for (const input of inputs) {

            if (input.value.trim() !== '') {
                hasChanges = true
                break
            }
        }

        const departmentChanged = department.value !== originalDepartment
        const addressChanged = address.value.trim() !== ''
        locationChanged = departmentChanged || addressChanged

        if (!hasChanges && !locationChanged) {
            notificacion('Debe completar al menos un campo', 'warning')
            return
        }
    }

    if (name.value.trim() === '') {

        if (!is_update) {

            notificacion('El nombre es obligatorio', 'warning')
            return
        }

    } else {

        if (!registerValidator.stringsInput(name, name_msg)) {

            notificacion('El nombre no es válido', 'error')
            return
        }
    }

    if (!is_update || locationChanged) {

        if (department.value.trim() === '') {

            notificacion('El departamento es obligatorio', 'warning')
            return
        }

        if (address.value.trim() === '') {

            notificacion('La dirección es obligatoria', 'warning')
            return
        }

        if (!registerValidator.selectsInput(department, department_msg)) {

            notificacion('El departamento no es válido', 'error')
            return
        }

        if (!registerValidator.stringsInput(address, address_msg)) {

            notificacion('La dirección no es válida', 'error')
            return
        }

        if (!currentMarker || !addressConfirmed) {

            notificacion('Debe confirmar la dirección en el mapa', 'warning')
            return
        }

        const position = currentMarker.getLatLng()

        ubication_lat.value = position.lat
        ubication_long.value = position.lng
    }

    form.submit()
})

lucide.createIcons()