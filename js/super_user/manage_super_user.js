const changeState = document.querySelectorAll('.change-state')

changeState.forEach(btn => {

    btn.addEventListener('click', async () => {
        const superUserId = btn.dataset.id

        if (!confirm("¿Está seguro que desea cambiar el estado del administrador?")) {
            return
        }

        try {

            const stateId = document.querySelector(`.change-state-select[data-id="${superUserId}"]`).value
            const form = new FormData()
            form.append("super_user_id", superUserId)
            form.append("state_id", stateId)

            const response = await fetch("/php/actions/super_user/super_user_change.php", {
                method: 'POST',
                body: form
            })

            const result = await response.json()
            if (!result.success || !response.ok) {
                alert(result.message || "Error al cambiar el estado del administrador, intente nuevamente.")
                return
            }

            alert(result.message || "El estado del administrador se ha cambiado exitosamente.")
            location.reload()

        } catch (error) {

            alert("Error al cambiar el estado del administrador, intente nuevamente.")
            return
        }
    })
})