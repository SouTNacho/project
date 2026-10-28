import { getUbications, createCurrentMarkerNoDraggable, createViewActions } from '/js/functions.js'

const id = Number(new URLSearchParams(window.location.search).get('id'))
const view_container = document.querySelector('.view-container')

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

async function loadData(container) {

    try {

        const response = await fetch(`/php/actions/route/get_route.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Ha ocurrido un error en la petic. de la ruta', result.message)
            return
        }

        if (!result.item) {

            const message_container = document.createElement('div')
            message_container.classList.add('message-container')

            const message = document.createElement('p')
            message.textContent = "Error al cargar la ruta."

            message_container.append(message)
            container.append(message_container)
            return
        }

        const item = result.item

        container.innerHTML =
        `
            <div>
            <h2>Visualizar Ruta</h2>
            <p><span>Nombre:</span> ${item.nombre}</p>
            <p class="route-information">Distancia: <span id="route_distance">—</span> / Tiempo estimado: <span id="route_time">—</span></p>
            <div id='map'></div>
            </div>
        `

        createViewActions(container, '/php/pages/route/manage_routes.php',
            `/php/pages/route/route_form.php?id=${id}`)

        let intermediumMarker = null
        let originMarker = null
        let finishMarker = null
        let routeLayer = null
        let intermedium = null

        const origin = await getUbications(`/php/actions/ubication/get_ubication.php?id=${item.id_ubicacion_origen}`)
        const destination = await getUbications(`/php/actions/ubication/get_ubication.php?id=${item.id_ubicacion_destino}`)

        const map = L.map('map').setView([origin.latitud, origin.longitud], 19)
        const defaultMarkerIcon = L.icon({ iconUrl: '/src/defualtMarker.svg', iconSize: [35, 40] })

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map)
        
        originMarker = createCurrentMarkerNoDraggable(origin.latitud, origin.longitud, originMarkerIcon, map, originMarker)
        finishMarker = createCurrentMarkerNoDraggable(destination.latitud, destination.longitud, finishMarkerIcon, map, finishMarker)

        if (item.id_ubicacion_intermedia) {

            intermedium = await getUbications(`/php/actions/ubication/get_ubication.php?id=${item.id_ubicacion_intermedia}`)
            intermediumMarker = createCurrentMarkerNoDraggable(intermedium.latitud, intermedium.longitud, intemediumMarkerIcon, map, intermediumMarker)
        }

        const routePoints = item.id_ubicacion_intermedia ?
        `${origin.latitud},${origin.longitud}|${intermedium.latitud},${intermedium.longitud}|${destination.latitud},${destination.longitud}` :
        `${origin.latitud},${origin.longitud}|${destination.latitud},${destination.longitud}`
        
        try {

            const url = new URL('https://api.geoapify.com/v1/routing')
            url.searchParams.set('waypoints', routePoints)
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

        } catch (error) {

            console.error('Error al calcular la ruta:', error.message)
            notificacion('No se pudo calcular la ruta. Intente nuevamente', 'error')
        }

    } catch (error) {

        // DESPUES SACAR EL MENSAJE
        notificacion('Ha ocurrido un error al cargar la ruta.', 'error')
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

if (!Number.isInteger(id) || id <= 0) {

    const div = document.createElement('div')

    div.classList.add('message-container')
    div.innerHTML = '<p>Error: ID inválida.</p>'

    const back = document.createElement('button')

    back.innerHTML = `<i data-lucide="arrow-left"></i>Volver`
    back.classList.add('back-btn')
    div.append(back)

    view_container.append(div)
    lucide.createIcons()

    back.addEventListener('click', () =>
        location.href = '/php/pages/route/manage_routes.php')

} else {

    await loadData(view_container)
}