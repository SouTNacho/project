const general_container = document.querySelector('#general_container')

const createPreview = (survey) => {
    const preview = document.createElement('div')
    preview.className = 'managed-survey-preview'

    if (!survey) {
        const empty = document.createElement('div')
        empty.className = 'managed-empty'
        empty.textContent = 'Este servicio no tiene una encuesta activa.'
        preview.append(empty)
        return preview
    }

    const content = JSON.parse(survey.contenido)

    const title = document.createElement('div')
    title.className = 'managed-preview-title'
    title.textContent = survey.titulo
    preview.append(title)

    content.questions.forEach((question, index) => {
        const questionBox = document.createElement('div')
        questionBox.className = 'managed-question'

        const questionTitle = document.createElement('h3')
        questionTitle.textContent = `${index + 1}. ${question.title}`
        questionBox.append(questionTitle)

        const options = document.createElement('div')
        options.className = 'managed-options'

        Object.values(question.options).forEach(optionText => {
            const option = document.createElement('span')
            option.className = 'managed-option'
            option.textContent = optionText
            options.append(option)
        })

        questionBox.append(options)
        preview.append(questionBox)
    })

    return preview
}

const showError = message => {
    general_container.innerHTML = ''
    const error = document.createElement('div')
    error.className = 'survey-error'
    error.textContent = message
    general_container.append(error)
}

async function getServiceSurveys() {
    try {
        const servicesResponse = await fetch('/php/actions/survey/get_services.php')
        const servicesResult = await servicesResponse.json()

        if (!servicesResponse.ok || !servicesResult.succes) {
            showError('Ha ocurrido un error al cargar los servicios.')
            return
        }

        const surveysResponse = await fetch('/php/actions/survey/get_surveys.php')
        const surveysResult = await surveysResponse.json()

        if (!surveysResponse.ok || !surveysResult.succes) {
            showError('Ha ocurrido un error al cargar las encuestas.')
            return
        }

        general_container.innerHTML = ''

        servicesResult.item.forEach(service => {
            const surveys = surveysResult.item.filter(
                survey => Number(survey.id_servicio) === Number(service.id_servicio)
            )

            const activeSurvey = surveys.find(
                survey => Number(survey.id_estado_encuesta) === 1
            )

            const serviceCard = document.createElement('article')
            serviceCard.className = 'service-survey-card'

            const heading = document.createElement('div')
            heading.className = 'service-survey-heading'

            const icon = document.createElement('span')
            icon.className = 'service-icon'
            icon.textContent = service.nombre.toLowerCase().includes('tras') ? '🚑' : '📄'

            const headingText = document.createElement('div')
            const serviceTitle = document.createElement('h2')
            serviceTitle.textContent = service.nombre
            const serviceSubtitle = document.createElement('p')
            serviceSubtitle.textContent = activeSurvey
                ? 'Encuesta activa para este servicio'
                : 'No hay una encuesta activa actualmente'
            headingText.append(serviceTitle, serviceSubtitle)
            heading.append(icon, headingText)

            const controls = document.createElement('div')
            controls.className = 'survey-controls'

            const label = document.createElement('label')
            label.textContent = 'Encuesta activa'

            const select = document.createElement('select')
            select.dataset.idService = service.id_servicio

            if (activeSurvey) {
                const activeOption = document.createElement('option')
                activeOption.value = activeSurvey.id_encuesta
                activeOption.textContent = activeSurvey.titulo
                select.append(activeOption)
            } else {
                const noneOption = document.createElement('option')
                noneOption.value = '0'
                noneOption.textContent = 'Sin encuesta activa'
                select.append(noneOption)
            }

            surveys
                .filter(survey => Number(survey.id_estado_encuesta) !== 1)
                .forEach(survey => {
                    const option = document.createElement('option')
                    option.value = survey.id_encuesta
                    option.textContent = survey.titulo
                    select.append(option)
                })

            const button = document.createElement('button')
            button.type = 'button'
            button.className = 'change-survey-button'
            button.textContent = 'Activar encuesta'

            controls.append(label, select, button)

            const preview = document.createElement('div')
            preview.className = 'managed-preview-wrapper'
            preview.append(createPreview(activeSurvey))

            select.addEventListener('change', () => {
                const selectedSurvey = surveys.find(
                    survey => Number(survey.id_encuesta) === Number(select.value)
                )
                preview.innerHTML = ''
                preview.append(createPreview(selectedSurvey))
            })

            button.addEventListener('click', async () => {
                if (select.value === '0') {
                    alert('Seleccione una encuesta válida.')
                    return
                }

                if (!confirm('¿Está seguro de activar esta encuesta para el servicio?')) {
                    return
                }

                const data = new FormData()
                data.append('service_id', service.id_servicio)
                data.append('survey_id', select.value)

                try {
                    const response = await fetch('/php/actions/survey/change_active_survey.php', {
                        method: 'POST',
                        body: data
                    })

                    const result = await response.json()

                    if (!response.ok || !result.succes) {
                        alert(result.message || 'Ha ocurrido un error al activar la encuesta.')
                        return
                    }

                    alert(result.message || 'La encuesta ha sido activada correctamente.')
                    location.reload()
                } catch (error) {
                    alert(error.message || 'Ha ocurrido un error al activar la encuesta.')
                }
            })

            serviceCard.append(heading, controls, preview)
            general_container.append(serviceCard)
        })
    } catch (error) {
        showError(error.message || 'Ha ocurrido un error al cargar las encuestas.')
    }
}

getServiceSurveys()
