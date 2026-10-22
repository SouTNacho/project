import formTools from "/js/library.js"
const { registerValidator } = formTools

const service_chose = document.querySelector('#service_chose')
const service_chose_msg = document.querySelector('#service_chose_msg')

const satisfaction_chart = document.querySelector("#satisfaction_chart")
const surveys_results = document.querySelector('#surveys_results')
const boolean_chart = document.querySelector("#boolean_chart")

const satisfaction_chart_container = satisfaction_chart.closest('.chart-container')
const boolean_chart_container = boolean_chart.closest('.chart-container')
let satisfaction_chart_instance = null
let boolean_chart_instance = null

function showSurveyMessage(message) {
    
    satisfaction_chart_instance?.destroy()
    satisfaction_chart_instance = null
    boolean_chart_instance?.destroy()
    boolean_chart_instance = null
    surveys_results.replaceChildren()

    const message_container = document.createElement('div')
    message_container.classList.add('survey-message')
    message_container.textContent = message

    surveys_results.appendChild(message_container)
}

function createSatisfactionChart(chart, counts, step_size) {

    return new Chart(chart, {
        type: "bar",

        data: {
            labels: [
                'Muy insat.',
                'Insat.',
                'Neutral',
                'Satisfecho',
                'Muy satis.'
            ],

            datasets: [{
                label: 'Cantidad de respuestas',
                data: counts,

                backgroundColor: [
                    '#dc2626',
                    '#f97316',
                    '#eab308',
                    '#84cc16',
                    '#16a34a'
                ],

                borderRadius: 5,
                borderSkipped: false
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        stepSize: step_size
                    },

                    title: {
                        display: true,
                        text: 'Cantidad de respuestas'
                    }
                },

                x: {
                    title: {
                        display: true,
                        text: 'Nivel de satisfacción'
                    }
                }
            },

            plugins: {
                legend: {
                    display: false
                },

                title: {
                    display: true,
                    text: 'Nivel de satisfacción'
                }
            }
        }
    })
}

function createBooleanChart(chart, counts, step_size) {

    return new Chart(chart, {
        type: "bar",

        data: {
            labels: ['Sí', 'No'],

            datasets: [{
                label: 'Cantidad de respuestas',
                data: counts,

                backgroundColor: [
                    '#16a34a',
                    '#dc2626'
                ],

                borderRadius: 5,
                borderSkipped: false
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        stepSize: step_size
                    },

                    title: {
                        display: true,
                        text: 'Cantidad de respuestas'
                    }
                },

                x: {
                    title: {
                        display: true,
                        text: 'Respuesta'
                    }
                }
            },

            plugins: {
                legend: {
                    display: false
                },

                title: {
                    display: true,
                    text: 'Respuestas de aprobación'
                }
            }
        }
    })
}

function calculateChartScale(counts) {

    const max_count = Math.max(...counts)

    if (max_count <= 10) return 1
    if (max_count <= 20) return 2
    if (max_count <= 50) return 5
    if (max_count <= 100) return 10

    return Math.ceil(max_count / 10 / 5) * 5
}

service_chose.addEventListener('change', async () => {

    if (!registerValidator.selectsInput(service_chose, service_chose_msg)) {

        showSurveyMessage('Seleccione un servicio válido.')
        console.error('El servicio seleccionado no es valido.')
        return
    }

    try {

        const surveys = await fetch(`/php/actions/survey/get_survey_service.php?id=${Number(service_chose.value.trim())}`)
        const surveys_result = await surveys.json()

        if (!surveys.ok || !surveys_result.success || !Array.isArray(surveys_result.item) || surveys_result.item.length === 0) {
            
            showSurveyMessage('El servicio seleccionado no tiene encuestas con respuestas.')
            return
        }

        const satisfaction_counts = [0, 0, 0, 0, 0]
        const boolean_counts = [0, 0]

        for (const survey of surveys_result.item) {
            const survey_id = survey.id_encuesta
            
            const survey_responses = await fetch(`/php/actions/survey/get_survey_responses.php?id=${survey_id}`)
            const responses_result = await survey_responses.json()

            if (!survey_responses.ok || !responses_result.success || !Array.isArray(responses_result.item) || responses_result.item.length === 0) {
                console.log(`La encuesta con id:${survey_id}, no tiene respuestas.`)
                continue
            }

            responses_result.item.forEach(response => {
                const responseArray = JSON.parse(response.respuestas)

                if (!Array.isArray(responseArray.respuestas)) {
                    console.error('Formato de respuestas inválido.')
                    return
                }
                
                responseArray.respuestas.forEach(answer => {

                    const answer_value = Number(answer.respuesta)

                    if (answer.type === 'satisfaction' && Number.isInteger(answer_value) &&
                        answer_value >= 1 && answer_value <= 5) {

                        satisfaction_counts[answer_value - 1]++
                    } else if (answer.type === 'boolean') {

                        if (answer_value === 1) {

                            boolean_counts[0]++
                        } else if (answer_value === 2) {

                            boolean_counts[1]++
                        }
                    }
                })
            })
        }

        const has_satisfaction = satisfaction_counts.some(count => count > 0)
        const has_boolean = boolean_counts.some(count => count > 0)

        if (!has_satisfaction && !has_boolean) {
            showSurveyMessage('No hay respuestas disponibles para mostrar.')
            return
        }

        satisfaction_chart_instance?.destroy()
        satisfaction_chart_instance = null

        boolean_chart_instance?.destroy()
        boolean_chart_instance = null

        surveys_results.replaceChildren()

        if (has_satisfaction) {
            surveys_results.appendChild(satisfaction_chart_container)
        }

        if (has_boolean) {
            surveys_results.appendChild(boolean_chart_container)
        }

        if (has_satisfaction) {

            const satisfaction_scale = calculateChartScale(satisfaction_counts)

            satisfaction_chart_instance = createSatisfactionChart(
                satisfaction_chart,
                satisfaction_counts,
                satisfaction_scale
            )
        }

        if (has_boolean) {

            const boolean_scale = calculateChartScale(boolean_counts)

            boolean_chart_instance = createBooleanChart(
                boolean_chart,
                boolean_counts,
                boolean_scale
            )
        }

    } catch (error) {
        
        console.error('Error al obtener los resultados:', error)
        showSurveyMessage('Ocurrió un error al cargar los resultados. Intente nuevamente.')
    }
})

lucide.createIcons()