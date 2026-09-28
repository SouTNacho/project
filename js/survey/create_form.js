import formTools from "/js/library.js"
const { registerValidator } = formTools

const form = document.querySelector("#create_survey_form")

const survey_name = document.querySelector("#survey_name")
const survey_service = document.querySelector("#survey_service")
const boolean_question = document.querySelector("#boolean_question")
const question_container = document.querySelector(".question_container")
const satisfaction_question = document.querySelector("#satisfaction_question")

const name_msg = document.querySelector("#survey_name_msg")
const service_msg = document.querySelector("#survey_service_msg")
const container_msg = document.querySelector("#question_container_msg")

const options = {

    boolean: {
        1: 'Si',
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

const services_list = async () => {

    try {

        const response = await fetch('/php/actions/survey/get_services.php')
        const result = await response.json()

        if (!response.ok || !result.succes) {
            
            return services = {
                1: 'Documento',
                2: 'Tralsado'
            }
        }

        return result.item

    } catch (error) {

        return services = {
            1: 'Documento',
            2: 'Tralsado'
        }
    }

}

const servs = await services_list()

servs.forEach(service => {

    const option = document.createElement('option')
    option.value = service.id_servicio
    option.textContent = service.nombre
    survey_service.append(option)
})

function createInput() {

    const input = document.createElement('input')
    input.type = 'text'
    input.placeholder = 'Escriba su pregunta'
    
    return input
}

boolean_question.addEventListener('click', () => {

    const input = createInput()
    input.classList.add('boolean')
    
    const div = document.createElement('div')
    div.append(input)

    question_container.append(div)
})

satisfaction_question.addEventListener('click', () => {

    const input = createInput()
    input.classList.add('satisfaction')
    
    const div = document.createElement('div')
    div.append(input)

    question_container.append(div)
})

survey_name.addEventListener('input', () =>
    registerValidator.largeStringsInput(survey_name, name_msg))

survey_service.addEventListener('change', () =>
    registerValidator.selectsInput(survey_service, service_msg))

form.addEventListener('submit', async (e) => {
    e.preventDefault()

    if (!registerValidator.largeStringsInput(survey_name, name_msg)) {

        alert('El nombre es obligatorio')
        return
    }

    if (!registerValidator.selectsInput(survey_service, service_msg)) {

        alert('El servicio asociado es obligatorio')
        return
    }

    const boolean = document.querySelectorAll('.boolean')
    const satisfaction = document.querySelectorAll('.satisfaction')

    if (boolean.length === 0 && satisfaction.length === 0) {

        alert('La encuesta debe tener al menos una pregunta')
        return
    }

    for (const input of boolean) {

        if (!registerValidator.largeStringsInput(input, container_msg)) {
            alert('La pregunta ingresada no es válida')
            return
        }
    }
        
    for (const input of satisfaction) {

        if (!registerValidator.largeStringsInput(input, container_msg)) {
            alert('La pregunta ingresada no es válida')
            return
        }
    }

    const inputs = document.querySelectorAll('.boolean, .satisfaction')

    const Json = {
        title: survey_name.value.trim(),
        questions: []

    }

    inputs.forEach(input => {

        if (input.classList.contains('boolean')) {

            Json.questions.push({
                title: input.value.trim(),
                type: 'boolean',
                options: options.boolean
            })
        } else if (input.classList.contains('satisfaction')) {

            Json.questions.push({
                title: input.value.trim(),
                type: 'satisfaction',
                options: options.satisfaction
            })
        }
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
        location.reload()

    } catch (error) {

        alert(error.message || 'Ha ocurrido un error al crear la encuesta, intente nuevamente.')
        return
    }
})