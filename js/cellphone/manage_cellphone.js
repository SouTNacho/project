import { showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')
const filter_search = document.querySelector('#filter_search')
const search_btn = document.querySelector('#search')


async function loadData(container, cellphones) {

    container.innerHTML = ''

    try {

        cellphones.forEach(cellphone => {
            const cellphone_id = cellphone.id_telefono
            
            container.innerHTML += 
            `
                <li>
                <p>${cellphone.telefono}</p>
                <button class='view-btn' data-id='${cellphone_id}'>
                    <i data-lucide="screen-share"></i>
                </button>
                <button class='update-btn' data-id='${cellphone_id}'>
                    <i data-lucide="refresh-cw"></i>
                </button>
                <button class='delete-btn' data-id='${cellphone_id}'>
                    <i data-lucide="trash"></i>
                </button>
                </li>
            `
        })

        lucide.createIcons()
        const delete_btn = document.querySelectorAll('.delete-btn')
        const update_btn = document.querySelectorAll('.update-btn')
        const view_btn = document.querySelectorAll('.view-btn')

        update_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const cellphone_id = btn.dataset.id
                location.href = `/php/pages/cellphone/cellphone_form.php?id=${cellphone_id}`
            })
        })

        view_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const cellphone_id = btn.dataset.id
                location.href = `/php/pages/cellphone/screen_cellphone.php?id=${cellphone_id}`
            })
        })

        delete_btn.forEach(btn => {
            btn.addEventListener('click', async () => {

                if (confirm('¿Está seguro que desea eliminar este teléfono?')) {

                    const cellphone_id = btn.dataset.id

                    try {
                        const response = await fetch(`/php/actions/cellphone/delete_cellphone.php?id=${cellphone_id}`)
                        const result = await response.json()

                        if (!response.ok || !result.success) {
                            console.error('Ha ocurrido un error en la petición de celulares')
                            return
                        }

                        alert(result.message)
                        searchFilterCellphones(filter_search.value.trim())
                    } catch (error) {

                        alert(`Ha ocurrido un error: ${error.message}`)
                        return
                    }
                }
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterCellphones(phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/cellphone/get_cellphones.php` :
            `/php/actions/cellphone/get_cellphones.php?phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de celulares')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron celulares',
                '/php/pages/cellphone/cellphone_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron celulares',
            '/php/pages/cellphone/cellphone_form.php')
    }
}

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/cellphone/cellphone_form.php')

document.addEventListener('DOMContentLoaded', () =>
    searchFilterCellphones(''))

search_btn.addEventListener('click', () => {

    searchFilterCellphones(filter_search.value.trim())
})

lucide.createIcons()