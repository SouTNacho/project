const form = document.getElementById('register_ruta_form')

if (form) {

    const nombre = document.getElementById('nombre_ruta')
    const origen = document.getElementById('origen')
    const destino = document.getElementById('destino')
    const descripcion = document.getElementById('descripcion')

    form.addEventListener('submit', async (event) => {

        event.preventDefault()

        const nombreTrim = nombre.value.trim()
        const origenTrim = origen.value.trim()
        const destinoTrim = destino.value.trim()
        const descripcionTrim = descripcion.value.trim()

        if (nombreTrim === '' || origenTrim === '' || destinoTrim === '') {
            alert('Completa todos los campos obligatorios.')
            return
        }

        const data = new FormData()

        data.append('accion', 'agregar')
        data.append('nombre', nombreTrim)
        data.append('origen', origenTrim)
        data.append('destino', destinoTrim)
        data.append('descripcion', descripcionTrim)

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
                alert(`Error HTTP ${response.status} al registrar la ruta.`)
                return
            }

            alert(result)

            if (result.includes('correctamente')) {
                window.location.href = '/php/pages/routes/route_panel.php'
            }

        } catch (error) {

            console.error(error)
            alert('No se pudo registrar la ruta.')
        }
    })
}
