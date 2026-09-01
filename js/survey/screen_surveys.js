import { Counter } from './classes.js'

const general_container = document.querySelector(".general-container")

function createSurvey(survey, item) {

    if (survey === 0) {

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
        label.htmlFor = `quest${count.value}`

        div.append(label)

        if (quest.type === "select") {

            const input = document.createElement('select')
            input.id = `quest${count.value}`

            Object.entries(quest.options).forEach(([key, opt]) => {

                const op = document.createElement('option')
                op.textContent = opt
                op.value = key
                
                input.append(op)
                div.append(input)
            })

        } else if (quest.type === 'checkbox') {

            const sub_count = new Counter()

            Object.entries(quest.options).forEach(([key, opt]) => {

                const input = document.createElement('input')
                input.type = 'radio'

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

    item.append(form)

}

async function getServiceSurveys() {
    
    try {

        const response = await fetch("/php/actions/get_services.php")

        const result = await response.json()

        if (!response.ok || !result.succes) {

            const div = document.createElement('div')
            div.classList.add('error-message-container')

            const p = document.createElement('p')
            p.textContent = "Ha ocurrido un error al cargar las encuestas, comuniquese con el administrador."

            div.append(p)
            general_container.append(div)

            console.error("error 1")
            return
        }

        const response_sur = await fetch('/php/actions/get_surveys.php')

        const result_sur = await response_sur.json()

        if (!response_sur.ok || !result_sur.succes) {

            const div = document.createElement('div')
            div.classList.add('error-message-container')

            const p = document.createElement('p')
            p.textContent = "Ha ocurrido un error al cargar las encuestas, comuniquese con el administrador."

            div.append(p)
            general_container.append(div)

            console.error("error 2")
            return
        }

        result.item.forEach(service => {

            const service_container = document.createElement('div')
            service_container.classList.add('service-container')

            const name_container = document.createElement('div')
            name_container.classList.add('name-container')

            const service_name = document.createElement('h2')
            service_name.classList.add('service-name')

            service_name.textContent = service.nombre
            name_container.append(service_name)

            service_container.append(name_container)

            const survey_info = document.createElement('div')
            survey_info.classList.add('survey-info')

            const info_name = document.createElement('p')
            info_name.textContent = 'Encuesta Activa: '

            const info_select = document.createElement('select')
            info_select.dataset.idService = service.id_servicio

            const active_survey = result_sur.item.find(
                i =>
                    Number(i.id_servicio) === Number(service.id_servicio) &&
                    Number(i.id_estado_encuesta) === 1
            )

            const survey_preview = document.createElement('div')
            survey_preview.classList.add('survey-preview')

            if (active_survey) {

                const option = document.createElement('option')
                option.textContent = active_survey.titulo
                option.value = active_survey.id_encuesta

                info_select.append(option)
                createSurvey(active_survey, survey_preview)
            } else {

                const option = document.createElement('option')
                option.textContent = 'Sin encuesta activa'
                option.value = 0

                info_select.append(option)

                createSurvey(0, survey_preview)
            }

            result_sur.item.forEach(survey => {

                if (Number(survey.id_servicio) === Number(service.id_servicio) &&
                        Number(survey.id_estado_encuesta) !== 1) {

                    const opt = document.createElement('option')
                    opt.textContent = survey.titulo
                    opt.value = survey.id_encuesta

                    info_select.append(opt)
                }

            })

            const info_btn = document.createElement('button')
            info_btn.dataset.idService = service.id_servicio
            info_btn.textContent = 'Confirmar Cambio'

            info_select.addEventListener('change', () => {

                survey_preview.innerHTML = ''
                const survey_id = info_select.value
                const survey = result_sur.item.find(i => Number(i.id_encuesta) === Number(survey_id))

                createSurvey(survey, survey_preview)
            })

            info_btn.addEventListener('click', async (e) => {
                e.preventDefault()

                const service_id = info_btn.dataset.idService
                const selected = document.querySelector(`select[data-id-service="${service_id}"]`)

                if (selected.value == 0) {

                    alert('La opción seleccionada no es válida')
                    location.reload()
                    return
                }

                const data = new FormData()
                data.append('service_id', service_id)
                data.append('survey_id', selected.value)
                
                try {

                    const response = await fetch('/php/actions/change_active_survey.php', {
                        method: 'POST',
                        body: data
                    })

                    const result = await response.json()

                    if (!response.ok || !result.succes) {

                        alert(result.message || 'Ha ocurrido un error al activar la encuesta, intente nuevamente')
                        return
                    }

                    alert(result.message || 'La encuesta ha sido activada para el servicio')
                    location.reload()
                    return

                } catch (error) {

                    alert(error.message || 'Ha ocurrido un error al activar la encuesta')
                    return
                }
            })

            survey_info.append(info_name)
            survey_info.append(info_select)
            survey_info.append(info_btn)
            service_container.append(survey_info)
            service_container.append(survey_preview)
            general_container.append(service_container)

        })

    } catch(error) {

        alert(error.message)
        return
    }

}

getServiceSurveys()
