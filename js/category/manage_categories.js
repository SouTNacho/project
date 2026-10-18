const categoryTable = document.querySelector('#category-table')
const categoryList = document.querySelector('#category-list')
const emptyMessage = document.querySelector('#category-empty')

function escapeHtml(value) {
    const element = document.createElement('div')
    element.textContent = value ?? ''
    return element.innerHTML
}

function showEmptyMessage(message) {
    categoryTable.hidden = true
    emptyMessage.hidden = false
    emptyMessage.textContent = message
}

async function loadCategories() {
    try {
        const response = await fetch(
            '/php/actions/category/get_categories.php',
            { cache: 'no-store' }
        )
        const result = await response.json()

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'No se pudieron cargar las categorías.')
        }

        if (!result.item || result.item.length === 0) {
            showEmptyMessage('Todavía no hay categorías registradas.')
            return
        }

        categoryList.innerHTML = result.item.map(category => {
            const categoryId = Number(category.id_categoria)
            const safeId = escapeHtml(String(categoryId))
            const stateId = Number(category.id_estado_categoria)
            const isActive = stateId === 1

            return `
                <tr>
                    <td>${safeId}</td>
                    <td>${escapeHtml(category.nombre)}</td>
                    <td>${escapeHtml(category.estado)}</td>
                    <td>
                        <div class="category-row-actions">
                            <a class="category-edit-button" href="/php/pages/category/category_update.php?id=${safeId}" aria-label="Editar categoría">
                                <i data-lucide="pencil"></i>
                            </a>
                            <button class="category-state-button ${isActive ? 'is-active' : 'is-inactive'}"
                                type="button"
                                data-id="${safeId}"
                                data-state="${isActive ? 2 : 1}"
                                aria-label="${isActive ? 'Desactivar' : 'Activar'} categoría">
                                <i data-lucide="${isActive ? 'circle-pause' : 'circle-play'}"></i>
                                ${isActive ? 'Desactivar' : 'Activar'}
                                </button>
                        </div>
                    </td>
                </tr>
            `
        }).join('')

        emptyMessage.hidden = true
        categoryTable.hidden = false
        lucide.createIcons()

        categoryList.querySelectorAll('.category-state-button').forEach(button => {
            button.addEventListener('click', async () => {
                const action = Number(button.dataset.state) === 1 ? 'activar' : 'desactivar'

                if (!confirm(`¿Seguro que desea ${action} esta categoría?`)) {
                    return
                }

                const form = new FormData()
                form.append('category_id', button.dataset.id)
                form.append('state_id', button.dataset.state)

                try {
                    const response = await fetch('/php/actions/category/category_change.php', {
                        method: 'POST',
                        body: form
                    })
                    const result = await response.json()

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'No se pudo cambiar el estado de la categoría.')
                    }

                    await loadCategories()
                } catch (error) {
                    console.error(error)
                    alert(error.message || 'No se pudo cambiar el estado de la categoría.')
                }
            })
        })
    } catch (error) {
        console.error(error)
        showEmptyMessage(error.message || 'No se pudieron cargar las categorías.')
    }
}

loadCategories()