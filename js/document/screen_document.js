const token = new URLSearchParams(window.location.search).get('token')
const document_container = document.querySelector('#document_view')
const view_container = document.querySelector('.view-container')

if (!token) {
    
    console.error('El token recibido es incorrecto')
    location.href = '/php/pages/document/manage_documents.php'
}

async function loadData(container, doc_container) {

    try {
    
        const response = await fetch(`/php/actions/document/get_document.php?token=${token}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            console.error('Ha ocurrido un error en la petic. del documento', result.message)

            const message_container = document.createElement('div')
            message_container.classList.add('message-container')

            const message = document.createElement('p')
            message.textContent = "Error al cargar el documento."

            const back_button = document.createElement('button')
            back_button.innerHTML = '<i data-lucide="arrow-left"></i>Volver'

            message_container.append(message, back_button)
            container.append(message_container)

            back_button.addEventListener('click', () => {
                location.href = '/php/pages/document/manage_documents.php'})

            return
        }


        const back_button = document.createElement('button')
        back_button.innerHTML = '<i data-lucide="arrow-left"></i>Volver'

        const download_button = document.createElement('button')
        download_button.innerHTML = '<i data-lucide="download"></i>Descargar'

        const item = result.item
        doc_container.src = item.archivo

        container.append(back_button, download_button)

        back_button.addEventListener('click', () => {
            location.href = '/php/pages/document/manage_documents.php'})

        download_button.addEventListener('click', () => {
            location.href = `/php/actions/document/download_document.php?token=${token}`})

    } catch (error) {

        // DESPUES SACAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

await loadData(view_container, document_container)

lucide.createIcons()