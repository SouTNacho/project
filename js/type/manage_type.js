import { loadStateName, loadStates, getStates, showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')
const filter_search = document.querySelector('#filter_search')
const search_btn = document.querySelector('#search')


async function loadData(container, types) {

    container.innerHTML = ''

    try {

        const states = await getStates('/php/actions/type/get_types_states.php')

        types.forEach(type => {
            
            const state_id = Number(type.id_estado_tipo_muestra)
            const type_id = Number(type.id_tipo_muestra)
            
            container.innerHTML += 
            `
                <li>
                <p>${type.nombre}</p>
                <p>Estado: ${loadStateName(state_id, states, 'id_estado_tipo_muestra')}</p>
                <label>Cambiar Estado
                <select class='change-state-select' data-id='${type_id}'>
                ${loadStates(state_id, states, 'id_estado_tipo_muestra')}
                </select>
                </label>
                <button class='confirm-btn' data-id='${type_id}'>
                    <i data-lucide="circle-check"></i>
                </button>
                <button class='update-btn' data-id='${type_id}'>
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
                const type_id = btn.dataset.id

                if (!confirm("¿Está seguro de modificar el tipo de muestra?")) {
                    return
                }

                try {

                    const state_id = document.querySelector(`.change-state-select[data-id="${type_id}"]`).value
                    const form = new FormData()

                    form.append("type_id", type_id)
                    form.append("state_id", state_id)

                    const response = await fetch("/php/actions/type/type_change.php", {
                        method: 'POST',
                        body: form
                    })

                    const result = await response.json()

                    if (!result.success || !response.ok) {
                        notificacion(result.message || "Error al modificar el tipo, intente nuevamente.", 'error')
                        return
                    }

                    notificacion(result.message || "Estado modificado exitosamente.", 'success')
                    searchFilterTypes(filter_search.value.trim())

                } catch (error) {

                    // DESPUES QUITAR EL MENSAJE
                    notificacion("Error al modificar el tipo, intente nuevamente.", 'error')
                    console.error(error.message)
                    return
                }
            })
        })

        update_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const type_id = btn.dataset.id
                location.href = `/php/pages/type/type_form.php?id=${type_id}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterTypes(phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/type/get_types.php` :
            `/php/actions/type/get_types.php?phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de tipos')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron tipos',
                '/php/pages/type/type_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron tipos',
            '/php/pages/type/type_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/type/type_form.php')

document.addEventListener('DOMContentLoaded', () =>
    searchFilterTypes(''))

search_btn.addEventListener('click', () => {

    searchFilterTypes(filter_search.value.trim())
})

lucide.createIcons()