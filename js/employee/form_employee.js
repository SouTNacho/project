import formTools from "/js/library.js"
const { registerValidator } = formTools

const other_locality_container = document.querySelector("#other_locality_container")
const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#employee_form")
const is_update = Number.isInteger(id) && id > 0
const title = document.querySelector('h2')

async function loadEmployee() {

    try {

        const response = await fetch(`/php/actions/employee/get_employee.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el empleado')
            return
        }

        const item = result.item

        first_name.placeholder = item.nombre
        last_name.placeholder = item.apellido
        employee_document.placeholder = item.cedula
        nationality.value = item.nacionalidad
        birthdate.value = item.fecha_nacimiento
        address.placeholder = item.direccion
        address_number.placeholder = item.numero_puerta
        email.placeholder = item.email
        entry_date.value = item.fecha_ingreso

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

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
const entry_date = document.querySelector("#employee_entry_date")
const btn = document.querySelector("#employee_btn")

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
const entry_date_msg = document.querySelector("#employee_entry_date_msg")
//const btn_msg = document.querySelector("#employee_btn_msg")

const inputs = [first_name, last_name, employee_document, nationality, birthdate,
    department, locality, other_locality, address, address_number, email, entry_date]

formTools.loadNationalitiesSelect(nationality)
formTools.loadDepartmentsSelect(department)

if (is_update) {

    form.action = `/php/actions/employee/employee_update.php?id=${id}`
    title.textContent = 'Actualizar Empleado'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadEmployee()
} else {

    form.action = '/php/actions/employee/employee_register.php'
    title.textContent = 'Registrar Empleado'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}

first_name.addEventListener('input', () => {

    if (first_name.value.trim() !== '') {

        return registerValidator.namesInput(first_name, first_name_msg)
    } 
    
    if (is_update) {

        return formTools.setValid(first_name, first_name_msg)
    }
    
    return formTools.setInvalid(first_name, first_name_msg, 'Este campo es obligatorio')
})

last_name.addEventListener('input', () => {

    if (last_name.value.trim() !== '') {

        return registerValidator.namesInput(last_name, last_name_msg)
    }

    if(is_update) {

        return formTools.setValid(last_name, last_name_msg)
    }

    return formTools.setInvalid(last_name, last_name_msg, 'Este campo es obligatorio')
})

employee_document.addEventListener('input', () => {

    if (employee_document.value.trim() !== '') {

        return registerValidator.documentIdInput(employee_document, employee_document_msg)
    }

    if(is_update) {

        return formTools.setValid(employee_document, employee_document_msg)
    }

    return formTools.setInvalid(employee_document, employee_document_msg, 'Este campo es obligatorio')
})

nationality.addEventListener('change', () => {

    if (nationality.value.trim() !== '') {

        return registerValidator.selectsInput(nationality, nationality_msg)
    }

    if(is_update) {

        return formTools.setValid(nationality, nationality_msg)
    }

    return formTools.setInvalid(nationality, nationality_msg, 'Este campo es obligatorio')
})

birthdate.addEventListener('change', () => {

    if (birthdate.value.trim() !== '') {

        return registerValidator.dateInput(birthdate, birthdate_msg)
    }

    if(is_update) {

        return formTools.setValid(birthdate, birthdate_msg)
    }

    return formTools.setInvalid(birthdate, birthdate_msg, 'Este campo es obligatorio')
})

other_locality.addEventListener('input', () => {

    if (other_locality.value.trim() !== '') {

        return registerValidator.stringsInput(other_locality, other_locality_msg)
    }

    if(is_update) {

        return formTools.setValid(other_locality, other_locality_msg)
    }

    return formTools.setInvalid(other_locality, other_locality_msg, 'Este campo es obligatorio')
})

address.addEventListener('input', () => {

    if (address.value.trim() !== '') {

        return registerValidator.stringsInput(address, address_msg)
    }

    if(is_update) {

        return formTools.setValid(address, address_msg)
    }

    return formTools.setInvalid(address, address_msg, 'Este campo es obligatorio')
})

address_number.addEventListener('input', () => {

    if (address_number.value.trim() !== '') {

        return registerValidator.doorNumberInput(address_number, address_number_msg)
    }

    if(is_update) {

        return formTools.setValid(address_number, address_number_msg)
    }

    return formTools.setInvalid(address_number, address_number_msg, 'Este campo es obligatorio')
})

email.addEventListener('input', () => {

    if (email.value.trim() !== '') {

        return registerValidator.emailInput(email, email_msg)
    }

    if(is_update) {

        return formTools.setValid(email, email_msg)
    }

    return formTools.setInvalid(email, email_msg, 'Este campo es obligatorio')
})

entry_date.addEventListener('change', () => {

    if (entry_date.value.trim() !== '') {

        return registerValidator.dateInput(entry_date, entry_date_msg)
    }

    if(is_update) {

        return formTools.setValid(entry_date, entry_date_msg)
    }

    return formTools.setInvalid(entry_date, entry_date_msg, 'Este campo es obligatorio')
})

department.addEventListener('change', () => {

    if (department.value.trim() !== '') {

        if(!registerValidator.selectsInput(department, department_msg)) {
            return
        }

        formTools.loadLocationsSelect(locality, department)
        registerValidator.localitySelect(locality, locality_msg, other_locality, other_locality_container)
        return formTools.setInvalid(locality, locality_msg, 'Debe seleccionar una localidad')
    }   

    locality.innerHTML = '<option value="">Seleccione una opción</option>'
    registerValidator.localitySelect( locality, locality_msg, other_locality, other_locality_container)

    if (is_update) {

        formTools.setValid(locality, locality_msg)
        return formTools.setValid(department, department_msg)
    }

    formTools.setInvalid(locality, locality_msg, 'Este campo es obligatorio')
    return formTools.setInvalid(department, department_msg, 'Este campo es obligatorio')
})

locality.addEventListener('change', () => {

    if (department.value.trim() === '') {

        if (locality.value.trim() !== '') {

            return formTools.setInvalid(locality, locality_msg,
                'Para seleccionar una localidad debe seleccionar un departamento')
        }

        return formTools.setValid(locality, locality_msg)
    }

    if (locality.value.trim() === '') {

        return formTools.setInvalid(locality, locality_msg,
            'Este campo es obligatorio')
    }

    return registerValidator.localitySelect(locality, locality_msg, other_locality, other_locality_container)
})

form.addEventListener('submit', (e) => {
    e.preventDefault()

    if (is_update) {

        let hasChanges = false
        for (const input of inputs) {
            if (input.value.trim() !== '') {
                hasChanges = true
                break
            }
        }

        if (!hasChanges) {
            alert("Debe completar al menos un campo")
            return
        }
    }

    if (first_name.value.trim() === '') {

        if (!is_update) {

            alert("El nombre es obligatorio")
            return
        }
    } else {

        if (!registerValidator.namesInput(first_name, first_name_msg)) {
            alert("El nombre no es válido")
            return
        }
    }

    if (last_name.value.trim() === '') {

        if (!is_update) {

            alert("El apellido es obligatorio")
            return
        }
    } else {

        if (!registerValidator.namesInput(last_name, last_name_msg)) {

            alert("El apellido no es válido")
            return
        }
    }

    if (employee_document.value.trim() === '') {

        if (!is_update) {

            alert("La cédula es obligatoria")
            return
        }
    } else {

        if (!registerValidator.documentIdInput(employee_document, employee_document_msg)) {

            alert("La cédula no es válida")
            return
        }
    }

    if (nationality.value.trim() === '') {

        if (!is_update) {

            alert("La nacionalidad es obligatoria")
            return
        }
    } else {

        if (!registerValidator.selectsInput(nationality, nationality_msg)) {

            alert("La nacionalidad no es válida")
            return
        }
    }

    if (birthdate.value.trim() === '') {

        if (!is_update) {

            alert("La fecha de nacimiento es obligatoria")
            return
        }
    } else {

        if (!registerValidator.dateInput(birthdate, birthdate_msg)) {

            alert("La fecha de nacimiento no es válida")
            return
        }
    }

    if (locality.value.trim() === '') {

        if (!is_update) {

            alert("La localidad es obligatoria")
            return
        }
    } else {

        if (!registerValidator.localitySelect(locality, locality_msg, other_locality, other_locality_container)) {

            alert("La localidad no es válida")
            return
        }
    }

    if (address.value.trim() === '') {

        if (!is_update) {

            alert("La dirección es obligatoria")
            return
        }
    } else {

        if (!registerValidator.stringsInput(address, address_msg)) {

            alert("La dirección no es válida")
            return
        }
    }

    if (address_number.value.trim() === '') {

        if (!is_update) {

            alert("El número de puerta es obligatorio")
            return
        }
    } else {

        if (!registerValidator.doorNumberInput(address_number, address_number_msg)) {

            alert("El número de puerta no es válido")
            return
        }
    }

    if (email.value.trim() === '') {

        if (!is_update) {

            alert("El correo electrónico es obligatorio")
            return
        }
    } else {

        if (!registerValidator.emailInput(email, email_msg)) {

            alert("El correo electrónico no es válido")
            return
        }
    }

    if (entry_date.value.trim() === '') {

        if (!is_update) {

            alert("La fecha de entrada es obligatoria")
            return
        }
    } else {

        if (!registerValidator.dateInput(entry_date, entry_date_msg)) {

            alert("La fecha de entrada no es válida")
            return
        }
    }

    if (other_locality.required) {
        if(!registerValidator.stringsInput(other_locality, other_locality_msg)) {

        alert("Debe completar todos los campos")
        return
        }
    }

    if (department.value.trim() === '') {

        if (locality.value.trim() !== '') {
            alert("Para seleccionar la localidad, es necesario seleccionar el departamento")
            return
        }

        if (!is_update) {

            alert("El departamento es obligatorio")
            return
        }
    } else {

        if (locality.value.trim() === '') {

            alert('La localidad es obligatoria')
            return
        }

        if (!registerValidator.selectsInput(department, department_msg)) {

            alert("El departamento no es válido")
            return
        }
        
    }

    form.submit()
})

lucide.createIcons()