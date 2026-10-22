<header id="header">
    <?php if (!$is_logged): ?>
            <a href="/index.php">
                <img src="/src/logo-white.svg" alt="">
            </a>
        <?php endif; ?>
    <div class="title-container">
        <h1>Hospital de Clínicas BYP</h1>
    </div>
    <div class="login-container">
        <?php if ($is_logged): ?>
            <a href="/php/actions/logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="/php/pages/login.php">Acceso para personal</a>
        <?php endif; ?>
    </div>
</header>