const container = document.querySelector('.index-intro-content')

document.addEventListener('DOMContentLoaded', async () => {

    if (!container) return

    try {

        const response = await fetch('/php/actions/contact/get_messages.php?phrase=null&id_state=3')
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Error al solicitar los mensajes de los usuarios.')
            return
        }

        const notification = document.createElement('div')
        notification.classList.add('pending-messages-notice')

        const message = document.createElement('p')
        message.textContent = 'Tenés mensajes pendientes de respuesta. Accedé a la bandeja para revisarlos y responderlos.'

        const button = document.createElement('button')
        button.type = 'button'
        button.classList.add('view-messages-btn')
        button.textContent = 'Ver bandeja de mensajes'

        button.addEventListener('click', () => {
            location.href = '/php/pages/contact/screen_messages.php'
        })

        notification.append(message, button)
        container.appendChild(notification)

    } catch (error) {
        console.error('Error al solicitar los mensajes de los usuarios.', error)
    }
})

lucide.createIcons()