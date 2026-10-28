import { loadStateName, getStates, getPatient, getRoute, getAmbulance, createCurrentMarkerNoDraggable,
    getAdministrative, getCopilot, getDriver, getCompanion, getSample, getElement, getUbication } from '/js/functions.js'

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
        const driver = await getDriver(`/php/actions/transfer/get_driver.php?code=${item.codigo_conductor}`)
        const ambulance = await getAmbulance(`/php/actions/ambulance/get_ambulance.php?id=${item.id_ambulancia}`)
        const administrative = await getAdministrative(`/php/actions/transfer/get_administrative.php?code=${item.codigo_administrativo}`)
        
        container.insertAdjacentHTML('beforeend', `
            <div>
            <h2>Historial de Traslado</h2>
            <p><span>Inicio marcado:</span> ${item.hora_inicio_estimada}</p>
            <p><span>Duracion estimada:</span> ${item.duracion_estimada_minutos} minutos</p>
            <p><span>Inicio:</span> ${item.hora_inicio_real === null ? 'Faltante' : item.hora_inicio_real}</p>
            <p><span>Final:</span> ${item.hora_fin_real === null ? 'Faltante' : item.hora_fin_real}</p>
            <p><span>Estado:</span> ${loadStateName(state_id, states, 'id_estado_traslado')}</p>
            </div>
        `)

        const ambulanceStates = await getStates('/php/actions/ambulance/get_ambulances_states.php')

        container.insertAdjacentHTML('beforeend', `
            <div>
            <h2>Ambulancia</h2>
            <p><span>Matricula:</span> ${ambulance.matricula}</p>
            <p><span>Marca: marcado:</span> ${ambulance.marca}</p>
            <p><span>Modelo:</span> ${ambulance.modelo}</p>
            <p><span>Ano:</span> ${ambulance.anio}</p>
            <p><span>Estado:</span> ${loadStateName(ambulance.id_estado_ambulancia,ambulanceStates, 'id_estado_ambulancia')}</p>
            </div>
        `)

        const routeStates = await getStates('/php/actions/route/get_routes_states.php')

        const origin = await getUbication(route.id_ubicacion_origen)
        const finish = await getUbication(route.id_ubicacion_destino)
        let intermedium = null

        if (route.id_ubicacion_intermedia !== null) {

            intermedium = await getUbication(route.id_ubicacion_intermedia)
        }

        container.insertAdjacentHTML('beforeend', `
            <div>
            <h2>Ruta</h2>
            <p><span>Nombre:</span> ${route.nombre}</p>
            <p><span>Punto de inicio:</span> ${origin.nombre}</p>
            ${route.id_ubicacion_intermedia !== null ? `<p><span>Punto de intermedio:</span> ${intermedium.nombre}</p>` : ''}
            <p><span>Punto final:</span> ${finish.nombre}</p>
            <p><span>Estado:</span> ${loadStateName(route.id_estado_ruta, routeStates, 'id_estado_ruta')}</p>
            </div>
            <div>
            <p class="route-information">Distancia: <span id="route_distance">—</span></p>
            <p class="route-information">Tiempo estimado: <span id="route_time">—</span></p>
            <div id='map'></div>
            </div>
        `)

        let intermediumMarker = null
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

        const employeeStates = await getStates(`/php/actions/employee/get_employees_states.php`)

        let permission = null
        switch(administrative.permisos) {

            case 'low':
                permission = 'Bajos'
                break
            case 'mid':
                permission = 'Medios'
                break
            case 'high':
                permission = 'Altos'
                break
        }

        container.insertAdjacentHTML('beforeend', `
            <div>
            <h2>Administrativo</h2>
            <p><span>Cedula:</span> ${administrative.cedula}</p>
            <p><span>Nombre:</span> ${administrative.nombre}</p>
            <p><span>Apellido:</span> ${administrative.apellido}</p>
            <p><span>Codigo: ${administrative.codigo}</p>
            <p><span>Permisos:</span> ${permission}</p>
            <p><span>Estado:</span> ${loadStateName(administrative.id_estado_funcionario, employeeStates, 'id_estado_funcionario')}</p>
            </div>
        `)

        container.insertAdjacentHTML('beforeend', `
            <div>
            <h2>Conductor</h2>
            <p><span>Cedula:</span> ${driver.cedula}</p>
            <p><span>Nombre:</span> ${driver.nombre}</p>
            <p><span>Apellido:</span> ${driver.apellido}</p>
            <p><span>Codigo: ${driver.codigo}</p>
            <p><span>Categoria del Carnet:</span> ${driver.categoria_carnet}</p>
            <p><span>Vencimiento del Carnet:</span> ${driver.vencimiento_carnet}</p>
            <p><span>Estado:</span> ${loadStateName(driver.id_estado_funcionario, employeeStates, 'id_estado_funcionario')}</p>
            </div>
        `)

        let copilot = null
        if (item.codigo_copiloto !== null) {

            copilot = await getCopilot(`/php/actions/transfer/get_copilot.php?code=${item.codigo_copiloto}`)

            container.insertAdjacentHTML('beforeend', `
                <div>
                <h2>Copiloto</h2>
                <p><span>Cedula:</span> ${copilot.cedula}</p>
                <p><span>Nombre:</span> ${copilot.nombre}</p>
                <p><span>Apellido:</span> ${copilot.apellido}</p>
                <p><span>Codigo: ${copilot.codigo}</p>
                <p><span>Especialidad:</span> ${copilot.especialidad}</p>
                <p><span>Estado:</span> ${loadStateName(copilot.id_estado_funcionario, employeeStates, 'id_estado_funcionario')}</p>
                </div>
            `)
        }

        let patient = null
        const patientStates = await getStates('/php/actions/patient/get_patients_states.php')

        if (item.id_paciente) {

            patient = await getPatient(`/php/actions/patient/get_patient.php?id=${item.id_paciente}`)

            container.insertAdjacentHTML('beforeend',`
                <div>
                <h2>Paciente</h2>
                <p><span>Cedula:</span> ${patient.cedula}</p>
                <p><span>Nombre:</span> ${patient.nombre}</p>
                <p><span>Apellido:</span> ${patient.apellido}</p>
                <p><span>Estado:</span> ${loadStateName(patient.id_estado_paciente, patientStates, 'id_estado_paciente')}</p>
                </div>
            `)
        }

        let companion = null
        const companionStates = await getStates('/php/actions/companion/get_companions_states.php')

        if (item.id_acompaniante) {

            companion = await getCompanion(`/php/actions/companion/get_companion.php?id=${item.id_acompaniante}`)

            container.insertAdjacentHTML('beforeend', `
                <div>
                <h2>Acompanante</h2>
                <p><span>Cedula:</span> ${companion.cedula}</p>
                <p><span>Nombre:</span> ${companion.nombre}</p>
                <p><span>Apellido:</span> ${companion.apellido}</p>
                <p><span>Estado:</span> ${loadStateName(companion.id_estado_acompaniante, companionStates, 'id_estado_acompaniante')}</p>
                </div>
            `)
        }

        const elementsResponse = await fetch(`/php/actions/transfer/get_elements.php?id=${id}`)
        const elementsResult = await elementsResponse.json()

        const samplesResponse = await fetch(`/php/actions/transfer/get_samples.php?id=${id}`)
        const samplesResult = await samplesResponse.json()

        if (elementsResult.item) {

            const elements_container = document.createElement('div')
            const title = document.createElement('h2')
            title.textContent = 'Elementos Trasladados'
            elements_container.append(title)

            for (let element of elementsResult.item) {

                const row = await getElement(`/php/actions/element/get_element.php?id=${element.id_elemento_traslado}`)
                
                const p = document.createElement('p')
                p.innerHTML = `</span> ${row.nombre}</p>`
                elements_container.append(p)
            }

            container.append(elements_container)
        }

        if (samplesResult.item) {

            const samples_container = document.createElement('div')
            const title = document.createElement('h2')
            title.textContent = 'Muestras Trasladadas'
            samples_container.append(title)

            for (let sample of samplesResult.item) {

                const row = await getSample(`/php/actions/sample/get_sample.php?id=${sample.id_muestra_traslado}`)
                
                const p = document.createElement('p')
                p.innerHTML = `</span> ${row.descripcion}</p>`
                samples_container.append(p)
            }

            container.append(samples_container)
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