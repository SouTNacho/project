import { loadStateName, loadStates, getStates, showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')
const filter_search = document.querySelector('#filter_search')
const search_btn = document.querySelector('#search')


async function loadData(container, categories) {

    container.innerHTML = ''

    try {

        const states = await getStates('/php/actions/category/get_categories_states.php')

        categories.forEach(category => {
            
            const state_id = Number(category.id_estado_categoria)
            const category_id = Number(category.id_categoria)
            
            container.innerHTML += 
            `
                <li>
                <p>${category.nombre}</p>
                <p>Estado: ${loadStateName(state_id, states, 'id_estado_categoria')}</p>
                <label>Cambiar Estado
                <select class='change-state-select' data-id='${category_id}'>
                ${loadStates(state_id, states, 'id_estado_categoria')}
                </select>
                </label>
                <button class='confirm-btn' data-id='${category_id}'>
                    <i data-lucide="circle-check"></i>
                </button>
                <button class='update-btn' data-id='${category_id}'>
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
                const category_id = btn.dataset.id

                if (!confirm("¿Está seguro de modificar la categoría?")) {
                    return
                }

                try {

                    const state_id = document.querySelector(`.change-state-select[data-id="${category_id}"]`).value
                    const form = new FormData()

                    form.append("category_id", category_id)
                    form.append("state_id", state_id)

                    const response = await fetch("/php/actions/category/category_change.php", {
                        method: 'POST',
                        body: form
                    })

                    const result = await response.json()

                    if (!result.success || !response.ok) {
                        alert(result.message || "Error al modificar la categoría, intente nuevamente.")
                        return
                    }

                    alert(result.message || "Estado modificado exitosamente.")
                    searchFilterCategories(filter_search.value.trim())

                } catch (error) {

                    // DESPUES QUITAR EL MENSAJE
                    alert("Error al modificar la categoría, intente nuevamente.")
                    console.error(error.message)
                    return
                }
            })
        })

        update_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const category_id = btn.dataset.id
                location.href = `/php/pages/category/category_form.php?id=${category_id}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterCategories(phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/category/get_categories.php` :
            `/php/actions/category/get_categories.php?phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de categorías')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron categorías',
                '/php/pages/category/category_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron categorías',
            '/php/pages/category/category_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/category/category_form.php')

document.addEventListener('DOMContentLoaded', () => searchFilterCategories(''))
search_btn.addEventListener('click', () => searchFilterCategories(filter_search.value.trim()))
lucide.createIcons()