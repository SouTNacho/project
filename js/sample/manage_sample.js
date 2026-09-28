const changeState = document.querySelectorAll('.change-state')

changeState.forEach(btn => {

    btn.addEventListener('click', async () => {
        const sampleId = btn.dataset.id

        if (!confirm("¿Está seguro que desea cambiar el estado de la muestra?")) {
            return
        }

        try {

            const stateId = document.querySelector(`.change-state-select[data-id="${sampleId}"]`).value
            const form = new FormData()
            form.append("sample_id", sampleId)
            form.append("state_id", stateId)

            const response = await fetch("/php/actions/sample/sample_change.php", {
                method: 'POST',
                body: form
            })

            const result = await response.json()
            if (!result.success || !response.ok) {
                alert(result.message || "Error al cambiar el estado de la muestra, intente nuevamente.")
                return
            }

            alert(result.message || "El estado de la muestra se ha cambiado exitosamente.")
            location.reload()

        } catch (error) {

            alert("Error al cambiar el estado de la muestra, intente nuevamente.")
            return
        }
    })
})