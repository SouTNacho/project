import { showNoResults } from '/js/functions.js';
 
const registro = '/php/pages/element_subtype/sub_type_register.php';
const update = '/php/pages/element_subtype/sub_type_update.php';
 
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
 

const nombresParaTipos = { 1: 'Biológico', 2: 'No biológico' };



function renderSubtypes(subtypes) {
 
    view.innerHTML = subtypes.map(subtype => {
 
        const subtypeId = Number(subtype.id_subtipo);
 
        return `
            <li>
                <p>${escapeHtml(subtype.nombre)}</p>
                <p>Tipo: ${nombresParaTipos[subtype.tipo] ?? 'Desconocido'}</p>
                <button class='update-btn' data-id='${subtypeId}'>
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