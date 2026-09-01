const changeState = document.querySelectorAll('.change-state')

changeState.forEach(btn => {

    btn.addEventListener('click', async () => {
        const elementId = btn.dataset.id

        if (!confirm("¿Está seguro que desea cambiar el estado del elemento?")) {
            return
        }

        try {

            const stateId = document.querySelector(`.change-state-select[data-id="${elementId}"]`).value
            const form = new FormData()
            form.append("element_id", elementId)
            form.append("state_id", stateId)

            const response = await fetch("/php/actions/element/element_change.php", {
                method: 'POST',
                body: form
            })

            const result = await response.json()
            if (!result.success || !response.ok) {
                alert(result.message || "Error al cambiar el estado del elemento, intente nuevamente.")
                return
            }

            alert(result.message || "El estado del elemento se ha cambiado exitosamente.")
            location.reload()

        } catch (error) {

            alert("Error al cambiar el estado del elemento, intente nuevamente.")
            return
        }
    })
})