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

            return `
                <tr>
                    <td>${safeId}</td>
                    <td>${escapeHtml(category.nombre)}</td>
                    <td>
                        <div class="category-row-actions">
                            <a class="category-edit-button" href="/php/pages/category/category_update.php?id=${safeId}" aria-label="Editar categoría">
                                <i data-lucide="pencil"></i>
                            </a>
                            <form class="category-delete-form" action="/php/actions/category/category_delete.php" method="post" onsubmit="return confirm('¿Seguro que desea eliminar esta categoría?');">
                                <input type="hidden" name="category_id" value="${safeId}">
                                <button class="category-delete-button" type="submit" aria-label="Eliminar categoría">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            `
        }).join('')

        emptyMessage.hidden = true
        categoryTable.hidden = false
        lucide.createIcons()
    } catch (error) {
        console.error(error)
        showEmptyMessage(error.message || 'No se pudieron cargar las categorías.')
    }
}

loadCategories()