const updateForm = document.getElementById('update_ruta_form')

if (updateForm) {

    const nombreUpdate = document.getElementById('nombre_ruta_update')
    const nombreNuevo = document.getElementById('nombre_nuevo')
    const origenUpdate = document.getElementById('origen_update')
    const destinoUpdate = document.getElementById('destino_update')
    const descripcionUpdate = document.getElementById('descripcion_update')
    const estadoUpdate = document.getElementById('id_estado_ruta_update')

    updateForm.addEventListener('submit', async (event) => {

        event.preventDefault()

        const nombreActual = nombreUpdate.value.trim()
        const nombreNuevoValue = nombreNuevo.value.trim()
        const origenValue = origenUpdate.value.trim()
        const destinoValue = destinoUpdate.value.trim()
        const descripcionValue = descripcionUpdate.value.trim()

        if (nombreActual === '') {
            alert('Completa el nombre actual de la ruta.')
            return
        }

        if (
            nombreNuevoValue === '' &&
            origenValue === '' &&
            destinoValue === '' &&
            descripcionValue === '' &&
            estadoUpdate.value === '1'
        ) {
            alert('Completa al menos un dato para actualizar.')
            return
        }

        const data = new FormData()

        data.append('accion', 'actualizar')
        data.append('nombre_update', nombreActual)
        data.append('nombre_nuevo', nombreNuevoValue)
        data.append('origen_update', origenValue)
        data.append('destino_update', destinoValue)
        data.append('descripcion_update', descripcionValue)
        data.append('id_estado_ruta_update', estadoUpdate.value)

        try {

            const response = await fetch(
                '/php/actions/rutes/process_ruta.php',
                {
                    method: 'POST',
                    body: data
                }
            )

            const result = await response.text()

            if (!response.ok) {
                console.error(result)
                alert(`Error HTTP ${response.status} al actualizar la ruta.`)
                return
            }

            alert(result)

            if (result.includes('correctamente')) {
                window.location.href = '/php/pages/routes/route_panel.php'
            }

        } catch (error) {

            console.error(error)
            alert('No se pudo actualizar la ruta.')
        }
    })
}
