import { Counter } from '/js/classes.js'

const download_btn = document.querySelector("#download_btn")
const form_button = document.querySelector("#form_button")
const form = document.querySelector('.form')

const token = new URLSearchParams(window.location.search).get("token")

download_btn.addEventListener('click', () => {

    if (!token) {
        console.error("No se encontró el token")
        return
    }

    location.href = `/php/actions/document/download_document.php?token=${token}`

    const div = document.createElement('div')
    div.classList.add('message_container')
    
    const message = document.createElement('p')
    message.textContent = 'Greacias por descargar el documento, si desea puede completar la encuesta que se encuentra a continuación'
    div.append(message)
    form.append(div)
})

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

    const form = document.createElement('form')
    form.classList.add('survey-form')
    form.id = 'survey_form'

    const survey_title = document.createElement('h2')
    survey_title.textContent = survey.titulo
    form.append(survey_title)

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

                if (quest.type === 'boolean') input.classList.add('boolean')
                if (quest.type === 'satisfaction') input.classList.add('satisfaction')

                input.id = `quest${count.value}_${sub_count.value}`
                input.name = `quest${count.value}`
                input.value = key

                const option_label = document.createElement('label')
                option_label.htmlFor = input.id
                option_label.textContent = opt

                sub_count.increment()

                div.append(input)
                div.append(option_label)

            })

        }

        count.increment()
        form.append(div)
    })

    const submit_btn = document.createElement('button')
    submit_btn.id = 'submit_btn'

    submit_btn.type = 'submit'
    submit_btn.textContent = 'Enviar Respuesta'

    form.append(submit_btn)
    item.append(form)

}

form_button.addEventListener('click', async () => {

    try {
        
        const response = await fetch('/php/actions/survey/get_survey.php')

        const result = await response.json()

        if (!response.ok || !result.succes) {

            alert('Ocurrio un error al cargar el formulario, intente nuevamente')
            return
        }

        form.innerHTML = ''
        createSurvey(result.item, form)
        const send_btn = document.querySelector('#submit_btn')

        send_btn.addEventListener('click', async (e) => {
            e.preventDefault()

            const counter = new Counter()

            const response = {

                encuesta_id: result.item.id_encuesta,
                titulo: result.item.titulo,
                respuestas: []
            }

            while (true) {

                const question = document.querySelector(
                    `input[name="quest${counter.value}"]`
                )

                if (!question) {
                    break
                }

                const selected = document.querySelector(
                    `input[name="quest${counter.value}"]:checked`
                )

                if (!selected) {
                    alert('Debe responder todas las preguntas antes de enviar el formulario')
                    return
                }

                response.respuestas.push({
                    pregunta: selected.name,
                    type: selected.classList.contains('boolean') ? 'boolean' : 'satisfaction',
                    respuesta: selected.value
                })

                counter.increment()
            }

            console.log(response)

            const formData = new FormData()

            formData.append('survey_id', result.item.id_encuesta)
            formData.append('response', JSON.stringify(response))

            const send_response = await fetch('/php/actions/survey/send_survey.php', {
                method: 'POST',
                body: formData
            })

            const send_result = await send_response.json()

            if (!send_response.ok || !send_result.succes) {

                alert('Ocurrio un error al enviar el formulario, intente nuevamente')
                return
            }

            const div = document.createElement('div')
            div.classList.add('message_container')
            
            const message = document.createElement('p')
            message.textContent = 'Gracias por completar la encuesta.'

            form.innerHTML = ''
            div.append(message)
            form.append(div)

            alert('Gracias por enviar su respuesta, la descarga comenzara automaticamente')

            location.href = `/php/actions/document/download_document.php?token=${token}`
            
        })
        
    } catch (error) {

        console.error('Error:', error)
        alert('Ocurrio un error al cargar el formulario, intente nuevamente')
        return
    }
})