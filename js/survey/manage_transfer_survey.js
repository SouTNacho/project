import { Counter } from '/js/classes.js'

const form = document.querySelector('#survey_container')
let currentSurvey = null

document.addEventListener('DOMContentLoaded', async () => {
    await loadSurvey()
})

async function loadSurvey() {

    try {

        const response = await fetch('/php/actions/survey/get_survey.php?id=2')
        const result = await response.json()

        if (!response.ok || !result.success) {

            console.error('Error al cargar la encuesta:', result)
            form.textContent = 'Ocurrió un error al cargar la encuesta.'
            return
        }

        form.innerHTML = ''
        currentSurvey = result.item
        createSurvey(result.item, form)

    } catch (error) {

        console.error('Error al cargar la encuesta:', error)
        form.textContent = 'Ocurrió un error al cargar la encuesta.'
    }
}

function createSurvey(survey, item) {

    if (survey === 0 || !survey) {

        const empty_container = document.createElement('div')
        empty_container.classList.add('empty_container')

        const message = document.createElement('p')
        message.textContent = 'Este servicio no cuenta con ninguna encuesta'

        empty_container.append(message)
        item.append(empty_container)
        return
    }

    const survey_form = document.createElement('form')
    survey_form.classList.add('survey-form')
    survey_form.id = 'survey_form'

    const survey_title = document.createElement('h2')
    survey_title.textContent = survey.titulo
    survey_form.append(survey_title)

    const count = new Counter()
    const survey_content = JSON.parse(survey.contenido)

    survey_content.questions.forEach(quest => {

        const div = document.createElement('div')
        const label = document.createElement('label')
        label.textContent = quest.title

        div.append(label)

        if (quest.type === 'boolean' || quest.type === 'satisfaction') {

            const sub_count = new Counter()

            Object.entries(quest.options).forEach(([key, opt]) => {

                const input = document.createElement('input')
                input.type = 'radio'

                if (quest.type === 'boolean') {
                    input.classList.add('boolean')
                }

                if (quest.type === 'satisfaction') {
                    input.classList.add('satisfaction')
                }

                input.id = `quest${count.value}_${sub_count.value}`
                input.name = `quest${count.value}`
                input.value = key
                input.required = true

                const option_label = document.createElement('label')
                option_label.htmlFor = input.id
                option_label.textContent = opt

                sub_count.increment()

                div.append(input)
                div.append(option_label)
            })
        }

        count.increment()
        survey_form.append(div)
    })

    const submit_btn = document.createElement('button')
    submit_btn.id = 'submit_btn'
    submit_btn.type = 'submit'
    submit_btn.textContent = 'Enviar Respuesta'

    survey_form.append(submit_btn)
    item.append(survey_form)

    survey_form.addEventListener('submit', sendSurvey)
}

async function sendSurvey(e) {

    e.preventDefault()

    const survey_form = e.currentTarget
    const submit_btn = survey_form.querySelector('#submit_btn')

    submit_btn.disabled = true

    try {

        const counter = new Counter()

        const response = {
            encuesta_id: currentSurvey.id_encuesta,
            titulo: currentSurvey.titulo,
            respuestas: []
        }

        while (true) {

            const question = survey_form.querySelector(
                `input[name="quest${counter.value}"]`
            )

            if (!question) {
                break
            }

            const selected = survey_form.querySelector(
                `input[name="quest${counter.value}"]:checked`
            )

            if (!selected) {

                alert('Debe responder todas las preguntas antes de enviar el formulario')
                submit_btn.disabled = false
                return
            }

            response.respuestas.push({
                pregunta: selected.name,
                type: selected.classList.contains('boolean') ? 'boolean' : 'satisfaction',
                respuesta: selected.value
            })

            counter.increment()
        }

        const formData = new FormData()
        formData.append('survey_id', response.encuesta_id)
        formData.append('response', JSON.stringify(response))

        const send_response = await fetch('/php/actions/survey/send_survey.php', {
            method: 'POST',
            body: formData
        })

        const send_result = await send_response.json()

        if (!send_response.ok || !send_result.success) {

            console.error('Error al enviar la encuesta:', send_result)
            alert('Ocurrió un error al enviar el formulario, intente nuevamente.')
            submit_btn.disabled = false
            return
        }

        const div = document.createElement('div')
        div.classList.add('message_container')

        const message = document.createElement('p')
        message.textContent = 'Gracias por completar la encuesta.'

        div.append(message)

        form.innerHTML = ''
        form.append(div)

    } catch (error) {

        console.error('Error al enviar la encuesta:', error)
        alert('Ocurrió un error al enviar el formulario, intente nuevamente.')
        submit_btn.disabled = false
    }
}
