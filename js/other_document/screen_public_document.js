const token = new URLSearchParams(window.location.search).get('token');
const title = document.getElementById('viewer-title');
const subtitle = document.getElementById('viewer-subtitle');
const status = document.getElementById('viewer-status');
const iframe = document.getElementById('document_view');
const actions = document.getElementById('viewer-actions');
const openLink = document.getElementById('viewer-open');
const downloadLink = document.getElementById('viewer-download');

function renderIcons() {
    if (window.lucide) window.lucide.createIcons();
}

function showError(message) {
    iframe.hidden = true;
    actions.hidden = true;
    title.textContent = 'No se pudo visualizar el documento';
    subtitle.textContent = 'La vista previa no está disponible.';
    status.hidden = false;
    status.classList.add('viewer-error');
    status.innerHTML = '';
    const heading = document.createElement('h2');
    heading.textContent = 'No pudimos cargar el documento';
    const info = document.createElement('p');
    info.textContent = message;
    const link = document.createElement('a');
    link.href = '/php/pages/other_document/public_documents.php';
    link.className = 'viewer-button viewer-primary';
    link.textContent = 'Volver a documentos';
    status.append(heading, info, link);
}

async function loadDocument() {
    if (!token) {
        showError('No se recibió un enlace válido. Volvé al listado y elegí el documento nuevamente.');
        return;
    }
    try {
        const response = await fetch(`/php/actions/document/get_document.php?token=${encodeURIComponent(token)}`, {
            headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error('No se pudo consultar el documento.');
        const result = await response.json();
        if (!result.success || !result.item || !result.item.archivo) {
            throw new Error(result.message || 'El documento solicitado no está disponible.');
        }
        const item = result.item;
        const fileURL = new URL(item.archivo, window.location.origin);
        if (fileURL.origin !== window.location.origin || !/^https?:$/.test(fileURL.protocol)) {
            throw new Error('La ubicación del archivo no es válida.');
        }
        title.textContent = item.nombre || 'Documento';
        subtitle.textContent = 'Documento disponible para consulta pública';
        document.title = `${item.nombre || 'Documento'} - BYP`;
        openLink.href = fileURL.href;
        downloadLink.href = `/php/actions/document/download_document.php?token=${encodeURIComponent(token)}`;
        actions.hidden = false;
        iframe.addEventListener('load', () => {
            status.hidden = true;
            iframe.hidden = false;
        }, { once: true });
        iframe.src = fileURL.href;
    } catch (error) {
        console.error('Error al visualizar el documento:', error);
        showError(error.message || 'Ocurrió un error. Intentá nuevamente.');
    }
}

renderIcons();
loadDocument();
