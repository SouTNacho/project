<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$is_logged = isset($_SESSION['logged']) && $_SESSION['logged'] === true;

?>
<header id="header">
    <div class="title-container">
        <h1>Hospital de Clínicas BYP</h1>
    </div>

    <div class="login-container">
        <?php if ($is_logged): ?>
            <a href="/php/actions/logout.php" onclick="return confirm('¿Está seguro de que desea cerrar la sesión?');">
                <i data-lucide="log-out"></i>
                <span>Cerrar Sesión</span>
            </a>
        <?php else: ?>
            <a href="/php/pages/login.php">
                <i data-lucide="log-in"></i>
                <span>Acceso para personal</span>
            </a>
        <?php endif; ?>
    </div>
</header>
