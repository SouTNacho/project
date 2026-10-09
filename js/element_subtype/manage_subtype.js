import { showNoResults } from '/js/functions.js';

const registro = '/php/pages/element_subtype/subtype_register.php';
const update = '/php/pages/element_subtype/subtype_register.php';
const delete_action = '/php/actions/element/subtype_delete.php';

const view = document.querySelector('#view');
const searchInput = document.querySelector('#filter_search');
const searchButton = document.querySelector('#search');
const registerButton = document.querySelector('#register');

const filters = [
    document.querySelector('#filter_all'),
    document.querySelector('#filter_bio'),
    document.querySelector('#filter_nonbio')
];


function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text ?? '';
    return div.innerHTML;
}


function getSelectedFilter() {
    const selected = filters.find(filter => filter.checked);
    return selected ? selected.value : 'all';
}


async function deleteSubtype(subtypeId, subtypeName) {

    if (!confirm(`¿Está seguro de eliminar el subtipo "${subtypeName}"? Esta acción no se puede deshacer.`)) {
        return;
    }

    try {

        const form = new FormData();
        form.append('subtype_id', subtypeId);

        const response = await fetch(delete_action, {
            method: 'POST',
            body: form
        });

        const result = await response.json();

        alert(result.message ||
            (result.success ? 'Subtipo eliminado correctamente.' : 'No se pudo eliminar el subtipo.'));

        if (result.success) {
            loadSubtypes();
        }

    } catch (error) {

        console.error('Error al eliminar el subtipo:', error);
        alert('Error al eliminar el subtipo, intente nuevamente.');
    }
}


function renderSubtypes(subtypes) {

    view.innerHTML = subtypes.map(subtype => {

        const subtypeId = Number(subtype.id_subtipo);

        return `
            <li>
                <p>${escapeHtml(subtype.nombre)}</p>
                <p>Tipo: ${escapeHtml(subtype.nombre_tipo)}</p>
                <button class='update-btn' data-id='${subtypeId}'>
                    <i data-lucide="refresh-cw"></i>
                </button>
                <button class='delete-btn' data-id='${subtypeId}'>
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

            // El nombre se lee del <p> con textContent (no se mete en un atributo HTML)
            const subtypeName = btn.closest('li').querySelector('p').textContent;

            deleteSubtype(btn.dataset.id, subtypeName);
        });
    });
}


async function loadSubtypes() {

    const params = new URLSearchParams({
        search: searchInput.value.trim(),
        filter: getSelectedFilter()
    });

    const url = `/php/actions/element/get_element_subtype.php?${params.toString()}`;

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
            throw new Error(result.message || 'No se pudieron cargar los subtipos.');
        }

        if (!Array.isArray(result.data) || result.data.length === 0) {
            showNoResults(view, 'No se encontraron subtipos', registro);
            return;
        }

        renderSubtypes(result.data);

    } catch (error) {

        console.error('Error al cargar los subtipos:', error);

        view.textContent = 'Error al cargar los subtipos. Revisar la consola.';
    }
}


registerButton.addEventListener('click', () => {
    location.href = registro;
});

searchButton.addEventListener('click', loadSubtypes);

searchInput.addEventListener('keydown', event => {
    if (event.key === 'Enter') {
        loadSubtypes();
    }
});

filters.forEach(filter => {
    filter.addEventListener('change', loadSubtypes);
});

loadSubtypes();