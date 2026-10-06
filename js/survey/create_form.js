import formTools from "/js/library.js"
const { registerValidator } = formTools

const form = document.querySelector("#create_survey_form")
const survey_name = document.querySelector("#survey_name")
const survey_service = document.querySelector("#survey_service")
const boolean_question = document.querySelector("#boolean_question")
const satisfaction_question = document.querySelector("#satisfaction_question")
const question_container = document.querySelector("#question_container")
const question_empty = document.querySelector("#question_empty")
const preview_title = document.querySelector("#preview_title")
const preview_questions = document.querySelector("#preview_questions")
const preview_empty = document.querySelector("#preview_empty")

const name_msg = document.querySelector("#survey_name_msg")
const service_msg = document.querySelector("#survey_service_msg")
const container_msg = document.querySelector("#question_container_msg")

const options = {
    boolean: {
        1: 'Sí',
        2: 'No'
    },
    satisfaction: {
        1: 'Muy insatisfecho',
        2: 'Insatisfecho',
        3: 'Ni satisfecho ni insatisfecho',
        4: 'Satisfecho',
        5: 'Muy satisfecho'
    }
}

const back_btn = document.querySelector("#back")
const cancel_btn = document.querySelector("#cancel")

back_btn.addEventListener('click', () => {
    location.href = '/php/pages/survey/screen_surveys.php'
})

cancel_btn.addEventListener('click', () => {
    location.href = '/php/pages/survey/screen_surveys.php'
})

const services_list = async () => {
    try {
        const response = await fetch('/php/actions/survey/get_services.php')
        const result = await response.json()

        if (!response.ok || !result.succes) {
            return [
                { id_servicio: 1, nombre: 'Documento' },
                { id_servicio: 2, nombre: 'Traslado' }
            ]
        }

        return result.item
    } catch (error) {
        return [
            { id_servicio: 1, nombre: 'Documento' },
            { id_servicio: 2, nombre: 'Traslado' }
        ]
    }
}

const servs = await services_list()
servs.forEach(service => {
    const option = document.createElement('option')
    option.value = service.id_servicio
    option.textContent = service.nombre
    survey_service.append(option)
})

function refreshEmptyState() {
    const questions = question_container.querySelectorAll('.question-card')
    question_empty.style.display = questions.length ? 'none' : 'flex'
}

function createPreviewOption(type, optionText, index) {
    const label = document.createElement('label')
    label.className = 'preview-option'

    const input = document.createElement('input')
    input.type = 'radio'
    input.disabled = true
    input.name = `preview_${type}_${index}`

    const span = document.createElement('span')
    span.textContent = optionText

    label.append(input, span)
    return label
}

function updatePreview() {
    preview_title.textContent = survey_name.value.trim() || 'Nombre de la encuesta'
    preview_questions.innerHTML = ''

    const cards = question_container.querySelectorAll('.question-card')
    preview_empty.style.display = cards.length ? 'none' : 'block'

    cards.forEach((card, index) => {
        const type = card.dataset.type
        const input = card.querySelector('.question-input')
        const question = input.value.trim() || 'Escriba aquí la pregunta'

        const item = document.createElement('div')
        item.className = 'preview-question'

        const title = document.createElement('h4')
        title.textContent = `${index + 1}. ${question}`
        item.append(title)

        const typeOptions = type === 'boolean' ? options.boolean : options.satisfaction
        Object.values(typeOptions).forEach((text, optionIndex) => {
            item.append(createPreviewOption(type, text, `${index}_${optionIndex}`))
        })

        preview_questions.append(item)
    })
}

function createQuestion(type) {
    const card = document.createElement('article')
    card.className = 'question-card'
    card.dataset.type = type

    const header = document.createElement('div')
    header.className = 'question-card-header'

    const badge = document.createElement('span')
    badge.className = 'question-badge'
    badge.textContent = type === 'boolean' ? 'SÍ / NO' : 'SATISFACCIÓN'

    const removeButton = document.createElement('button')
    removeButton.type = 'button'
    removeButton.className = 'remove-question'
    removeButton.textContent = 'Eliminar'
    removeButton.addEventListener('click', () => {
        card.remove()
        refreshEmptyState()
        updatePreview()
    })

    header.append(badge, removeButton)

    const label = document.createElement('label')
    label.textContent = 'Pregunta'
    label.className = 'question-label'

    const input = document.createElement('input')
    input.type = 'text'
    input.className = `question-input ${type}`
    input.placeholder = 'Escriba aquí la pregunta que desea realizar'
    input.addEventListener('input', updatePreview)

    const optionsTitle = document.createElement('span')
    optionsTitle.className = 'question-options-title'
    optionsTitle.textContent = 'Opciones de respuesta'

    const optionsBox = document.createElement('div')
    optionsBox.className = 'fixed-options'

    const typeOptions = type === 'boolean' ? options.boolean : options.satisfaction
    Object.values(typeOptions).forEach(text => {
        const option = document.createElement('span')
        option.className = 'fixed-option'
        option.textContent = text
        optionsBox.append(option)
    })

    card.append(header, label, input, optionsTitle, optionsBox)
    question_container.append(card)

    refreshEmptyState()
    updatePreview()
    input.focus()
}

boolean_question.addEventListener('click', () => createQuestion('boolean'))
satisfaction_question.addEventListener('click', () => createQuestion('satisfaction'))

survey_name.addEventListener('input', () => {
    registerValidator.largeStringsInput(survey_name, name_msg)
    updatePreview()
})

survey_service.addEventListener('change', () =>
    registerValidator.selectsInput(survey_service, service_msg))

form.addEventListener('submit', async e => {
    e.preventDefault()

    if (!registerValidator.largeStringsInput(survey_name, name_msg)) {
        alert('El nombre es obligatorio')
        return
    }

    if (!registerValidator.selectsInput(survey_service, service_msg)) {
        alert('El servicio asociado es obligatorio')
        return
    }

    const boolean = question_container.querySelectorAll('.question-input.boolean')
    const satisfaction = question_container.querySelectorAll('.question-input.satisfaction')

    if (boolean.length === 0 && satisfaction.length === 0) {
        alert('La encuesta debe tener al menos una pregunta')
        return
    }

    for (const input of [...boolean, ...satisfaction]) {
        if (!registerValidator.largeStringsInput(input, container_msg)) {
            alert('La pregunta ingresada no es válida')
            input.focus()
            return
        }
    }

    const Json = {
        title: survey_name.value.trim(),
        questions: []
    }

    question_container.querySelectorAll('.question-card').forEach(card => {
        const input = card.querySelector('.question-input')
        const type = card.dataset.type

        Json.questions.push({
            title: input.value.trim(),
            type,
            options: type === 'boolean' ? options.boolean : options.satisfaction
        })
    })

    const formData = new FormData()
    formData.append('survey_title', survey_name.value.trim())
    formData.append('id_service', survey_service.value)
    formData.append('survey', JSON.stringify(Json))

    try {
        const response = await fetch('/php/actions/survey/save_survey.php', {
            method: 'POST',
            body: formData
        })

        const result = await response.json()

        if (!response.ok || !result.succes) {
            alert(result.message || 'Ha ocurrido un error al crear la encuesta.')
            return
        }

        alert(result.message || 'La encuesta ha sido creada exitosamente.')
        location.href = '/php/pages/survey/screen_surveys.php'
    } catch (error) {
        alert(error.message || 'Ha ocurrido un error al crear la encuesta, intente nuevamente.')
    }
})

refreshEmptyState()
updatePreview()