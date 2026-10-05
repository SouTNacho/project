import { showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')
const filter_search = document.querySelector('#filter_search')
const search_btn = document.querySelector('#search')


async function loadData(container, drivers) {

    container.innerHTML = ''

    try {

        drivers.forEach(driver => {
            const driver_code = driver.codigo
            
            container.innerHTML += 
            `
                <li>
                <p>${driver.codigo}</p>
                <button class='view-btn' data-code='${driver_code}'>
                    <i data-lucide="screen-share"></i>
                </button>
                <button class='update-btn' data-code='${driver_code}'>
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

                const driver_code = btn.dataset.code
                location.href = `/php/pages/driver/driver_form.php?code=${driver_code}`
            })
        })

        view_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const driver_code = btn.dataset.code
                location.href = `/php/pages/driver/screen_driver.php?code=${driver_code}`
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterdrivers(phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/driver/get_drivers.php` :
            `/php/actions/driver/get_drivers.php?phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de conductores')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron conductores',
                '/php/pages/driver/driver_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron conductores',
            '/php/pages/driver/driver_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/driver/driver_form.php')

document.addEventListener('DOMContentLoaded', () =>
    searchFilterdrivers(''))

search_btn.addEventListener('click', () => {

    searchFilterdrivers(filter_search.value.trim())
})

lucide.createIcons()