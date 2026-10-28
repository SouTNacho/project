import { loadStateName, getStates, getPatient, getRoute, getAmbulance,
    createCurrentMarkerNoDraggable, getCompanion, getUbication } from '/js/functions.js'

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

const AmbulanceIcon = L.icon({ 
    iconUrl: '/src/ambulance.png',
    iconSize: [35, 40] 
})

async function loadData(container) {

    try {
    
        const response = await fetch(`/php/actions/transfer/get_transfer.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Ha ocurrido un error en la petic. del Super Usuario', result.message)
            return
        }

        if (!result.item) {

            const message_container = document.createElement('div')
            message_container.classList.add('message-container')

            const message = document.createElement('p')
            message.textContent = "Error al cargar el Super Usuario"

            message_container.append(message)
            container.append(message_container)
            return
        }

        const item = result.item
        const state_id = Number(item.id_estado_traslado)
        const states = await getStates('/php/actions/transfer/get_transfers_states.php')

        const route = await getRoute(`/php/actions/route/get_route.php?id=${item.id_ruta}`)
        const ambulance = await getAmbulance(`/php/actions/ambulance/get_ambulance.php?id=${item.id_ambulancia}`)

        let patient = null
        if (item.id_paciente) {

            patient = await getPatient(`/php/actions/patient/get_patient.php?id=${item.id_paciente}`)
        }

        let companion = null
        if (item.id_acompaniante) {

            companion = await getCompanion(`/php/actions/companion/get_companion.php?id=${item.id_acompaniante}`)
        }

        container.insertAdjacentHTML('beforeend', `
            <div>
            <h2>Visualización de Traslado</h2>
            <p><span>Inicio marcado:</span> ${item.hora_inicio_estimada}</p>
            <p><span>Duracion estimada:</span> ${item.duracion_estimada_minutos} minutos</p>
            <p><span>Inicio:</span> ${item.hora_inicio_real === null ? 'Faltante' : item.hora_inicio_real}</p>
            <p><span>Final:</span> ${item.hora_fin_real === null ? 'Faltante' : item.hora_fin_real}</p>
            <p><span>Ruta:</span> ${route.nombre}</p>
            <p><span>Ambulancia:</span> ${ambulance.matricula}</p>
            <p><span>Conductor:</span> ${item.codigo_conductor}</p>
            ${item.codigo_copiloto ? `<p><span>Copiloto:</span> ${item.codigo_copiloto}</p>` : ''}
            ${item.id_paciente ? `<p><span>Paciente:</span> ${patient.cedula}</p>` : ''}
            ${item.id_acompaniante ? `<p><span>Acompañante:</span> ${companion.cedula}</p>` : ''}
            <p><span>Estado:</span> ${loadStateName(state_id, states, 'id_estado_traslado')}</p>
            </div>
            <div>
            <p class="route-information">Distancia: <span id="route_distance">—</span></p>
            <p class="route-information">Tiempo estimado: <span id="route_time">—</span></p>
            <div id='map'></div>
            </div>
        `)

        const origin = await getUbication(route.id_ubicacion_origen)
        const finish = await getUbication(route.id_ubicacion_destino)
        let intermedium = null

        if (route.id_ubicacion_intermedia !== null) {

            intermedium = await getUbication(route.id_ubicacion_intermedia)
        }

        let intermediumMarker = null
        let currentMarker = null
        let originMarker = null
        let finishMarker = null
        let routeLayer = null

        const map = L.map('map').setView([origin.latitud, origin.longitud], 19)

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map)
        
        originMarker = createCurrentMarkerNoDraggable(origin.latitud, origin.longitud, originMarkerIcon, map, originMarker)
        finishMarker = createCurrentMarkerNoDraggable(finish.latitud, finish.longitud, finishMarkerIcon, map, finishMarker)

        if (route.id_ubicacion_intermedia !== null) {

            intermediumMarker = createCurrentMarkerNoDraggable(intermedium.latitud, intermedium.longitud, intemediumMarkerIcon, map, intermediumMarker)
        }

        const routePoints = item.id_ubicacion_intermedia  ?
        `${origin.latitud},${origin.longitud}|${intermedium.latitud},${intermedium.longitud}|${finish.latitud},${finish.longitud}` :
        `${origin.latitud},${origin.longitud}|${finish.latitud},${finish.longitud}`
        
        try {

            const url = new URL('https://api.geoapify.com/v1/routing')
            url.searchParams.set('waypoints', routePoints)
            url.searchParams.set('mode', 'drive')
            url.searchParams.set('units', 'metric')
            url.searchParams.set('apiKey', 'b36d19d4a4d14f198f0c5fe1d627b037')

            const response = await fetch(url)
            const result = await response.json()

            if (!response.ok || !result.features || result.features.length === 0) {
                alert('No se pudo calcular la ruta. Verifique las ubicaciones e intente nuevamente')
                console.error('No se pudo calcular la ruta')
                return
            }

            const route = result.features[0]
            const distance = route.properties.distance
            const time = route.properties.time

            if (!route.geometry || !Number.isFinite(distance) || !Number.isFinite(time)) {
                alert('La respuesta de la ruta no es válida. Intente nuevamente')
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
            alert('No se pudo calcular la ruta. Intente nuevamente')
        }

        currentMarker = createCurrentMarkerNoDraggable(-34.89480, -56.16500, AmbulanceIcon, map, currentMarker)
        
        const historyResponse = await fetch(`/php/actions/transfer/get_history.php?id=${id}`);
        const history = await historyResponse.json();

        let count = 1
        if (history.item && history.item.length > 0) {

            let historyHTML = `
                <div class="history-container">
                    <h2>Historial</h2>
            `;

            for (const log of history.item) {
                historyHTML += `
                    <div class="history-item">
                        <p><span>Número:</span> ${count}</p>
                        <p><span>Fecha:</span> ${log.fecha_hora}</p>
                        <p><span>Descripción:</span> ${log.descripcion}</p>
                    </div>
                `;
                count++
            }

            historyHTML += `</div>`;

            container.insertAdjacentHTML('beforeend', historyHTML);
        }

        const action_container = document.createElement('div')
        action_container.classList.add('action-container')

        const back = document.createElement('button')
        back.innerHTML = `<i data-lucide="arrow-left"></i>Volver `
        back.classList.add('back-btn')

        action_container.append(back)
        container.append(action_container)

        lucide.createIcons()

        back.addEventListener('click', () =>
            location.href = '/php/pages/transfer/manage_transfers.php')

        if (result.item.id_estado_traslado === 2 || result.item.id_estado_traslado === 3) {

            while (true) {
            
                const currentLocation = await fetch(`/php/actions/transfer/ubication_transfer.php?id=${id}`)
                const locationResult = await currentLocation.json()

                if (locationResult) {

                    currentMarker = createCurrentMarkerNoDraggable(locationResult.latitud, locationResult.longitud, AmbulanceIcon, map, currentMarker)
                }

                await new Promise(resolve => setTimeout(resolve, 5000));
            }
        }

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
        location.href = '/php/pages/transfer/manage_transfers.php')

} else {
    
    await loadData(view_container)
}