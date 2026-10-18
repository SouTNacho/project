import { showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')
const filter_search = document.querySelector('#filter_search')
const search_btn = document.querySelector('#search')


async function loadData(container, copilots) {

    container.innerHTML = ''

    try {

        copilots.forEach(copilot => {
            const copilot_code = copilot.codigo
            
            container.innerHTML += 
            `
                <li>
                <p>${copilot.codigo}</p>
                <button class='view-btn' data-code='${copilot_code}'>
                    <i data-lucide="screen-share"></i>
                </button>
                <button class='update-btn' data-code='${copilot_code}'>
                    <i data-lucide="refresh-cw"></i>
                </button>
                </li>
            `
        })

        lucide.createIcons()
        const update_btn = document.querySelectorAll('.update-btn')
        const view_btn = document.querySelectorAll('.view-btn')

        update_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const copilot_code = btn.dataset.code
                location.href = `/php/pages/copilot/copilot_form.php?code=${copilot_code}`
            })
        })

        view_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const copilot_code = btn.dataset.code
                location.href = `/php/pages/copilot/screen_copilot.php?code=${copilot_code}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterCopilots(phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/copilot/get_copilots.php` :
            `/php/actions/copilot/get_copilots.php?phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de copilotos')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron copilotos',
                '/php/pages/copilot/copilot_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron copilotos',
            '/php/pages/copilot/copilot_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/copilot/copilot_form.php')

document.addEventListener('DOMContentLoaded', () =>
    searchFilterCopilots(''))

search_btn.addEventListener('click', () => {

    searchFilterCopilots(filter_search.value.trim())
})

lucide.createIcons()