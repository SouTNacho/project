import formTools from "/js/library.js"

const { registerValidator } = formTools

const view_container = document.querySelector('#view')
const register_btn = document.querySelector('#register')
const filter_all = document.querySelector('#filter_all')
const filter_search = document.querySelector('#filter_search')
const filter_active = document.querySelector('#filter_active')
const search_btn = document.querySelector('#search')
const filter_inactive = document.querySelector('#filter_inactive')
const filter_deleted = document.querySelector('#filter_deleted')
const password_dialog = document.querySelector('#change-password-dialog')

const positions = {
    FA: 'Administrativo',
    DR: 'Conductor',
    CO: 'Copiloto'
}

let employee_states = []

function getSelectedState() {
    if (filter_all.checked) return 0
    if (filter_active.checked) return 1
    if (filter_inactive.checked) return 2
    if (filter_deleted.checked) return 3
    return 0
}

function escapeHtml(value) {
    const div = document.createElement('div')
    div.textContent = value ?? ''
    return div.innerHTML
}

function getEmployeeCode(employee) {
    return `${employee.cargo}${String(employee.id_funcionario).padStart(8, '0')}`
}

function getStateName(stateId) {
    const state = employee_states.find(
        item => Number(item.id_estado_funcionario) === Number(stateId)
    )

    return state ? state.nombre : 'Desconocido'
}

function getStateOptions(currentStateId) {
    return employee_states
        .filter(state => Number(state.id_estado_funcionario) !== Number(currentStateId))
        .map(state => `
            <option value="${escapeHtml(state.id_estado_funcionario)}">
                ${escapeHtml(state.nombre)}
            </option>
        `)
        .join('')
}

async function loadEmployeeStates() {
    const response = await fetch(
        '/php/actions/employee/get_employees_states.php',
        { cache: 'no-store' }
    )

    const result = await response.json()

    if (!response.ok || !result.success) {
        throw new Error(result.message || 'No se pudieron cargar los estados.')
    }

    employee_states = result.item || []
}

function showNoResults() {
    view_container.innerHTML = `
        <div class="no-results">
            <p>No se encontraron funcionarios.</p>
        </div>
    `
}

async function changeEmployeeState(employeeId) {
    const select = document.querySelector(
        `.change-state-select[data-id="${employeeId}"]`
    )

    if (!select) {
        alert('No se pudo encontrar el estado seleccionado.')
        return
    }

    if (!confirm('¿Está seguro de modificar el estado del funcionario?')) {
        return
    }

    const form = new FormData()
    form.append('employee_id', employeeId)
    form.append('state_id', select.value)

    const response = await fetch(
        '/php/actions/employee/employee_change_state.php',
        {
            method: 'POST',
            body: form
        }
    )

    const result = await response.json()

    if (!response.ok || !result.success) {
        throw new Error(
            result.message ||
            'Error al modificar el estado del funcionario.'
        )
    }

    alert(result.message || 'Estado modificado correctamente.')

    await searchFilterEmployees(
        getSelectedState(),
        filter_search.value.trim()
    )
}

function openPasswordDialog(employeeId) {
    password_dialog.innerHTML = `
        <form id="change_password_form">
            <div>
                <h2>Cambiar contraseña</h2>
            </div>

            <div>
                <label for="input_password">Nueva contraseña:</label>
                <input
                    type="password"
                    name="password"
                    id="input_password"
                    autocomplete="new-password"
                >
                <span id="input_password_msg"></span>
            </div>

            <div>
                <label for="confirm_password">Confirmar contraseña:</label>
                <input
                    type="password"
                    name="confirm_password"
                    id="confirm_password"
                    autocomplete="new-password"
                >
                <span id="confirm_password_msg"></span>
            </div>

            <div>
                <button type="button" id="confirm_btn">Cambiar</button>
                <button type="button" id="cancel_btn">Cancelar</button>
            </div>
        </form>
    `

    password_dialog.showModal()

    const form = password_dialog.querySelector('#change_password_form')
    const input_password = password_dialog.querySelector('#input_password')
    const confirm_password = password_dialog.querySelector('#confirm_password')
    const input_password_msg = password_dialog.querySelector('#input_password_msg')
    const confirm_password_msg = password_dialog.querySelector('#confirm_password_msg')
    const cancel_button = password_dialog.querySelector('#cancel_btn')
    const confirm_button = password_dialog.querySelector('#confirm_btn')

    cancel_button.addEventListener('click', () => password_dialog.close())

    input_password.addEventListener('input', () =>
        registerValidator.passwordInput(input_password, input_password_msg)
    )

    confirm_password.addEventListener('input', () =>
        registerValidator.passwordMatch(
            confirm_password,
            confirm_password_msg,
            input_password
        )
    )

    confirm_button.addEventListener('click', async () => {
        if (
            !registerValidator.passwordInput(input_password, input_password_msg) ||
            !registerValidator.passwordMatch(
                confirm_password,
                confirm_password_msg,
                input_password
            )
        ) {
            alert('Los datos ingresados no son válidos.')
            return
        }

        try {
            const formData = new FormData(form)
            formData.append('employee_id', employeeId)

            const response = await fetch(
                '/php/actions/employee/employee_change_password.php',
                {
                    method: 'POST',
                    body: formData
                }
            )

            const result = await response.json()

            if (!response.ok || !result.success) {
                alert(
                    result.message ||
                    'Error al cambiar la contraseña del funcionario.'
                )
                return
            }

            alert(
                result.message ||
                'La contraseña del funcionario se cambió correctamente.'
            )

            password_dialog.close()

        } catch (error) {
            console.error(error)
            alert('Error al cambiar la contraseña del funcionario.')
        }
    })
}

async function loadData(container, employees) {
    container.innerHTML = ''

    employees.forEach(employee => {
        const stateId = Number(employee.id_estado_funcionario)
        const employeeId = Number(employee.id_funcionario)
        const position = positions[employee.cargo] ?? employee.cargo
        const employeeCode = getEmployeeCode(employee)
        const fullName = `${employee.nombre ?? ''} ${employee.apellido ?? ''}`.trim()

        container.innerHTML += `
            <li>
                <p>${escapeHtml(fullName)}</p>

                <p>${escapeHtml(position)}</p>

                <p>Código: ${escapeHtml(employeeCode)}</p>

                <p>
                    Fecha de Ingreso:
                    ${escapeHtml(employee.fecha_ingreso ?? '')}
                </p>

                <p>
                    Estado:
                    ${escapeHtml(getStateName(stateId))}
                </p>

                <div class="edit-state-container">
                    <label>
                        Cambiar Estado
                        <select
                            class="change-state-select"
                            data-id="${employeeId}"
                        >
                            ${getStateOptions(stateId)}
                        </select>
                    </label>

                    <button
                        type="button"
                        class="confirm-btn"
                        data-id="${employeeId}"
                        title="Confirmar cambio de estado"
                        aria-label="Confirmar cambio de estado"
                    >
                        <i data-lucide="circle-check"></i>
                    </button>

                    <button
                        type="button"
                        class="change-password-button"
                        data-id="${employeeId}"
                        title="Cambiar contraseña"
                        aria-label="Cambiar contraseña"
                    >
                        <i data-lucide="key-round"></i>
                    </button>
                </div>
            </li>
        `
    })

    lucide.createIcons()

    document.querySelectorAll('.confirm-btn').forEach(button => {
        button.addEventListener('click', async () => {
            try {
                await changeEmployeeState(button.dataset.id)
            } catch (error) {
                console.error(error)
                alert(error.message || 'Error al cambiar el estado.')
            }
        })
    })

    document.querySelectorAll('.change-password-button').forEach(button => {
        button.addEventListener('click', () => {
            openPasswordDialog(button.dataset.id)
        })
    })
}

async function searchFilterEmployees(stateId, phrase = '') {
    try {
        const params = new URLSearchParams({
            id_state: String(stateId),
            phrase: phrase || 'null'
        })

        const response = await fetch(
            `/php/actions/employee/get_employees.php?${params.toString()}`,
            { cache: 'no-store' }
        )

        const result = await response.json()

        if (!response.ok || !result.success) {
            throw new Error(
                result.message ||
                'Error al obtener los funcionarios.'
            )
        }

        if (!result.item || result.item.length === 0) {
            showNoResults()
            return
        }

        await loadData(view_container, result.item)

    } catch (error) {
        console.error(error)
        view_container.innerHTML = `
            <div class="no-results">
                <p>No se pudieron cargar los funcionarios.</p>
            </div>
        `
    }
}

register_btn.addEventListener('click', () => {
    location.href = '/php/pages/employee/register.php'
})

filter_all.addEventListener('change', () => {
    if (filter_all.checked) {
        searchFilterEmployees(0, filter_search.value.trim())
    }
})

filter_active.addEventListener('change', () => {
    if (filter_active.checked) {
        searchFilterEmployees(1, filter_search.value.trim())
    }
})

filter_inactive.addEventListener('change', () => {
    if (filter_inactive.checked) {
        searchFilterEmployees(2, filter_search.value.trim())
    }
})

filter_deleted.addEventListener('change', () => {
    if (filter_deleted.checked) {
        searchFilterEmployees(3, filter_search.value.trim())
    }
})

search_btn.addEventListener('click', () => {
    searchFilterEmployees(
        getSelectedState(),
        filter_search.value.trim()
    )
})

filter_search.addEventListener('keydown', event => {
    if (event.key === 'Enter') {
        searchFilterEmployees(
            getSelectedState(),
            filter_search.value.trim()
        )
    }
})

document.addEventListener('DOMContentLoaded', async () => {
    try {
        await loadEmployeeStates()
        await searchFilterEmployees(0, '')
    } catch (error) {
        console.error(error)
        view_container.innerHTML = `
            <div class="no-results">
                <p>No se pudieron cargar los estados de los funcionarios.</p>
            </div>
        `
    }
})

lucide.createIcons()
