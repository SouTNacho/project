import { showNoResults } from '/js/functions.js';
 
const registro = '/php/pages/sample_type/type_form.php';
const update = '/php/pages/sample_type/type_update.php';
 
const view = document.querySelector('#view');
const searchInput = document.querySelector('#filter_search');
const searchButton = document.querySelector('#search');
const registerButton = document.querySelector('#register');
 
const filters = [
    document.querySelector('#filter_all'),
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
 


function renderTypes(types) {
 
    view.innerHTML = types.map(type => {
 
        const typeId = Number(type.id_tipo);
 
        return `
            <li>
                <p>${escapeHtml(type.nombre)}</p>
                <p>Tipo: ${nombresParaTipos[type.tipo] ?? 'Desconocido'}</p>
                <button class='update-btn' data-id='${typeId}'>
                    <i data-lucide="refresh-cw"></i>
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
}
 
 
async function loadTypes() {

 
    const params = new URLSearchParams({
        search: searchInput.value.trim(),
        filter: getSelectedFilter()
    });
 
    const url = `/php/actions/sample_type/get_sample_type.php?${params.toString()}`;
 
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
 
filters.forEach(filter => {
    filter.addEventListener('change', loadTypes);
});
 
loadTypes();