const view = document.querySelector('#view')
const registerBtn = document.querySelector('#register')
const searchBtn = document.querySelector('#search')
const searchInput = document.querySelector('#filter_search')

const filterAll = document.querySelector('#filter_all')
const filterActive = document.querySelector('#filter_active')
const filterInactive = document.querySelector('#filter_inactive')

function getSelectedState() {
    if (filterActive.checked) return 1
    if (filterInactive.checked) return 2
    return 0
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;')
}

async function requestRoutes(state = 0, phrase = '') {

    const data = new FormData()
    data.append('accion', 'listar')
    data.append('id_estado', String(state))
    data.append('phrase', phrase.trim())

    try {

        const response = await fetch('/php/actions/rutes/process_ruta.php', {
            method: 'POST',
            body: data
        })

        const result = await response.json()

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'No se pudieron cargar las rutas.')
        }

        renderRoutes(result.item)

    } catch (error) {

        console.error(error)

        view.innerHTML = `
            <div class="message-container">
                <p>No se pudieron cargar las rutas.</p>
            </div>
        `
    }
}

function renderRoutes(routes) {

    view.innerHTML = ''

    if (routes.length === 0) {
        view.innerHTML = `
            <div class="message-container">
                <p>No se encontraron rutas.</p>
                <a href="/php/pages/routes/route_register.php">
                    <i data-lucide="square-plus"></i>
                    Registrar Ruta
                </a>
            </div>
        `
        lucide.createIcons()
        return
    }

    const list = document.createElement('ul')
    list.className = 'view-list'
    list.style.width = '100%'
    list.style.padding = '0'
    list.style.margin = '0'
    list.style.display = 'flex'
    list.style.flexDirection = 'column'
    list.style.gap = '0.7rem'

    routes.forEach(route => {

        const routeId = Number(route.id_ruta)
        const stateId = Number(route.id_estado_ruta)
        const stateName = escapeHtml(route.estado)

        list.innerHTML += `
            <li>
                <p>${escapeHtml(route.nombre)}</p>
                <p>${escapeHtml(route.origen)} → ${escapeHtml(route.destino)}</p>

                <label>
                    Estado
                    <select class="change-state-select" data-id="${routeId}">
                        <option value="1" ${stateId === 1 ? 'selected' : ''}>Activa</option>
                        <option value="2" ${stateId === 2 ? 'selected' : ''}>Inactiva</option>
                    </select>
                </label>

                <button type="button" class="confirm-btn" data-id="${routeId}" title="Guardar estado">
                    <i data-lucide="circle-check"></i>
                </button>

                <button type="button" class="update-btn" data-name="${escapeHtml(route.nombre)}" title="Actualizar ruta">
                    <i data-lucide="refresh-cw"></i>
                </button>
            </li>
        `
    })

    view.appendChild(list)

    lucide.createIcons()

    document.querySelectorAll('.confirm-btn').forEach(button => {

        button.addEventListener('click', async () => {

            const routeId = button.dataset.id
            const select = document.querySelector(
                `.change-state-select[data-id="${routeId}"]`
            )

            if (!confirm('¿Está seguro de modificar el estado de la ruta?')) {
                return
            }

            const data = new FormData()
            data.append('accion', 'cambiar_estado')
            data.append('id_ruta', routeId)
            data.append('id_estado_ruta', select.value)

            try {

                const response = await fetch('/php/actions/rutes/process_ruta.php', {
                    method: 'POST',
                    body: data
                })

                const result = await response.json()

                if (!response.ok || !result.success) {
                    alert(result.message || 'No se pudo modificar el estado.')
                    return
                }

                alert(result.message)
                requestRoutes(getSelectedState(), searchInput.value)

            } catch (error) {

                console.error(error)
                alert('No se pudo modificar el estado de la ruta.')
            }
        })
    })

    document.querySelectorAll('.update-btn').forEach(button => {
        button.addEventListener('click', () => {
            const name = encodeURIComponent(button.dataset.name)
            window.location.href = `/php/pages/routes/route_update_drop.php?nombre=${name}`
        })
    })
}

registerBtn.addEventListener('click', () => {
    window.location.href = '/php/pages/routes/route_register.php'
})

searchBtn.addEventListener('click', () => {
    requestRoutes(getSelectedState(), searchInput.value)
})

searchInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        requestRoutes(getSelectedState(), searchInput.value)
    }
})

filterAll.addEventListener('change', () => {
    if (filterAll.checked) requestRoutes(0, searchInput.value)
})

filterActive.addEventListener('change', () => {
    if (filterActive.checked) requestRoutes(1, searchInput.value)
})

filterInactive.addEventListener('change', () => {
    if (filterInactive.checked) requestRoutes(2, searchInput.value)
})

document.addEventListener('DOMContentLoaded', () => {
    requestRoutes(0, '')
})
