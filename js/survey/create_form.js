const send_btn = document.querySelector("#send_btn")
const survey_name = document.querySelector("#survey_name")
const survey_service = document.querySelector("#survey_service")
const multiple_question = document.querySelector("#multiple_question")
const selection_question = document.querySelector("#selection_question")
const question_container = document.querySelector(".question_container")

const options = {
    1: 'Muy insatisfecho',
    2: 'Insatisfecho',
    3: 'Normal',
    4: 'Satisfecho',
    5: 'Muy satisfecho'
}

function createInput() {
    const input = document.createElement('input')
    input.type = 'text'
    input.placeholder = 'Escriba una pregunta'
    
    return input
}

selection_question.addEventListener('click', () => {

    const input = createInput()
    input.classList.add('select')
    
    const div = document.createElement('div')
    div.append(input)
    question_container.append(div)
})

multiple_question.addEventListener('click', () => {

    const input = createInput()
    input.classList.add('checkbox')
    
    const div = document.createElement('div')
    div.append(input)
    question_container.append(div)
})

send_btn.addEventListener('click', async (e) => {
    e.preventDefault()

    const stringRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9.,_\/\s-]{4,100}$/u

    if (!stringRegex.test(survey_name.value.trim())) {
        alert('El titulo de la encuesta no es válido')
        return
    }

    if (survey_service.value === '') {
        alert('El servicio asociado es obligatorio')
        return
    }

    const select_questions = document.querySelectorAll('.select')
    const multiple_questions = document.querySelectorAll('.checkbox')

    if (select_questions.length === 0 && multiple_questions.length === 0) {
        alert('La encuesta debe tener al menos una pregunta')
        return
    }

    for (const input of select_questions) {
        if (!stringRegex.test(input.value.trim())) {
            alert('La pregunta ingresada no es válida')
            return
        }
    }
        
    for (const input of multiple_questions) {
        if (!stringRegex.test(input.value.trim())) {
            alert('La pregunta ingresada no es válida')
            return
        }
    }

    const inputs = document.querySelectorAll('.select, .checkbox')

    const Json = {
        title: survey_name.value.trim(),
        questions: []

    }

    inputs.forEach(input => {

        if (input.classList.contains('select')) {

            Json.questions.push({
                title: input.value.trim(),
                type: 'select',
                options: options
            })
        } else if (input.classList.contains('checkbox')) {

            Json.questions.push({
                title: input.value.trim(),
                type: 'checkbox',
                options: options
            })
        }
    })

    console.log(Json)
    const form = new FormData()
    form.append('survey_title', survey_name.value.trim())
    form.append('survey', JSON.stringify(Json))
    form.append('id_sevice', survey_service.value)

    try {

        const response = await fetch('/php/actions/save_survey.php', {
            method: 'POST',
            body: form
        })

        const result = await response.json()

        if (!response.ok || !result.succes) {
            alert(result.message || 'Ha ocurrido un error al crear el crer la ecuesta.')
            return
        }

        alert(result.message || 'Ha ocurrido un error al crear el crer la ecuesta.')
        location.reload()

    } catch (error) {

        alert(error.message || 'Ha ocurrido un error al crear el crer la ecuesta.')
        return
    }
})