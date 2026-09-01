const changeState = document.querySelectorAll('.change-state')

changeState.forEach(btn => {

    btn.addEventListener('click', async () => {
        const patientID = btn.dataset.id

        if (!confirm("¿Está seguro que desea cambiar el estado del paciente?")) {
            return
        }

        try {

            const stateId = document.querySelector(`.change-state-select[data-id="${patientID}"]`).value
            const form = new FormData()
            form.append("patient_id", patientID)
            form.append("state_id", stateId)

            const response = await fetch("actions/patient_change_state.php", {
                method: 'POST',
                body: form
            })

            const result = await response.json()
            if (!result.success || !response.ok) {
                alert(result.message || "Error al cambiar el estado del paciente, intente nuevamente.")
                return
            }

            alert(result.message || "El estado del empleado se ha cambiado exitosamente.")
            location.reload()

        } catch (error) {

            alert("Error al cambiar el estado del empleado, intente nuevamente.")
            return
        }
    })
})