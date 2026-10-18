const search_btn = document.querySelector('#search')
const filter_search = document.querySelector('#filter_search')
const view_container = document.querySelector('#public_documents_container')

async function loadData(container, documents) {

    container.innerHTML = ''

    try {

        documents.forEach(document => {

            const document_token = encodeURIComponent(document.token)
            const document_name = document.nombre

            container.innerHTML += 
            `
                <article class="public-document-card">
                <i data-lucide="file-text"></i>
                <h2>${document_name}</h2>
                <p>Documento disponible para consulta pública.</p>
                <div class="public-document-actions">
                <a class="view-document" href="/php/pages/other_document/screen_public_document.php?token=${document_token}">
                <i data-lucide="eye"></i>Ver
                </a>
                <a class="download-document" href="/php/actions/document/download_document.php?token=${document_token}">
                <i data-lucide="download"></i>Descargar
                </a>
                </div>
                </article>
            `
        })

        lucide.createIcons()

    } catch (error) {

        console.error('Ha ocurrido un error:', error.message)
    }
}

function showNoPublicDocuments() {

    view_container.innerHTML = `
        <div class="public-document-card">
            <i data-lucide="file-text"></i>
            <h2>No hay documentos disponibles</h2>
            <p>Actualmente no hay documentos públicos activos para consultar.</p>
        </div>
    `

    lucide.createIcons()
}

async function searchFilterDocuments(phrase = '') {

    try {

        const petition =
            `/php/actions/document/get_public_documents.php?phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de documentos')
            showNoPublicDocuments()
            return
        }

        if (result.item.length === 0) {
            showNoPublicDocuments()
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        console.error('Ha ocurrido un error:', error.message)
        showNoPublicDocuments()
    }
}

document.addEventListener('DOMContentLoaded', () => searchFilterDocuments())
search_btn.addEventListener('click', () => searchFilterDocuments(filter_search.value.trim()))
lucide.createIcons()