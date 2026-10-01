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

async function loadData(container, companions) {

    container.innerHTML = ''

    try {

        const states = await getStates('/php/actions/companion/get_companions_states.php')

        companions.forEach(companion => {

            const state_id = Number(companion.id_estado_acompaniante)
            const companion_id = Number(companion.id_acompaniante)
            
            container.innerHTML += 
            `
                <li>
                <p>${companion.cedula}</p>
                <p>Estado: ${loadStateName(state_id, states, 'id_estado_acompaniante')}</p>

                ${state_id !== 3 ? `
                    <label>Cambiar Estado
                    <select class='change-state-select' data-id='${companion_id}'>
                    ${loadStates(state_id, states, 'id_estado_acompaniante')}
                    </select>
                    </label>
                    <button class='confirm-btn' data-id='${companion_id}'>
                        <i data-lucide="circle-check"></i>
                    </button>
                    <button class='view-btn' data-id='${companion_id}'>
                        <i data-lucide="screen-share"></i>
                    </button>
                    <button class='update-btn' data-id='${companion_id}'>
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
                const companion_id = btn.dataset.id

                if (!confirm("¿Está seguro de modificar el acompañante?")) {
                    return
                }

                try {

                    const state_id = document.querySelector(`.change-state-select[data-id="${companion_id}"]`).value
                    const form = new FormData()

                    form.append("companion_id", companion_id)
                    form.append("state_id", state_id)

                    const response = await fetch("/php/actions/companion/companion_change.php", {
                        method: 'POST',
                        body: form
                    })

                    const result = await response.json()
                    if (!result.success || !response.ok) {
                        alert(result.message || "Error al modificar el acompañante, intente nuevamente.")
                        return
                    }

                    const option = getSelectedState()
                    alert(result.message || "Estado modificado exitosamente.")
                    
                    searchFilterCompanions(option, filter_search.value.trim())

                } catch (error) {

                    // DESPUES QUITAR EL MENSAJE
                    alert("Error al modificar el acompañante, intente nuevamente.")
                    console.error(error.message)
                    return
                }
            })
        })

        update_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const companion_id = btn.dataset.id
                location.href = `/php/pages/companion/companion_form.php?id=${companion_id}`
            })
        })

        view_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const companion_id = btn.dataset.id
                location.href = `/php/pages/companion/screen_companion.php?id=${companion_id}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterCompanions(state_id, phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/companion/get_companions.php?id_state=${state_id}&phrase=null` :
            `/php/actions/companion/get_companions.php?id_state=${state_id}&phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de acompañantes')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron acompañantes',
                '/php/pages/companion/companion_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron acompañantes',
            '/php/pages/companion/companion_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/companion/companion_form.php')


document.addEventListener('DOMContentLoaded', () =>
    searchFilterCompanions(0, ''))

filter_all.addEventListener('change', () => {

    if (filter_all.checked) {
        searchFilterCompanions(0, filter_search.value.trim())
    }
})

filter_active.addEventListener('change', () => {

    if (filter_active.checked) {
        searchFilterCompanions(1, filter_search.value.trim())
    }
})

filter_inactive.addEventListener('change', () => {

    if (filter_inactive.checked) {
        searchFilterCompanions(2, filter_search.value.trim())
    }
})

filter_deleted.addEventListener('change', () => {

    if (filter_deleted.checked) {
        searchFilterCompanions(3, filter_search.value.trim())
    }
})

search_btn.addEventListener('click', () => {

    const option = getSelectedState()
    searchFilterCompanions(option, filter_search.value.trim())
})

lucide.createIcons()