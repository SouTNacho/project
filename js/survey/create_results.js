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

service_chose.addEventListener('change', async () => {

    if (!registerValidator.selectsInput(service_chose, service_chose_msg)) {

        console.error('El servicio seleccionado no es valido.')
        return
    }

    try {

        const surveys = await fetch(`/php/actions/survey/get_survey_service.php?id=${Number(service_chose.value.trim())}`)
        const surveys_result = await surveys.json()

        if (!surveys.ok || !surveys_result.success ||
            !Array.isArray(surveys_result.item) || surveys_result.item.length === 0) {
            
            showSurveyMessage('El servicio seleccionado no tiene encuestas con respuestas.')
            return
        }

        const satisfaction_counts = [0, 0, 0, 0, 0]
        const boolean_counts = [0, 0]

        for (const survey of surveys_result.item) {

            const survey_id = survey.id_respuesta_encuesta
            
            const survey_responses = await fetch(`/php/actions/survey/get_survey_responses.php?id=${survey_id}`)
            const responses_result = await survey_responses.json()

            if (!survey_responses.ok || !responses_result.success ||
                !responses_result.item || !Array.isArray(responses_result.item.respuestas)) {
                
                console.log(`La encuesta con id:${survey_id}, no tiene respuestas.`)
                continue
            }

            responses_result.item.respuestas.forEach(response => {
                const answer = Number(response.respuesta)

                if (response.type === 'satisfaction' && answer >= 1 && answer <= 5) {
                    satisfaction_counts[answer - 1]++

                } else if (response.type === 'boolean') {

                    if (answer === 1) {
                        boolean_counts[0]++
                    } 
                    else if (answer === 2) {
                        boolean_counts[1]++
                    }
                }
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

            satisfaction_chart_instance = new Chart(satisfaction_chart, {
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
                        data: satisfaction_counts,

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

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
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
                            text: 'Distribución de satisfacción'
                        }
                    }
                }
            })
        }

        if (has_boolean) {

            boolean_chart_instance = new Chart(boolean_chart, {
                type: "bar",

                data: {
                    labels: ['Sí', 'No'],

                    datasets: [{
                        label: 'Cantidad de respuestas',
                        data: boolean_counts,

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

                    scales: {
                        y: {
                            beginAtZero: true,

                            ticks: {
                                stepSize: 1
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
    } catch (error) {
        
        console.error('Error al obtener los resultados:', error)
        showSurveyMessage('Ocurrió un error al cargar los resultados. Intente nuevamente.')
    }
})