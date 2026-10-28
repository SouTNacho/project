import { loadStateName, getStates, showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')

const filter_all = document.querySelector('#filter_all')
const filter_active = document.querySelector('#filter_active')
const filter_pending = document.querySelector('#filter_pending')
const filter_stopped = document.querySelector('#filter_stopped')
const filter_finish = document.querySelector('#filter_finish')

const filter_search = document.querySelector('#filter_search')
const search_btn = document.querySelector('#search')


function getSelectedState() {

    if (filter_all.checked) return 0
    if (filter_pending.checked) return 1
    if (filter_active.checked) return 2
    if (filter_stopped.checked) return 3
    if (filter_finish.checked) return 4

    return 0
}

async function loadData(container, transfers) {

    container.innerHTML = ''

    try {

        const states = await getStates('/php/actions/transfer/get_transfers_states.php')

        transfers.forEach(transfer => {

            const state_id = Number(transfer.id_estado_traslado)
            const transfer_id = Number(transfer.id_traslado)
            
            container.innerHTML += 
            `
                <li>
                <p>Numero: ${transfer_id}</p>
                <p>Fecha: ${transfer.hora_inicio_estimada}</p>
                <p>Estado: ${loadStateName(state_id, states, 'id_estado_traslado')}</p>
                <button class='history-btn' data-id='${transfer_id}'>
                    <i data-lucide="BookOpen"></i>
                </button>

                ${state_id !== 4 ? `
                    <button class='view-btn' data-id='${transfer_id}'>
                        <i data-lucide="screen-share"></i>
                    </button>
                    ` : ''}
                </li>
            `
        })

        lucide.createIcons()
        const view_btn = document.querySelectorAll('.view-btn')
        const history_btn = document.querySelectorAll('.history-btn')

        view_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const transfer_id = btn.dataset.id
                location.href = `/php/pages/transfer/screen_transfer.php?id=${transfer_id}`
            })
        })

        history_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const transfer_id = btn.dataset.id
                location.href = `/php/pages/transfer/screen_transfer_history.php?id=${transfer_id}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterTransfers(state_id, phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/transfer/get_transfers.php?id_state=${state_id}&phrase=null` :
            `/php/actions/transfer/get_transfers.php?id_state=${state_id}&phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de tralados')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron traslados',
                '/php/pages/transfer/transfer_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron traslados',
            '/php/pages/transfer/transfer_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/transfer/transfer_form.php')


document.addEventListener('DOMContentLoaded', () =>
    searchFilterTransfers(0, ''))

filter_all.addEventListener('change', () => {

    if (filter_all.checked) {
        searchFilterTransfers(0, filter_search.value.trim())
    }
})

filter_active.addEventListener('change', () => {

    if (filter_active.checked) {
        searchFilterTransfers(2, filter_search.value.trim())
    }
})

filter_pending.addEventListener('change', () => {

    if (filter_pending.checked) {
        searchFilterTransfers(1, filter_search.value.trim())
    }
})

filter_stopped.addEventListener('change', () => {

    if (filter_stopped.checked) {
        searchFilterTransfers(3, filter_search.value.trim())
    }
})

filter_finish.addEventListener('change', () => {

    if (filter_finish.checked) {
        searchFilterTransfers(4, filter_search.value.trim())
    }
})

search_btn.addEventListener('click', () => {

    const option = getSelectedState()
    searchFilterTransfers(option, filter_search.value.trim())
})

filter_search.addEventListener('keydown', (event) => {
    
    const option = getSelectedState()
    if (event.key === 'Enter') {
        searchFilterTransfers(option, filter_search.value.trim()) 
    }
})

lucide.createIcons()