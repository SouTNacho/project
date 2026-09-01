const changeState = document.querySelectorAll('.change-state')

changeState.forEach(btn => {

    btn.addEventListener('click', async () => {
        const ambulanceId = btn.dataset.id

        if (!confirm("¿Está seguro que desea cambiar el estado de la ambulancia?")) {
            return
        }

        try {

            const stateId = document.querySelector(`.change-state-select[data-id="${ambulanceId}"]`).value
            const form = new FormData()
            form.append("ambulance_id", ambulanceId)
            form.append("state_id", stateId)

            const response = await fetch("/php/actions/ambulance/ambulance_change.php", {
                method: 'POST',
                body: form
            })

            const result = await response.json()
            if (!result.success || !response.ok) {
                alert(result.message || "Error al cambiar el estado de la ambulancia, intente nuevamente.")
                return
            }

            alert(result.message || "El estado de la ambulancia se ha cambiado exitosamente.")
            location.reload()

        } catch (error) {

            alert("Error al cambiar el estado de la ambulancia, intente nuevamente.")
            return
        }
    })
})