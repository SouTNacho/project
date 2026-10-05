import { loadStateName, loadStates, getStates, showNoResults } from '/js/functions.js'

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')

const filter_all = document.querySelector('#filter_all')
const filter_search = document.querySelector('#filter_search')
const filter_active = document.querySelector('#filter_active')
const search_btn = document.querySelector('#search')
const filter_inactive = document.querySelector('#filter_inactive')
const filter_deleted = document.querySelector('#filter_deleted')

const qr_container = document.querySelector('#document_qr_container')
const print_qr_btn = document.querySelector('#print_qr_btn')
const close_qr_btn = document.querySelector('#close_qr_btn')
const download_qr_btn = document.querySelector('#img_qr_btn')
const document_qr = document.querySelector('#document_qr')

function getSelectedState() {

    if (filter_all.checked) return 0
    if (filter_active.checked) return 1
    if (filter_inactive.checked) return 2
    if (filter_deleted.checked) return 3

    return 0
}

async function loadData(container, documents) {

    container.innerHTML = ''

    try {

        const states = await getStates('/php/actions/document/get_documents_states.php')

        documents.forEach(document => {

            const state_id = Number(document.id_estado_documento)
            const document_id = Number(document.id_documento)
            const document_token = document.token

            container.innerHTML += 
            `
                <li>
                <p>${document.nombre}</p>
                <p>Estado: ${loadStateName(state_id, states, 'id_estado_documento')}</p>

                ${state_id !== 3 ? `
                    <label>Cambiar Estado
                    <select class='change-state-select' data-id='${document_id}'>
                    ${loadStates(state_id, states, 'id_estado_documento')}
                    </select>
                    </label>
                    <button class='confirm-btn' data-id='${document_id}'>
                        <i data-lucide="circle-check"></i>
                    </button>
                    <button class='view-btn' data-token='${document_token}'>
                        <i data-lucide="screen-share"></i>
                    </button>
                    <button class='qr-btn' data-token='${document_token}'>
                        <i data-lucide="qr-code"></i>
                    </button>
                    <button class='download-btn' data-token='${document_token}'>
                        <i data-lucide="download"></i>
                    </button>
                    <button class='upd-btn' data-id='${document_id}'>
                        <i data-lucide="refresh-cw"></i>
                    </button>
                    ` : ''}
                </li>
            `
        })

        lucide.createIcons()
        const download_btn = document.querySelectorAll('.download-btn')
        const change_btn = document.querySelectorAll('.confirm-btn')
        const view_btn = document.querySelectorAll('.view-btn')
        const upd_btn = document.querySelectorAll('.upd-btn')
        const qr_btn = document.querySelectorAll('.qr-btn')

        change_btn.forEach(btn => {
            btn.addEventListener('click', async () => {
                const document_id = btn.dataset.id

                if (!confirm("¿Está seguro de modificar el documento?")) {
                    return
                }

                try {

                    const state_id = document.querySelector(`.change-state-select[data-id="${document_id}"]`).value
                    const form = new FormData()

                    form.append("document_id", document_id)
                    form.append("state_id", state_id)

                    const response = await fetch("/php/actions/document/document_change.php", {
                        method: 'POST',
                        body: form
                    })

                    const result = await response.json()
                    if (!result.success || !response.ok) {
                        alert(result.message || "Error al modificar el documento, intente nuevamente.")
                        return
                    }

                    const option = getSelectedState()
                    alert(result.message || "Estado modificado exitosamente.")
                    
                    searchFilterDocuments(option, filter_search.value.trim())

                } catch (error) {

                    // DESPUES QUITAR EL MENSAJE
                    alert("Error al modificar el documento, intente nuevamente.")
                    console.error(error.message)
                    return
                }
            })
        })

        view_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const document_token = btn.dataset.token
                location.href = `/php/pages/document/screen_document.php?token=${document_token}`
            })
        })

        upd_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const document_id = btn.dataset.id
                location.href = `/php/pages/document/document_form.php?id=${document_id}`
            })
        })

        download_btn.forEach(btn => {
            btn.addEventListener('click', () => {

                const document_token = btn.dataset.token
                location.href = `/php/actions/document/download_document.php?token=${document_token}`
            })
        })

        qr_btn.forEach(btn => {
            btn.addEventListener('click', async () => {

                try {

                    const document_token = btn.dataset.token
                    const response = await fetch(`/php/actions/document/get_document_path.php?token=${document_token}`)

                    const result = await response.json()
                    if (!response.ok || !result.success) {
                        alert(result.message || "Ha ocurrido un error, intente nuevamente")
                        return
                    }

                    const url = result.param

                    await QRCode.toCanvas(document_qr, url, {
                        width: 200,
                        height: 200
                    })

                    qr_container.classList.add('show')
                    qr_container.classList.remove('hidden')
                    
                } catch (error) {

                    alert(error.message || "Ha ocurrido un error, intente nuevamente")
                    return
                }
            })
        })

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return
    }
}

async function searchFilterDocuments(state_id, phrase = '') {

    try {

        const petition = phrase === '' ?
            `/php/actions/document/get_documents.php?id_state=${state_id}&phrase=null` :
            `/php/actions/document/get_documents.php?id_state=${state_id}&phrase=${encodeURIComponent(phrase)}`

        const response = await fetch(petition)
        const result = await response.json()

        if (!response.ok || !result.success) {
            console.error('Ha ocurrido un error en la petición de documentos')
            return
        }

        if (result.item.length === 0) {
            showNoResults(view_container, 'No se encontraron documentos',
                '/php/pages/document/document_form.php')
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
        showNoResults(view_container, 'No se encontraron documentos',
            '/php/pages/document/document_form.php')
    }
}

close_qr_btn.addEventListener('click', () => {

    if (confirm("¿Esta seguro de dejar de visualizar este QR?")) {

        document_qr.textContent = ""
        qr_container.classList.remove('show')
        qr_container.classList.add('hidden')

    }
})

download_qr_btn.addEventListener('click', () => {

    if (confirm("¿Esta seguro que desea descargar este QR?")) {
        const img = document_qr.toDataURL('image/png')

        const link = document.createElement('a')
        link.href = img

        link.download = 'codigo_qr.png'
        link.click()

    }
})

print_qr_btn.addEventListener('click', () => {

    if (confirm("¿Esta seguro que desea imprimir este QR?")) {
        
        const img = document_qr.toDataURL("image/png")
        const printWindow = window.open("", "_blank")

        printWindow.document.body.innerHTML = `
            <div style="
                width:100%;
                height:98vh;
                display:flex;
                justify-content:center;
                align-items:center;
            ">
                <img id="qr" src="${img}" style="width:400px;">
            </div>
        `

        printWindow.document.close()

        const qr = printWindow.document.getElementById("qr")

        qr.onload = () => {
            printWindow.focus()
            printWindow.print()
        }
    }
})

register_btn.addEventListener('click', () =>
    location.href = '/php/pages/document/document_form.php')

document.addEventListener('DOMContentLoaded', () =>
    searchFilterDocuments(0, ''))

filter_all.addEventListener('change', () => {

    if (filter_all.checked) {
        searchFilterDocuments(0, filter_search.value.trim())
    }
})

filter_active.addEventListener('change', () => {

    if (filter_active.checked) {
        searchFilterDocuments(1, filter_search.value.trim())
    }
})

filter_inactive.addEventListener('change', () => {

    if (filter_inactive.checked) {
        searchFilterDocuments(2, filter_search.value.trim())
    }
})

filter_deleted.addEventListener('change', () => {

    if (filter_deleted.checked) {
        searchFilterDocuments(3, filter_search.value.trim())
    }
})

search_btn.addEventListener('click', () => {

    const option = getSelectedState()
    searchFilterDocuments(option, filter_search.value.trim())
})

lucide.createIcons()