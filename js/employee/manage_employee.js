import formTools from "/js/library.js"
const { registerValidator } = formTools

const container = document.querySelector("#change-password-dialog")
const changeStateButtons = document.querySelectorAll('.change-state-button')
const changePasswordButtons = document.querySelectorAll('.change-password-button')

changeStateButtons.forEach(btn => {

    btn.addEventListener('click', async () => {
        const employeeId = btn.dataset.id

        if (!confirm("¿Está seguro que desea cambiar el estado del empleado?")) {
            return
        }

        try {

            const stateId = document.querySelector(`.change-state-select[data-id="${employeeId}"]`).value
            const form = new FormData()
            form.append("employee_id", employeeId)
            form.append("state_id", stateId)

            const response = await fetch("actions/employee_change_state.php", {
                method: 'POST',
                body: form
            })

            const result = await response.json()
            if (!result.success || !response.ok) {
                alert(result.message || "Error al cambiar el estado del empleado, intente nuevamente.")
                return
            }

            alert(result.message || "El estado del empleado se ha cambiado exitosamente.")
            container.close()
            location.reload()

        } catch (error) {

            alert("Error al cambiar el estado del empleado, intente nuevamente.")
            return
        }
    })
})

changePasswordButtons.forEach(btn => {

    btn.addEventListener('click', async () => {
        const employeeId = btn.dataset.id

        try {

            const file = await fetch("/pages/change_password_dialog.html")
            const content = await file.text()
            container.innerHTML = content
            container.showModal()

            container.style.position = "absolute"
            container.style.width = "60%"
            container.style.height = "40%"

            const form = document.querySelector("#change_password_form")
            const input_password = document.querySelector("#input_password")
            const confirm_password = document.querySelector("#confirm_password")
            const input_password_msg = document.querySelector("#input_password_msg")
            const confirm_password_msg = document.querySelector("#confirm_password_msg")

            const cancelButton = document.querySelector("#cancel_btn")
            const confirmButton = document.querySelector("#confirm_btn")

            cancelButton.addEventListener('click', () => {
                container.close()
                return
            })

            confirmButton.addEventListener('click', async () => {

                input_password.addEventListener('input', () =>
                    registerValidator.passwordInput(input_password, input_password_msg))

                confirm_password.addEventListener('input', () =>
                    registerValidator.passwordMatch(confirm_password, confirm_password_msg, input_password))

                if (!registerValidator.passwordInput(input_password, input_password_msg) ||
                !registerValidator.passwordMatch(confirm_password, confirm_password_msg, input_password)) {
                    alert("Los datos ingresados no son válidos.")
                    return
                }

                alert(input_password.value)

                const formData = new FormData(form)
                formData.append("employee_id", employeeId)

                const response = await fetch("actions/employee_change_password.php", {
                    method: 'POST',
                    body: formData
                })

                const result = await response.json()

                if (!result.success || !response.ok) {
                    alert(result.message || "Error al cambiar la contraseña del empleado, intente nuevamente.")
                    return
                }

                alert(result.message || "La contraseña del empleado se ha cambiado exitosamente.")
                container.close()
                location.reload()

            })

        } catch (error) {

            alert("Error al cargar el formulario de cambio de contraseña, intente nuevamente.")
            return
        }
    })
})