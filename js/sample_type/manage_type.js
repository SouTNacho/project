import { showNoResults } from '/js/functions.js';

const registro = '/php/pages/sample_type/type_register.php';
const update = '/php/pages/sample_type/type_register.php';
const delete_action = '/php/actions/sample/type_delete.php';

const view = document.querySelector('#view');
const searchInput = document.querySelector('#filter_search');
const searchButton = document.querySelector('#search');
const registerButton = document.querySelector('#register');




function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text ?? '';
    return div.innerHTML;
}





async function deleteType(typeId, typeName) {

    if (!confirm(`¿Está seguro de eliminar el tipo "${typeName}"? Esta acción no se puede deshacer.`)) {
        return;
    }

    try {

        const form = new FormData();
        form.append('type_id', typeId);

        const response = await fetch(delete_action, {
            method: 'POST',
            body: form
        });

        const result = await response.json();

        alert(result.message ||
            (result.success ? 'Tipo eliminado correctamente.' : 'No se pudo eliminar el tipo.'));

        if (result.success) {
            loadTypes();
        }

    } catch (error) {

        console.error('Error al eliminar el tipo:', error);
        alert('Error al eliminar el tipo, intente nuevamente.');
    }
}


function renderTypes(types) {

    view.innerHTML = types.map(type => {

        const typeId = Number(type.id_tipo);

        return `
            <li>
                <p>${escapeHtml(type.nombre)}</p>
                <button class='update-btn' data-id='${typeId}'>
                    <i data-lucide="refresh-cw"></i>
                </button>
                <button class='delete-btn' data-id='${typeId}'>
                    <i data-lucide="trash-2"></i>
                </button>
            </li>
        `;

    }).join('');

    if (window.lucide) {
        lucide.createIcons();
    }

    view.querySelectorAll('.update-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            location.href = `${update}?id=${btn.dataset.id}`;
        });
    });

    view.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {

            // El nombre se lee del <p> con textContent 
            const typeName = btn.closest('li').querySelector('p').textContent;

            deleteType(btn.dataset.id, typeName);
        });
    });
}


async function loadTypes() {

   const params = new URLSearchParams({
        search: searchInput.value.trim()
    });

    const url = `/php/actions/sample/get_type.php?${params.toString()}`;

    try {

        const response = await fetch(url);
        const responseText = await response.text();

        if (!response.ok) {
            throw new Error(`HTTP ${response.status} en ${url}\n${responseText}`);
        }

        let result;

        try {
            result = JSON.parse(responseText);
        } catch (error) {
            throw new Error(`La respuesta no es JSON válido:\n${responseText}`);
        }

        if (!result.success) {
            throw new Error(result.message || 'No se pudieron cargar los tipos.');
        }

        if (!Array.isArray(result.data) || result.data.length === 0) {
            showNoResults(view, 'No se encontraron tipos', registro);
            return;
        }

        renderTypes(result.data);

    } catch (error) {

        console.error('Error al cargar los tipos:', error);

        view.textContent = 'Error al cargar los tipos. Revisar la consola.';
    }
}


registerButton.addEventListener('click', () => {
    location.href = registro;
});

searchButton.addEventListener('click', loadTypes);

searchInput.addEventListener('keydown', event => {
    if (event.key === 'Enter') {
        loadTypes();
    }
});



if (window.lucide) {
    lucide.createIcons();
}

loadTypes();