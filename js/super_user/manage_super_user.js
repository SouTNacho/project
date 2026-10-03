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

let super_user_states = []

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

function getStateName(stateId) {
    const state = super_user_states.find(
        item => Number(item.id_estado_super_usuario) === Number(stateId)
    )

    return state ? state.nombre : 'Desconocido'
}

function getStateOptions(currentStateId) {
    return super_user_states
        .filter(
            state =>
                Number(state.id_estado_super_usuario) !==
                Number(currentStateId)
        )
        .map(state => `
            <option value="${escapeHtml(state.id_estado_super_usuario)}">
                ${escapeHtml(state.nombre)}
            </option>
        `)
        .join('')
}

async function loadSuperUserStates() {
    const response = await fetch(
        '/php/actions/super_user/get_super_users_states.php',
        { cache: 'no-store' }
    )

    const result = await response.json()

    if (!response.ok || !result.success) {
        throw new Error(
            result.message || 'No se pudieron cargar los estados.'
        )
    }

    super_user_states = result.item || []
}

function showNoResults() {
    view_container.innerHTML = `
        <div class="no-results">
            <p>No se encontraron administradores.</p>
        </div>
    `
}

async function changeSuperUserState(superUserId) {

    const select = document.querySelector(
        `.change-state-select[data-id="${superUserId}"]`
    )

    if (!select) {
        alert('No se pudo encontrar el estado seleccionado.')
        return
    }

    if (!confirm('¿Está seguro de modificar el estado del administrador?')) {
        return
    }

    const form = new FormData()

    form.append('super_user_id', superUserId)
    form.append('state_id', select.value)

    const response = await fetch(
        '/php/actions/super_user/super_user_change.php',
        {
            method: 'POST',
            body: form
        }
    )

    const result = await response.json()

    if (!response.ok || !result.success) {
        throw new Error(
            result.message ||
            'Error al modificar el estado del administrador.'
        )
    }

    alert(result.message || 'Estado modificado correctamente.')

    await searchFilterSuperUsers(
        getSelectedState(),
        filter_search.value.trim()
    )
}

async function loadData(container, super_users) {

    container.innerHTML = ''

    super_users.forEach(super_user => {

        const stateId = Number(
            super_user.id_estado_super_usuario
        )

        const superUserId = Number(
            super_user.id_super_usuario
        )

        container.innerHTML += `
            <li>

                <p>${escapeHtml(super_user.nombre)}</p>

                <p>${escapeHtml(super_user.permisos)}</p>

                <p>
                    Estado:
                    ${escapeHtml(getStateName(stateId))}
                </p>

                ${
                    stateId !== 3
                    ?
                    `
                    <div class="edit-state-container">

                        <label>
                            Cambiar Estado

                            <select
                                class="change-state-select"
                                data-id="${superUserId}"
                            >
                                ${getStateOptions(stateId)}
                            </select>
                        </label>

                        <button
                            type="button"
                            class="confirm-btn"
                            data-id="${superUserId}"
                            title="Confirmar cambio de estado"
                            aria-label="Confirmar cambio de estado"
                        >
                            <i data-lucide="circle-check"></i>
                        </button>

                    </div>
                    `
                    :
                    ''
                }

            </li>
        `
    })

    lucide.createIcons()

    document.querySelectorAll('.confirm-btn').forEach(button => {

        button.addEventListener('click', async () => {

            try {

                await changeSuperUserState(
                    button.dataset.id
                )

            } catch (error) {

                console.error(error)

                alert(
                    error.message ||
                    'Error al cambiar el estado.'
                )
            }
        })
    })
}

async function searchFilterSuperUsers(stateId, phrase = '') {

    try {

        const params = new URLSearchParams({
            id_state: String(stateId),
            phrase: phrase || 'null'
        })

        const response = await fetch(
            `/php/actions/super_user/get_super_users.php?${params.toString()}`,
            { cache: 'no-store' }
        )

        const result = await response.json()

        if (!response.ok || !result.success) {
            throw new Error(
                result.message ||
                'Error al obtener los administradores.'
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
                <p>No se pudieron cargar los administradores.</p>
            </div>
        `
    }
}

register_btn.addEventListener('click', () => {
    location.href =
        '/php/pages/super_user/super_user_register.php'
})

filter_all.addEventListener('change', () => {

    if (filter_all.checked) {
        searchFilterSuperUsers(
            0,
            filter_search.value.trim()
        )
    }
})

filter_active.addEventListener('change', () => {

    if (filter_active.checked) {
        searchFilterSuperUsers(
            1,
            filter_search.value.trim()
        )
    }
})

filter_inactive.addEventListener('change', () => {

    if (filter_inactive.checked) {
        searchFilterSuperUsers(
            2,
            filter_search.value.trim()
        )
    }
})

filter_deleted.addEventListener('change', () => {

    if (filter_deleted.checked) {
        searchFilterSuperUsers(
            3,
            filter_search.value.trim()
        )
    }
})

search_btn.addEventListener('click', () => {

    searchFilterSuperUsers(
        getSelectedState(),
        filter_search.value.trim()
    )
})

filter_search.addEventListener('keydown', event => {

    if (event.key === 'Enter') {

        searchFilterSuperUsers(
            getSelectedState(),
            filter_search.value.trim()
        )
    }
})

document.addEventListener('DOMContentLoaded', async () => {

    try {

        await loadSuperUserStates()

        await searchFilterSuperUsers(0, '')

    } catch (error) {

        console.error(error)

        view_container.innerHTML = `
            <div class="no-results">
                <p>No se pudieron cargar los estados de los administradores.</p>
            </div>
        `
    }
})

lucide.createIcons()