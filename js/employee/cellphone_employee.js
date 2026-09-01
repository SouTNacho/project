import formTools from "/js/library.js"
const { registerValidator } = formTools
const { loginValidator } = formTools

const cellphone_form = document.querySelector("#employee_manage_cellphone_form")
const action_container = document.querySelector("#employee_cellphone_action_container")
const employee_id = document.querySelector("#employee_id")
const action = document.querySelector("#employee_cellphone_action")

const id_msg = document.querySelector("#employee_id_msg")
const action_msg = document.querySelector("#employee_cellphone_action_msg")

let cellphone_number_code
let other_cellphone_msg
let cellphone_number
let cellphone_msg
let new_number
let new_code

employee_id.addEventListener('input', () => loginValidator.idEmployeeInput(employee_id, id_msg))

action.addEventListener('change', async() => {

    registerValidator.selectsInput(action, action_msg)
    
    if (action.value.trim() === "") {

        action_container.innerHTML = ""
        return
    }

    if (action.value.trim() === "rm" || 
        action.value.trim() === "cr") {

        try {
            
            action_container.innerHTML = ""
            const file = await fetch("/pages/insert_delete_cellphone.html")
            action_container.innerHTML = await file.text()

            cellphone_msg = document.querySelector("#cellphone_msg")
            cellphone_number_code = document.querySelector("#cellphone_code")
            cellphone_number = document.querySelector("#cellphone_number")
            formTools.loadPhoneCodesSelect(cellphone_number_code)

            cellphone_number_code.addEventListener('change', () =>
                registerValidator.selectsInput(cellphone_number_code, cellphone_msg))

            cellphone_number.addEventListener('input', () =>
                registerValidator.phoneNumberInput(cellphone_number, cellphone_msg))
        } catch (error) {
            
            alert("Ha ocurrido un error, intente seleccionar nuevamente")
            return
        }
    }

    if (action.value.trim() === "up") {

        try {
            
            action_container.innerHTML = ""
            const file = await fetch("/pages/update_cellphone.html")
            action_container.innerHTML = await file.text()

            cellphone_msg = document.querySelector("#cellphone_msg")
            cellphone_number_code = document.querySelector("#cellphone_code")
            cellphone_number = document.querySelector("#cellphone_number")
            formTools.loadPhoneCodesSelect(cellphone_number_code)

            other_cellphone_msg = document.querySelector("#other_cellphone_msg")
            new_code = document.querySelector("#other_cellphone_code")
            new_number = document.querySelector("#other_cellphone_number")
            formTools.loadPhoneCodesSelect(new_code)

            cellphone_number_code.addEventListener('change', () =>
                registerValidator.selectsInput(cellphone_number_code, cellphone_msg))

            cellphone_number.addEventListener('input', () =>
                registerValidator.phoneNumberInput(cellphone_number, cellphone_msg))

            new_code.addEventListener('change', () =>
                registerValidator.selectsInput(new_code, other_cellphone_msg))

            new_number.addEventListener('input', () =>
                registerValidator.phoneNumberInput(new_number, other_cellphone_msg))
        } catch (error) {
            
            alert("Ha ocurrido un error, intente seleccionar nuevamente")
            return
        }
    }
})

cellphone_form.addEventListener('submit', (event) => {
    event.preventDefault()

    if (!loginValidator.idEmployeeInput(employee_id, id_msg) ||
        !registerValidator.selectsInput(action, action_msg)) {

        alert("El código del funcionario es obligatorio")
        return
    }

    if (registerValidator.selectsInput(action, action_msg)) {

        switch(action.value.trim()) {

            case "rm":

                if (!registerValidator.selectsInput(cellphone_number_code, cellphone_msg) ||
                    !registerValidator.phoneNumberInput(cellphone_number, cellphone_msg)) {

                    alert("Todos los campos son obligatorios")
                    return
                }
                break
            case "cr":

                if (!registerValidator.selectsInput(cellphone_number_code, cellphone_msg) ||
                    !registerValidator.phoneNumberInput(cellphone_number, cellphone_msg)) {

                    alert("Todos los campos son obligatorios")
                    return
                }
                
                if (!registerValidator.fullPhoneNumber(cellphone_number_code, cellphone_number)) {

                    alert("El telefono ingresado para registrar no es válido")
                    return
                }
                break
            case "up":

                if (!registerValidator.selectsInput(cellphone_number_code, cellphone_msg) ||
                    !registerValidator.phoneNumberInput(cellphone_number, cellphone_msg) ||
                    !registerValidator.selectsInput(new_code, other_cellphone_msg) ||
                    !registerValidator.phoneNumberInput(new_number, other_cellphone_msg)) {

                    alert("Todos los campos son obligatorios")
                    return
                }

                if (!registerValidator.fullPhoneNumber(new_code, new_number)) {

                    alert("El telefono para actalizar ingresado no es válido")
                    return
                }
                break
        }
    }

    cellphone_form.submit()
})