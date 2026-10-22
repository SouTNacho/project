const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')

const filter_all = document.querySelector('#filter_all')
const filter_search = document.querySelector('#filter_search')
const filter_active = document.querySelector('#filter_active')
const search_btn = document.querySelector('#search')
const filter_inactive = document.querySelector('#filter_inactive')

function getSelectedState() {

    if (filter_all.checked) return 0
    if (filter_active.checked) return 2
    if (filter_inactive.checked) return 1

    return 0
}

async function loadData(container, messages) {

    container.innerHTML = ''

    try {

        messages.forEach(message => {

            const message_id = Number(message.id_mensaje)
            
            container.innerHTML += 
            `
                <li>
                <p>${message.nombre}</p>
                <p>${message.correo}</p>
                <textarea disbled>${message.mensaje}</textarea>

                ${!message.respondido ? 
                    `
                    <button class='response-btn' data-id='${message_id} data-email='${message.correo}'>
                        <i data-lucide="reply"></i>
                    </button>
                    ` : ''
                }
                </li>
            `
        })

        lucide.createIcons()
        const response_btn = document.querySelectorAll('.response-btn')

        response_btn.forEach(btn => {
            btn.addEventListener('click', async () => {

                const id = btn.dataset.id
                const email = btn.dataset.email
                const url = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}`

                window.open(url, '_blank')

                const change = await fetch(`/php/actions/contact/change_message.php?id=${id}`)
                location.reload()
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterMessages(state_id, phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/contact/get_messages.php?id_state=${state_id}&phrase=null` :
            `/php/actions/contact/get_messages.php?id_state=${state_id}&phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok) {
            console.error('Ha ocurrido un error en la petición de mensajes')
            return
        }

        if (!result.success) {

            view_container.innerHTML = ''
            const message_container = document.createElement('div')
            message_container.classList.add('message-container')

            const message_element = document.createElement('p')
            message_element.textContent = 'No se encontraron mensajes.'

            message_container.append(message_element)
            view_container.append(message_container)
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

document.addEventListener('DOMContentLoaded', () =>
    searchFilterMessages(0, ''))

filter_all.addEventListener('change', () => {

    if (filter_all.checked) {
        searchFilterMessages(0, filter_search.value.trim())
    }
})

filter_active.addEventListener('change', () => {

    if (filter_active.checked) {
        searchFilterMessages(2, filter_search.value.trim())
    }
})

filter_inactive.addEventListener('change', () => {

    if (filter_inactive.checked) {
        searchFilterMessages(1, filter_search.value.trim())
    }
})

search_btn.addEventListener('click', () => {

    const option = getSelectedState()
    searchFilterMessages(option, filter_search.value.trim())
})

lucide.createIcons()