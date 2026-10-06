import { loadStateName, loadStates, getStates, showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')

const filter_all = document.querySelector('#filter_all')
const filter_search = document.querySelector('#filter_search')
const filter_active = document.querySelector('#filter_active')
const search_btn = document.querySelector('#search')
const filter_inactive = document.querySelector('#filter_inactive')
const filter_deleted = document.querySelector('#filter_deleted')

function getSelectedState() {

    if (filter_all.checked) return 0
    if (filter_active.checked) return 1
    if (filter_inactive.checked) return 2
    if (filter_deleted.checked) return 3

    return 0

}

async function loadData(container, ubications) {

    container.innerHTML = ''

    try {

        const states = await getStates('/php/actions/ubication/get_ubications_states.php')

        ubications.forEach(ubication => {

            const state_id = Number(ubication.id_estado_ubicacion)
            const ubication_id = Number(ubication.id_ubicacion)

            container.innerHTML += 
            `
                <li>
                <p>${ubication.nombre}</p>
                <p>Estado: ${loadStateName(state_id, states, 'id_estado_ubicacion')}</p>

                ${state_id !== 3 ? `
                    <label>Cambiar Estado
                    <select class='change-state-select' data-id='${ubication_id}'>
                    ${loadStates(state_id, states, 'id_estado_ubicacion')}
                    </select>
                    </label>
                    <button class='confirm-btn' data-id='${ubication_id}'>
                        <i data-lucide="circle-check"></i>
                    </button>
                    <button class='view-btn' data-id='${ubication_id}'>
                        <i data-lucide="screen-share"></i>
                    </button>
                    <button class='update-btn' data-id='${ubication_id}'>
                        <i data-lucide="refresh-cw"></i>
                    </button>
                    ` : ''}
                </li>
            `
        })

        lucide.createIcons()
        const change_btn = document.querySelectorAll('.confirm-btn')
        const update_btn = document.querySelectorAll('.update-btn')
        const view_btn = document.querySelectorAll('.view-btn')

        change_btn.forEach(btn => {
            btn.addEventListener('click', async () => {
                const ubication_id = btn.dataset.id

                if (!confirm("¿Está seguro de modificar la ubicación?")) {
                    return
                }

                try {

                    const state_id = document.querySelector(`.change-state-select[data-id="${ubication_id}"]`).value
                    const form = new FormData()

                    form.append("ubication_id", ubication_id)
                    form.append("state_id", state_id)

                    const response = await fetch("/php/actions/ubication/ubication_change.php", {
                        method: 'POST',
                        body: form
                    })

                    const result = await response.json()
                    if (!result.success || !response.ok) {
                        alert(result.message || "Error al modificar la ubicación, intente nuevamente.")
                        return
                    }

                    const option = getSelectedState()
                    alert(result.message || "Estado modificado exitosamente.")

                    searchFilterUbications(option, filter_search.value.trim())

                } catch (error) {

                    // DESPUES QUITAR EL MENSAJE
                    alert("Error al modificar la ubicación, intente nuevamente.")
                    console.error(error.message)
                    return
                }
            })
        })

        update_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const ubication_id = btn.dataset.id
                location.href = `/php/pages/ubication/ubication_form.php?id=${ubication_id}`
            })
        })

        view_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const ubication_id = btn.dataset.id
                location.href = `/php/pages/ubication/screen_ubication.php?id=${ubication_id}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }

}

async function searchFilterUbications(state_id, phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/ubication/get_ubications.php?id_state=${state_id}&phrase=null` :
            `/php/actions/ubication/get_ubications.php?id_state=${state_id}&phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de ubicaciones')
            return
        }

        if (result.item.length === 0) {

            showNoResults(view_container, 'No se encontraron ubicaciones',
                '/php/pages/ubication/ubication_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron ubicaciones',
            '/php/pages/ubication/ubication_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/ubication/ubication_form.php')

document.addEventListener('DOMContentLoaded', () =>
    searchFilterUbications(0, ''))

filter_all.addEventListener('change', () => {

    if (filter_all.checked) {
        searchFilterUbications(0, filter_search.value.trim())
    }

})

filter_active.addEventListener('change', () => {

    if (filter_active.checked) {
        searchFilterUbications(1, filter_search.value.trim())
    }

})

filter_inactive.addEventListener('change', () => {

    if (filter_inactive.checked) {
        searchFilterUbications(2, filter_search.value.trim())
    }

})

filter_deleted.addEventListener('change', () => {

    if (filter_deleted.checked) {
        searchFilterUbications(3, filter_search.value.trim())
    }

})

search_btn.addEventListener('click', () => {

    const option = getSelectedState()
    searchFilterUbications(option, filter_search.value.trim())
})

lucide.createIcons()