(() => {
    'use strict';
    if (window.BYPNotify) return;

    const titles = {
        success: 'Operación realizada',
        error: 'Ocurrió un error',
        warning: 'Revisá los datos',
        info: 'Información'
    };
    const symbols = { success: '✓', error: 'x', warning: '!', info: 'i' };
    let stack;

    function ensureStack() {
        if (stack?.isConnected) return stack;
        stack = document.createElement('div');
        stack.className = 'byp-toast-stack';
        stack.setAttribute('aria-label', 'Notificaciones del sistema');
        document.body.appendChild(stack);
        return stack;
    }
 
    function notify(message, type = 'info', options = {}) {
        const text = String(message ?? '').trim();
        if (!text) return;
        const kind = Object.hasOwn(titles, type) ? type : 'info';
        const target = ensureStack();
        const toast = document.createElement('div');
        toast.className = `byp-toast byp-toast--${kind}`;
        toast.setAttribute('role', kind === 'error' ? 'alert' : 'status');

        const symbol = document.createElement('span');
        symbol.className = 'byp-toast-icon';
        symbol.setAttribute('aria-hidden', 'true');
        symbol.textContent = symbols[kind];

        const content = document.createElement('div');
        content.className = 'byp-toast-content';
        const heading = document.createElement('strong');
        heading.textContent = options.title || titles[kind];
        const body = document.createElement('p');
        body.textContent = text;
        content.append(heading, body);

        const close = document.createElement('button');
        close.className = 'byp-toast-close';
        close.type = 'button';
        close.setAttribute('aria-label', 'Cerrar notificación');
        close.textContent = 'x';
        toast.append(symbol, content, close);
        target.appendChild(toast);

        const duration = Math.max(2500, Number(options.duration) || (kind === 'error' ? 7000 : 5000));
        let remaining = duration;
        let start = Date.now();
        let timer;
        let removed = false;
        const remove = () => {
            if (removed) return;
            removed = true;
            clearTimeout(timer);
            toast.classList.add('byp-toast--closing');
            toast.addEventListener('animationend', () => toast.remove(), { once: true });
            setTimeout(() => toast.remove(), 400);
        };
        const pause = () => {
            clearTimeout(timer);
            remaining = Math.max(0, remaining - (Date.now() - start));
        };
        const resume = () => {
            if (removed) return;
            start = Date.now();
            timer = setTimeout(remove, remaining);
        };
        close.addEventListener('click', remove);
        toast.addEventListener('mouseenter', pause);
        toast.addEventListener('mouseleave', resume);
        toast.addEventListener('focusin', pause);
        toast.addEventListener('focusout', (e) => {
            if (!toast.contains(e.relatedTarget)) resume();
        });
        resume();
        return toast;
    }

    window.BYPNotify = notify;

    // queda por hacer el window.BYPConfirm = confirmAction; para los confirm
})();
