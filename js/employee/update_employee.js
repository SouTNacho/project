import formTools from "/js/library.js"
const { registerValidator } = formTools

const update_form = document.querySelector("#employee_update_form")
const dinamic_container = document.querySelector("#employee_extra_information")
const other_locality_container = document.querySelector("#other_locality_container")

const employee_id = document.querySelector("#employee_id")
const first_name = document.querySelector("#employee_first_name")
const last_name = document.querySelector("#employee_last_name")
const employee_document = document.querySelector("#employee_document")
const nationality = document.querySelector("#employee_nationality")
const birthdate = document.querySelector("#employee_birthdate")
const department = document.querySelector("#employee_department")
const locality = document.querySelector("#employee_locality")
const other_locality = document.querySelector("#other_locality")
const address = document.querySelector("#employee_address")
const address_number = document.querySelector("#employee_address_number")
const email = document.querySelector("#employee_email")
const position = document.querySelector("#employee_position")
const entry_date = document.querySelector("#employee_entry_date")
const register_btn = document.querySelector("#employee_update_btn")

const employee_id_msg = document.querySelector("#employee_id_msg")
const first_name_msg = document.querySelector("#employee_first_name_msg")
const last_name_msg = document.querySelector("#employee_last_name_msg")
const employee_document_msg = document.querySelector("#employee_document_msg")
const nationality_msg = document.querySelector("#employee_nationality_msg")
const birthdate_msg = document.querySelector("#employee_birthdate_msg")
const department_msg = document.querySelector("#employee_department_msg")
const locality_msg = document.querySelector("#employee_locality_msg")
const other_locality_msg = document.querySelector("#other_locality_msg")
const address_msg = document.querySelector("#employee_address_msg")
const address_number_msg = document.querySelector("#employee_address_number_msg")
const email_msg = document.querySelector("#employee_email_msg")
const position_msg = document.querySelector("#employee_position_msg")
const entry_date_msg = document.querySelector("#employee_entry_date_msg")
const register_btn_msg = document.querySelector("#employee_update_btn_msg")

let speciality
let speciality_msg
let license_category
let license_category_msg
let license_expiration
let license_expiration_msg
let permissions
let permissions_msg

formTools.loadNationalitiesSelect(nationality)
formTools.loadDepartmentsSelect(department)

employee_id.addEventListener('input', () => 
    formTools.loginValidator.idEmployeeInput(employee_id, employee_id_msg))

first_name.addEventListener('input', () => {
    
    if (first_name.value.trim() !== "") {

        registerValidator.namesInput(first_name, first_name_msg)
    } else {

        return formTools.setValid(first_name, first_name_msg)
    }

})

last_name.addEventListener('input', () => {

    if (last_name.value.trim() !== "") {

        registerValidator.namesInput(last_name, last_name_msg)
    } else {

        return formTools.setValid(last_name, last_name_msg)
    }
})

employee_document.addEventListener('input', () => {

    if (employee_document.value.trim() !== "") {

        registerValidator.documentIdInput(employee_document, employee_document_msg)
    } else {

        return formTools.setValid(employee_document, employee_document_msg)
    }
})

nationality.addEventListener('change', () => {

    if (nationality.value.trim() !== "") {

        registerValidator.selectsInput(nationality, nationality_msg)
    } else {

        return formTools.setValid(nationality, nationality_msg)
    }
})

birthdate.addEventListener('input', () => {

    if (birthdate.value.trim() !== "") {

        registerValidator.dateInput(birthdate, birthdate_msg)
    } else {

        return formTools.setValid(birthdate, birthdate_msg)
    }
})

address.addEventListener('input', () => {

    if (address.value.trim() !== "") {

        registerValidator.stringsInput(address, address_msg)
    } else {

        return formTools.setValid(address, address_msg)
    }
})

address_number.addEventListener('input', () => {

    if (address_number.value.trim() !== "") {

        registerValidator.doorNumberInput(address_number, address_number_msg)
    } else {

        return formTools.setValid(address_number, address_number_msg)
    }
})

email.addEventListener('input', () => {
    
    if (email.value.trim() !== "") {

        registerValidator.emailInput(email, email_msg)
    } else {

        return formTools.setValid(email, email_msg)
    }
})

entry_date.addEventListener('input', () => {

    if (entry_date.value.trim() !== "") {

        registerValidator.dateInput(entry_date, entry_date_msg)
    } else {

        return formTools.setValid(entry_date, entry_date_msg)
    }
})

department.addEventListener('change', () => { 

    if (department.value.trim() !== "") {

        formTools.loadLocationsSelect(locality, department)
        registerValidator.selectsInput(department, department_msg)
        formTools.setInvalid(locality, locality_msg, 'Debe seleccionar una localidad')
    } else {

        other_locality_container.classList.add('hidden')
        other_locality.value = ""

        locality.innerHTML = '<option value="">Seleccione una opción</option>'
        formTools.setValid(locality, locality_msg)
        return formTools.setValid(department, department_msg)
    }
})

locality.addEventListener('change', () => {

    if (locality.value.trim() === ''){

        if (department.value.trim() !== "") {

            return formTools.setInvalid(locality, locality_msg, 'Debe seleccionar una localidad')
        }
        return formTools.setValid(locality, locality_msg)
    }

    registerValidator.localitySelect(locality, locality_msg, other_locality, other_locality_container)

    if (locality.value.trim() === "Otra localidad") {

        formTools.setInvalid(other_locality, other_locality_msg, 'Debe ingresar la localidad')
    }

})

other_locality.addEventListener('input', () => {

    if (other_locality.value.trim() !== "") {

        registerValidator.stringsInput(other_locality, other_locality_msg)
    } else {

        if (locality.value.trim() === "Otra localidad") {

            return formTools.setInvalid(other_locality, other_locality_msg, 'Debe ingresar la localidad')
        } else {

            return formTools.setValid(other_locality, other_locality_msg)
        }
    }
})

position.addEventListener('change', async() => { 

    if (position.value.trim() !== "") {

        const value = position.value.trim()
        registerValidator.selectsInput(position, position_msg)

        if (value === "FA") {

            try {

                const file = await fetch("/pages/administrator_specialist.html")
                dinamic_container.innerHTML = await file.text()

                permissions = document.querySelector("#employee_permissions")
                permissions_msg = document.querySelector("#employee_permissions_msg")

                formTools.setInvalid(permissions, permissions_msg, 'Debe completar los datos adicionales')

                permissions.addEventListener('change', () => 
                    registerValidator.selectsInput(permissions, permissions_msg))
            } catch(error) {

                alert("Ha ocurrido un error, intente seleccionar el cargo nuevamente")
                console.error("Error:", error.message)
            }
        }

        if (value === "CO") {

            try {

                const file = await fetch("/pages/copilot_specialist.html")
                dinamic_container.innerHTML = await file.text()

                speciality = document.querySelector("#employee_speciality")
                speciality_msg = document.querySelector("#employee_speciality_msg")

                formTools.setInvalid(speciality, speciality_msg, 'Debe completar los datos adicionales')

                speciality.addEventListener('change', () => 
                    registerValidator.selectsInput(speciality, speciality_msg))
            } catch(error) {

                alert("Ha ocurrido un error, intente seleccionar el cargo nuevamente")
                console.error("Error:", error.message)
            }
        }

        if (value === "DR") {

            try {

                const file = await fetch("/pages/driver_specialist.html")
                dinamic_container.innerHTML = await file.text()

                license_expiration = document.querySelector("#employee_license_expiration")
                license_category = document.querySelector("#employee_license_category")
                license_expiration_msg = document.querySelector("#employee_license_expiration_msg")
                license_category_msg = document.querySelector("#employee_license_category_msg")

                formTools.setInvalid(license_expiration, license_expiration_msg, 'Debe completar los datos adicionales')
                formTools.setInvalid(license_category, license_category_msg, 'Debe completar los datos adicionales')

                license_expiration.addEventListener('change', () => 
                    registerValidator.selectsInput(license_expiration, license_expiration_msg))
                license_category.addEventListener('change', () => 
                    registerValidator.selectsInput(license_category, license_category_msg))
            } catch(error) {

                alert("Ha ocurrido un error, intente seleccionar el cargo nuevamente")
                console.error("Error:", error.message)
            }
        }
    } else {

        dinamic_container.innerHTML = ""
        return formTools.setValid(position, position_msg)
    }

})

update_form.addEventListener('submit', (event) => {
    event.preventDefault()

    if (!formTools.loginValidator.idEmployeeInput(employee_id, employee_id_msg)) {

        alert("El código de funcionario es obligatorio")
        return
    }

    if (position.value.trim() !== "") {

        const value = position.value.trim()
        registerValidator.selectsInput(position, position_msg)

        if (value === "FA") {
            if(!registerValidator.selectsInput(permissions, permissions_msg)) {

                alert("Debe completar todos los campos adicionales")
                return
            }
        }

        if (value === "CO") {
            if(!registerValidator.selectsInput(speciality, speciality_msg)) {

                alert("Debe completar todos los campos adicionales")
                return
            }
        }

        if (value === "DR") {
            if(!registerValidator.selectsInput(license_expiration, license_expiration_msg) ||
            !registerValidator.selectsInput(license_category, license_category_msg)) {

                alert("Debe completar todos los campos adicionales")
                return
            }
        }
    }

    if (first_name.value.trim() !== "") {

        if (!registerValidator.namesInput(first_name, first_name_msg)) {

            alert("El nombre debe ser válido o estar vacío")
            return
        }
    }

    if (last_name.value.trim() !== "") {

        if (!registerValidator.namesInput(last_name, last_name_msg)) {

            alert("El apellido debe ser válido o estar vacío")
            return
        }
    }

    if (employee_document.value.trim() !== "") {

        if (!registerValidator.documentIdInput(employee_document, employee_document_msg)) {

            alert("El documento debe ser válido o estar vacío")
            return
        }
    }

    if (nationality.value.trim() !== "") {

        if (!registerValidator.selectsInput(nationality, nationality_msg)) {

            alert("La nacionalidad debe ser válida o estar vacía")
            return
        }
    }

    if (birthdate.value.trim() !== "") {

        if (!registerValidator.dateInput(birthdate, birthdate_msg)) {

            alert("La fecha de nacimiento debe ser válida o estar vacía")
            return
        }
    }

    if (department.value.trim() !== "") {

        if (!registerValidator.selectsInput(department, department_msg)) {
            
            alert("El departamento debe ser válido o estar vacío")
            return
        }

        if (locality.value.trim() === "") {

            alert("La localidad es obligatoria si se selecciona un departamento")
            return
        }

        if (!registerValidator.localitySelect(locality, locality_msg, other_locality, other_locality_container)) {

            alert("La localidad seleccionada no es válida")
            return
        }

        if (locality.value.trim() === "Otra localidad") {

            if (!registerValidator.stringsInput(other_locality, other_locality_msg)) {

                alert("La localidad es obligatoria si se selecciona 'Otra localidad'")
                return
            }
        }
    }

    if (address.value.trim() !== "") {

        if (!registerValidator.stringsInput(address, address_msg)) {

            alert("La dirección debe ser válida o estar vacía")
            return
        }
    }

    if (address_number.value.trim() !== "") {

        if (!registerValidator.doorNumberInput(address_number, address_number_msg)) {
            alert("El número de puerta debe ser válido o estar vacío")
            return
        }
    }
    
    if (email.value.trim() !== "") {

        if (!registerValidator.emailInput(email, email_msg)) {

            alert("El email debe ser válido o estar vacío")
            return
        }
    }

    if (entry_date.value.trim() !== "") {

        if (!registerValidator.dateInput(entry_date, entry_date_msg)) {

            alert("La fecha de ingreso debe ser válida o estar vacía")
            return
        }
    }

    update_form.submit()
})