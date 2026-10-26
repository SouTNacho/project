(() => {
    'use strict';
    const form = document.querySelector('#tracking-form');
    const start = document.querySelector('#start-transfer');
    const pause = document.querySelector('#pause-transfer');
    const finish = document.querySelector('#finish-transfer');
    const message = document.querySelector('#tracking-message');
    const endpoint = '/php/actions/driver/tracking.php';
    let state = null;
    let timer = null;
    let generation = 0;
    let locationBusy = false;
    let actionBusy = false;
    let sending = false;

    function tell(text, error = false) {
        message.textContent = text;
        message.dataset.error = String(error);
    }

    function render(data) {
        state = data.estado;
        document.querySelector('#transfer-state').textContent = `Traslado ${data.id_traslado} · ${state}`;
        start.hidden = state === 'Finalizado' || (state === 'En curso' && sending);
        start.textContent = state === 'En pausa' ? 'Reanudar traslado' : state === 'En curso' ? 'Reanudar envío de ubicación' : 'Iniciar traslado';
        pause.hidden = state !== 'En curso';
        finish.hidden = !['En curso', 'En pausa'].includes(state);
        for (const button of [start, pause, finish]) button.disabled = actionBusy;
        if (data.ubicacion) {
            document.querySelector('#latitude').textContent = data.ubicacion.latitud;
            document.querySelector('#longitude').textContent = data.ubicacion.longitud;
            document.querySelector('#last-update').textContent = data.ubicacion.fecha_hora;
        }
    }

    async function request(operation, coordinates = {}) {
        const options = { credentials: 'same-origin', cache: 'no-store' };
        if (operation) {
            options.method = 'POST';
            options.body = new FormData(form);
            options.body.set('operacion', operation);
            for (const [key, value] of Object.entries(coordinates)) options.body.set(key, value);
        }
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 15000);
        options.signal = controller.signal;
        try {
            const response = await fetch(endpoint, options);
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'No se pudo guardar el cambio.');
            return result.traslado;
        } catch (error) {
            if (error.name === 'AbortError') throw new Error('La conexión demoró demasiado. Revisá el estado antes de volver a intentar.');
            if (error instanceof TypeError || error instanceof SyntaxError) throw new Error('No se pudo conectar con el servidor. Revisá la conexión y la sesión.');
            throw error;
        } finally {
            clearTimeout(timeout);
        }
    }

    function currentLocation() {
        return new Promise((resolve, reject) => {
            if (!window.isSecureContext) return reject(new Error('La ubicación requiere HTTPS o localhost. Una dirección HTTP con la IP de la computadora no alcanza.'));
            if (!navigator.geolocation) return reject(new Error('Este navegador no permite obtener la ubicación.'));
            navigator.geolocation.getCurrentPosition(
                position => resolve({ latitud: position.coords.latitude, longitud: position.coords.longitude }),
                error => reject(new Error(error.code === 1 ? 'Permití el acceso a tu ubicación para iniciar el envío.' : error.code === 3 ? 'El GPS demoró demasiado. Volvé a intentar.' : 'No se pudo obtener la ubicación. Revisá el GPS del dispositivo.')),
                { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 }
            );
        });
    }

    function stopSending() {
        generation++;
        sending = false;
        clearInterval(timer);
        timer = null;
    }

    function beginSending() {
        stopSending();
        sending = true;
        const run = generation;
        timer = setInterval(async () => {
            if (locationBusy || actionBusy || run !== generation) return;
            locationBusy = true;
            try {
                const coordinates = await currentLocation();
                if (run !== generation) return;
                const data = await request('ubicacion', coordinates);
                if (run !== generation) return;
                render(data);
                tell('Ubicación guardada. Envío activo.');
            } catch (error) {
                if (run !== generation) return;
                stopSending();
                if (state === 'En curso') {
                    start.hidden = false;
                    start.textContent = 'Reanudar envío de ubicación';
                }
                tell(`${error.message} El envío se detuvo; podés reintentarlo.`, true);
            } finally {
                locationBusy = false;
            }
        }, 5000);
    }

    async function changeState(operation) {
        if (actionBusy) return;
        if (operation === 'finalizar' && !window.confirm('¿Finalizar el traslado? No se podrá reanudar.')) return;
        actionBusy = true;
        stopSending();
        for (const button of [start, pause, finish]) button.disabled = true;
        tell(operation === 'iniciar' ? 'Obteniendo ubicación…' : 'Guardando cambio…');
        try {
            const coordinates = operation === 'iniciar' ? await currentLocation() : {};
            const data = await request(operation, coordinates);
            if (operation === 'iniciar') beginSending();
            render(data);
            tell(operation === 'iniciar' ? 'Traslado en curso. Envío de ubicación activo.' : operation === 'pausar' ? 'Traslado pausado. No se está enviando ubicación.' : 'Traslado finalizado. Envío de ubicación detenido.');
        } catch (error) {
            try { render(await request()); } catch (_) {
                
             }
            tell(`${error.message} No se está enviando ubicación.`, true);
        } finally {
            actionBusy = false;
            for (const button of [start, pause, finish]) button.disabled = false;
        }
    }

    start.addEventListener('click', () => changeState('iniciar'));
    pause.addEventListener('click', () => changeState('pausar'));
    finish.addEventListener('click', () => changeState('finalizar'));
    window.addEventListener('pagehide', stopSending);
    window.addEventListener('pageshow', event => {
        if (event.persisted) window.location.reload();
    });
    request().then(data => {
        render(data);
        tell(data.estado === 'En curso' ? 'Presioná Reanudar envío de ubicación para continuar desde este dispositivo.' : 'Listo para continuar.');
    }).catch(error => tell(error.message, true));
})();
