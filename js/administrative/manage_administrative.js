import { showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')
const filter_search = document.querySelector('#filter_search')
const search_btn = document.querySelector('#search')


async function loadData(container, administratives) {

    container.innerHTML = ''

    try {

        administratives.forEach(administrative => {
            const administrative_code = administrative.codigo
            
            container.innerHTML += 
            `
                <li>
                <p>${administrative.codigo}</p>
                <button class='view-btn' data-code='${administrative_code}'>
                    <i data-lucide="screen-share"></i>
                </button>
                <button class='update-btn' data-code='${administrative_code}'>
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

                const administrative_code = btn.dataset.code
                location.href = `/php/pages/administrative/administrative_form.php?code=${administrative_code}`
            })
        })

        view_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const administrative_code = btn.dataset.code
                location.href = `/php/pages/administrative/screen_administrative.php?code=${administrative_code}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilteradministratives(phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/administrative/get_administratives.php` :
            `/php/actions/administrative/get_administratives.php?phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de administrativos')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron administrativos',
                '/php/pages/administrative/administrative_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron administrativos',
            '/php/pages/administrative/administrative_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/administrative/administrative_form.php')

document.addEventListener('DOMContentLoaded', () =>
    searchFilteradministratives(''))

search_btn.addEventListener('click', () => {

    searchFilteradministratives(filter_search.value.trim())
})

lucide.createIcons()