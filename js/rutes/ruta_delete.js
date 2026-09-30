const deleteForm = document.getElementById('delete_ruta_form')

if (deleteForm) {

    const rutaDelete = document.getElementById('ruta_delete')

    deleteForm.addEventListener('submit', async (event) => {

        event.preventDefault()

        const nombre = rutaDelete.value.trim()

        if (nombre === '') {
            alert('Ingrese el nombre de la ruta.')
            return
        }

        if (!confirm('¿Está seguro de desactivar esta ruta?')) {
            return
        }

        const data = new FormData()
        data.append('accion', 'eliminar')
        data.append('ruta_delete', nombre)

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
                alert(`Error HTTP ${response.status} al desactivar la ruta.`)
                return
            }

            alert(result)

            if (result.includes('correctamente')) {
                window.location.href = '/php/pages/routes/route_panel.php'
            }

        } catch (error) {

            console.error(error)
            alert('No se pudo desactivar la ruta.')
        }
    })
}
