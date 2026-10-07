const actionTable = document.querySelector('#document-action-table')
const actionList = document.querySelector('#document-action-list')
const emptyMessage = document.querySelector('#document-action-empty')

function escapeHtml(value) {
    const element = document.createElement('div')
    element.textContent = value ?? ''
    return element.innerHTML
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
}

function showEmptyMessage(message) {
    actionTable.hidden = true
    emptyMessage.hidden = false
    emptyMessage.textContent = message
}

async function loadDocumentActions() {
    try {
        const response = await fetch(
            '/php/actions/document_action/get_document_actions.php',
            { cache: 'no-store' }
        )
        const result = await response.json()

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'No se pudieron cargar las acciones de documentos.')
        }

        if (!result.item || result.item.length === 0) {
            showEmptyMessage('Todavía no hay acciones de documentos registradas.')
            return
        }

        actionList.innerHTML = result.item.map(action => {
            const actionId = Number(action.id_accion)
            const safeId = escapeHtml(String(actionId))
            const safeName = escapeHtml(action.nombre)

            return `
                <tr>
                    <td>${safeId}</td>
                    <td>${safeName}</td>
                    <td>
                        <div class="document-action-row-actions">
                            <a class="document-action-edit-button" href="/php/pages/document_action/document_action_update.php?id=${safeId}" aria-label="Editar acción">
                                <i data-lucide="pencil"></i>
                            </a>
                            <button class="document-action-delete-button" type="button" data-id="${safeId}" data-name="${safeName}" aria-label="Eliminar acción">
                                <i data-lucide="trash-2"></i>
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            `
        }).join('')

        emptyMessage.hidden = true
        actionTable.hidden = false
        lucide.createIcons()

        actionList.querySelectorAll('.document-action-delete-button').forEach(button => {
            button.addEventListener('click', async () => {
                if (!confirm(`¿Seguro que desea eliminar la acción "${button.dataset.name}"?`)) {
                    return
                }

                const form = new FormData()
                form.append('action_id', button.dataset.id)

                try {
                    const response = await fetch('/php/actions/document_action/document_action_delete.php', {
                        method: 'POST',
                        body: form
                    })
                    const result = await response.json()

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'No se pudo eliminar la acción.')
                    }

                    await loadDocumentActions()
                } catch (error) {
                    console.error(error)
                    alert(error.message || 'No se pudo eliminar la acción.')
                }
            })
        })
    } catch (error) {
        console.error(error)
        showEmptyMessage(error.message || 'No se pudieron cargar las acciones de documentos.')
    }
}

loadDocumentActions()
