const deleteButtons = document.querySelectorAll('.delete-button')
const previewButtons = document.querySelectorAll('.preview-button')
const downloadButtons = document.querySelectorAll('.download-button')
const changeStateButtons = document.querySelectorAll('.change-state')
const generateQrButtons = document.querySelectorAll('.generate-qr-button')

const preview_container = document.querySelector('#document_preview_container')
const close_preview_btn = document.querySelector('#close_preview_btn')
const document_preview = document.querySelector('#document_preview')

const qr_container = document.querySelector('#document_qr_container')
const print_qr_btn = document.querySelector('#print_qr_btn')
const close_qr_btn = document.querySelector('#close_qr_btn')
const download_qr_btn = document.querySelector('#img_qr_btn')
const document_qr = document.querySelector('#document_qr')

close_qr_btn.addEventListener('click', () => {

    if (confirm("¿Esta seguro de dejar de visualizar este QR?")) {

        document_qr.textContent = ""
        qr_container.classList.remove('show')
        qr_container.classList.add('hidden')

    }
})

download_qr_btn.addEventListener('click', () => {

    if (confirm("¿Esta seguro que desea imprimir este QR?")) {

        // Hacer que descargue este qr

    }
})

print_qr_btn.addEventListener('click', () => {

    if (confirm("¿Esta seguro que desea imprimir este QR?")) {

        // Hacer que imprima este qr

    }
})

close_preview_btn.addEventListener('click', () => {

    if (confirm("¿Esta seguro de finalizar la previsualización?")) {

        document_preview.src = ""
        preview_container.classList.remove('show')
        preview_container.classList.add('hidden')

    }
})

generateQrButtons.forEach(btn => {
    
    btn.addEventListener('click', async() => {

        try {

            const document_token = btn.dataset.token
            const response = await fetch(`/php/actions/document/get_document_with_qr.php?token=${document_token}`)

            const result = await response.json()
            if (!response.ok || !result.succes) {
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

previewButtons.forEach(btn => {
    
    btn.addEventListener('click', async() => {

        try {

            const document_token = btn.dataset.token
            const response = await fetch(`/php/actions/document/get_document.php?token=${document_token}`)

            const result = await response.json()
            if (!response.ok || !result.succes) {
                alert(result.message || "Ha ocurrido un error, intente nuevamente")
                return
            }

            const document_address = result.param
            document_preview.src = document_address
            document_preview.width = "200%"
            document_preview.height = "200%"
            preview_container.classList.add('show')
            preview_container.classList.remove('hidden')
            
        } catch (error) {

            alert(result.message || "Ha ocurrido un error, intente nuevamente")
            return
        }
    })
})


deleteButtons.forEach(btn => {
    
    btn.addEventListener('click', async() => {

        if (confirm("¿Esta seguro de eliminar este documento?")) {

            try {

                const document_token = btn.dataset.token
                const response = await fetch(`/php/actions/document/delete_document.php?token=${document_token}`)

                const result = await response.json()
                if (!response.ok || !result.succes) {
                    alert(result.message || "Ha ocurrido un error, intente nuevamente")
                    return
                }

                alert(result.message || "El documento se elimino exitosamente")
                location.reload()
                
            } catch (error) {

                alert(result.message || "Ha ocurrido un error, intente nuevamente")
                return
            }
        }
    })
})


changeStateButtons.forEach(btn => {
    
    btn.addEventListener('click', async() => {

        try {

            const document_token = btn.dataset.token
            const response = await fetch(`/php/actions/document/change_state_document.php?token=${document_token}`)

            const result = await response.json()
            if (!response.ok || !result.succes) {
                alert(result.message || "Ha ocurrido un error, intente nuevamente")
                return
            }

            alert(result.message || "El documento se elimino exitosamente")
            location.reload()
            
        } catch (error) {

            alert(result.message || "Ha ocurrido un error, intente nuevamente")
            return
        }
    })
})

downloadButtons.forEach(btn => {
    
    btn.addEventListener('click', () => {

        try {

            const document_token = btn.dataset.token
            location.href = `/php/actions/document/download_document.php?token=${document_token}`
            
        } catch (error) {

            alert(result.message || "Ha ocurrido un error, intente nuevamente")
            return
        }
    })
})