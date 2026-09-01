import formTools from "/js/library.js"
const { registerValidator } = formTools

const form = document.querySelector("#document_upload_form")
const document_name = document.querySelector("#document_name")
const document_category = document.querySelector("#document_category")

const file_document = document.querySelector("#document")
const document_name_msg = document.querySelector("#document_name_msg")
const document_category_msg = document.querySelector("#document_category_msg")

document_name.addEventListener('input', () =>
    registerValidator.stringsInput(document_name, document_name_msg))

document_category.addEventListener('change', () =>
    registerValidator.selectsInput(document_category, document_category_msg))

form.addEventListener('submit', async (e) => {
    e.preventDefault()

    if (!file_document.files.length) {
        alert("Es obligatorio cargar un documento PDF.")
        return
    }

    if (!registerValidator.selectsInput(document_category, document_category_msg) ||
    !registerValidator.stringsInput(document_name, document_name_msg)) {
        alert("Debe completar todos los campos")
        return
    }

    const formData = new FormData()
    formData.append('name', document_name.value.trim())
    formData.append('category_id', document_category.value)
    formData.append('file_document', file_document.files[0])

    try {

        const response = await fetch("/php/actions/document/upload_document.php", 
            {
                method: 'POST',
                body: formData
            }
        )

        const result = await response.json()

        if (!result.success || !response.ok) {
            alert(result.message || "Error al cargar el documento, intente nuevamente.")
            return
        }

        alert(result.message || "El documento fue cargado exitosamente.")
        location.reload()

    } catch (error) {

        alert("Ha ocurrido un error: " + error.message)
        return
    }

})