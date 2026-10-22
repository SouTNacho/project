/* control solo para los menús de Administrador y Super Usuario. */
(() => {
    'use strict';
    const initialize = () => {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;
        const toggle = sidebar.querySelector('.sidebar-toggle');
        const nav = sidebar.querySelector('.sidebar-nav');
        if (!toggle || !nav) return;

        function setOpen(open) {
            sidebar.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', String(open));
            toggle.setAttribute('aria-label', open ? 'Cerrar menú de navegación' : 'Abrir menú de navegación');
        }

        toggle.addEventListener('click', () => {
            setOpen(!sidebar.classList.contains('is-open'));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && sidebar.classList.contains('is-open')) {
                setOpen(false);
                toggle.focus();
            }
        });

        document.addEventListener('click', (event) => {
            if (sidebar.classList.contains('is-open') && !sidebar.contains(event.target)) {
                setOpen(false);
            }
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, { once: true });
    } else {
        initialize();
    }
})();
