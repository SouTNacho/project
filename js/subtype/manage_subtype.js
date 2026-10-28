import { loadStateName, loadStates, getStates, showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')
const filter_search = document.querySelector('#filter_search')
const search_btn = document.querySelector('#search')


async function loadData(container, subtypes) {

    container.innerHTML = ''

    try {

        const states = await getStates('/php/actions/subtype/get_subtypes_states.php')

        subtypes.forEach(subtype => {
            
            const state_id = Number(subtype.id_estado_subtipo_elemento)
            const subtype_id = Number(subtype.id_subtipo_elemento)
            
            container.innerHTML += 
            `
                <li>
                <p>${subtype.nombre}</p>
                <p>Estado: ${loadStateName(state_id, states, 'id_estado_subtipo_elemento')}</p>
                <label>Cambiar Estado
                <select class='change-state-select' data-id='${subtype_id}'>
                ${loadStates(state_id, states, 'id_estado_subtipo_elemento')}
                </select>
                </label>
                <button class='confirm-btn' data-id='${subtype_id}'>
                    <i data-lucide="circle-check"></i>
                </button>
                <button class='update-btn' data-id='${subtype_id}'>
                    <i data-lucide="refresh-cw"></i>
                </button>
                </li>
            `
        })

        lucide.createIcons()
        const update_btn = document.querySelectorAll('.update-btn')
        const change_btn = document.querySelectorAll('.confirm-btn')

        change_btn.forEach(btn => {
            btn.addEventListener('click', async () => {
                const subtype_id = btn.dataset.id

                if (!confirm("¿Está seguro de modificar el subtipo de elemento?")) {
                    return
                }

                try {

                    const state_id = document.querySelector(`.change-state-select[data-id="${subtype_id}"]`).value
                    const form = new FormData()

                    form.append("subtype_id", subtype_id)
                    form.append("state_id", state_id)

                    const response = await fetch("/php/actions/subtype/subtype_change.php", {
                        method: 'POST',
                        body: form
                    })

                    const result = await response.json()

                    if (!result.success || !response.ok) {
                        notificacion(result.message || "Error al modificar el subtipo, intente nuevamente.", 'error')
                        return
                    }

                    notificacion(result.message || "Estado modificado exitosamente.", 'success')
                    searchFilterSubtypes(filter_search.value.trim())

                } catch (error) {

                    // DESPUES QUITAR EL MENSAJE
                    notificacion("Error al modificar el subtipo, intente nuevamente.", 'error')
                    console.error(error.message)
                    return
                }
            })
        })

        update_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const subtype_id = btn.dataset.id
                location.href = `/php/pages/subtype/subtype_form.php?id=${subtype_id}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterSubtypes(phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/subtype/get_subtypes.php` :
            `/php/actions/subtype/get_subtypes.php?phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de subtipos')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron subtipos',
                '/php/pages/subtype/subtype_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron subtipos',
            '/php/pages/subtype/subtype_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/subtype/subtype_form.php')

document.addEventListener('DOMContentLoaded', () =>
    searchFilterSubtypes(''))

search_btn.addEventListener('click', () => {

    searchFilterSubtypes(filter_search.value.trim())
})

lucide.createIcons()