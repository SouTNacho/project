import formTools from "/js/library.js"
const { registerValidator } = formTools

const id = Number(new URLSearchParams(window.location.search).get('id'))
const form = document.querySelector("#document_form")
const btn = document.querySelector("#document_btn")
const is_update = Number.isInteger(id) && id > 0
const title = document.querySelector('h2')

const defaultCategories = [
    { id_categoria: 1, nombre: 'Protocolos' },
    { id_categoria: 2, nombre: 'Procedimientos' },
    { id_categoria: 3, nombre: 'Manuales' },
    { id_categoria: 4, nombre: 'Instructivos' },
    { id_categoria: 5, nombre: 'Normativas' },
    { id_categoria: 6, nombre: 'Formularios' },
    { id_categoria: 7, nombre: 'Circulares y Comunicados' },
    { id_categoria: 8, nombre: 'Guías Clínicas' },
    { id_categoria: 9, nombre: 'Capacitación' },
    { id_categoria: 10, nombre: 'Documentación Técnica' },
    { id_categoria: 11, nombre: 'Mantenimiento' },
    { id_categoria: 12, nombre: 'Calidad' },
    { id_categoria: 13, nombre: 'Seguridad' },
    { id_categoria: 14, nombre: 'Recursos Humanos' },
    { id_categoria: 15, nombre: 'Otros' }
]

async function loadCategories() {
    
    try {

        const response = await fetch('/php/actions/document/get_categories.php')
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Error en la solicitud')
            return defaultCategories
        }

        return result.item
    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error: ', error.message)
        return defaultCategories
    }
}

async function loadDocument() {

    try {

        const response = await fetch(`/php/actions/document/get_document_id.php?id=${id}`)
        const result = await response.json()

        if (!response.ok || !result.success || !result.item) {

            // DESPUES QUITAR EL MENSAJE
            console.error('Error al cargar el documento')
            return
        }

        const item = result.item
        document_name.placeholder = item.nombre
        document_category.value = item.id_categoria

    } catch (error) {

        // DESPUES QUITAR EL MENSAJE
        console.error('Ha ocurrido un error:', error.message)
    }
}

function createOptions(options, item) {
    item.innerHTML = '<option value="">Seleccione una opción</option>'

    options.forEach(option => {
        
        const opt = document.createElement('option')
        opt.textContent = option.nombre
        opt.value = option.id_categoria

        item.append(opt)
    })
}

const file_document = document.querySelector("#document")
const document_name = document.querySelector("#document_name")
const document_category = document.querySelector("#document_category")

const file_document_msg = document.querySelector("#document_msg")
const document_name_msg = document.querySelector("#document_name_msg")
const document_category_msg = document.querySelector("#document_category_msg")
const inputs = [file_document, document_name, document_category]

const categories = await loadCategories()
createOptions(categories, document_category)

let path = ''

if (is_update) {

    path = `/php/actions/document/document_update.php?id=${id}`
    title.textContent = 'Actualizar Documento'
    btn.innerHTML = `<i data-lucide="refresh-cw"></i>Actualizar`
    await loadDocument()
} else {

    path = '/php/actions/document/document_register.php'
    title.textContent = 'Registrar Documento'
    btn.innerHTML = `<i data-lucide="square-plus"></i>Registrar`
}

document_name.addEventListener('input', () => {

    if (document_name.value.trim() !== '') {

        return registerValidator.stringsInput(document_name, document_name_msg)
    }
    
    if (is_update) {

        return formTools.setValid(document_name, document_name_msg)
    }

    return formTools.setInvalid(document_name, document_name_msg, 'Este campo es obligatorio')
})

document_category.addEventListener('input', () => {

    if (document_category.value.trim() !== '') {

        return registerValidator.selectsInput(document_category, document_category_msg)
    }
    
    if (is_update) {

        return formTools.setValid(document_category, document_category_msg)
    }

    return formTools.setInvalid(document_category, document_category_msg, 'Este campo es obligatorio')
})

form.addEventListener('submit', async (e) => {
    e.preventDefault()

    if (is_update) {

        let hasChanges = false
        for (const input of inputs) {
            
            if (input.type === 'file') {

                if (input.files.length) {
                    hasChanges = true
                    break
                }

            } else if (input.value.trim() !== '') {

                hasChanges = true
                break

            }
        }

        if (!hasChanges) {
            alert("Debe completar al menos un campo")
            return
        }
    }

    if (document_name.value.trim() === '') {

        if (!is_update) {

            alert("El nombre es obligatorio")
            return
        }
    } else {

        if (!registerValidator.stringsInput(document_name, document_name_msg)) {
            alert("El nombre no es válido")
            return
        }
    }

    if (document_category.value.trim() === '') {

        if (!is_update) {

            alert("La categoría es obligatoria")
            return
        }
    } else {

        if (!registerValidator.selectsInput(document_category, document_category_msg)) {
            alert("La categoría no es válida")
            return
        }
    }

    let file = null

    if (!file_document.files.length) {
        if (!is_update) {

            formTools.setInvalid(file_document, file_document_msg, "Debe seleccionar un archivo")
            return
        }
    } else {
        file = file_document.files[0]

        if (file.type !== 'application/pdf') {

            formTools.setInvalid(file_document, file_document_msg, "El archivo debe ser un PDF")
            return
        }

        formTools.setValid(file_document, file_document_msg)
    }

    const formData = new FormData()
    
    if (document_name.value.trim() !== '') {
        formData.append('name', document_name.value.trim())
    }

    if (document_category.value !== '') {
        formData.append('category_id', document_category.value)
    }

    if (file) {
        formData.append('file_document', file)
    }

    try {

        const response = await fetch(path, 
            {
                method: 'POST',
                body: formData
            }
        )

        const result = await response.json()

        if (!result.success || !response.ok) {
            alert(result.message || "Error al registrar.")
            return
        }

        alert(result.message || "El registro ha sido exitoso.")
        location.reload()

    } catch (error) {

        alert("Ha ocurrido un error: " + error.message)
        return
    }
})

lucide.createIcons()