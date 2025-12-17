<!--header -->
<nav class="top-nav">
    <div class="nav-container">
        <div class="logo">
            <img src="../../views/assets/icons/icon-32.png" alt="CupomApp Logo" class="logo-img">
            <a href="home.php" class="logo-text">CupomApp | Comerciante</a>
        </div>
        <div class="welcome-text">
            <a href="perfil.php" class="welcome-text">
                Olá, <?php echo htmlspecialchars($_SESSION['nome_fantasia']); ?>!
            </a>
        </div>
        <div class="nav-links">
            <a href="cadastrar_cupom.php"><i class="ph-bold ph-plus"> </i> Novo Cupom</a>
            <a href="validar_cupom.php"><i class="ph-bold ph-check"></i> Validar Cupom</a>
            <a href="../logout.php" class="logout-link">Sair</a>
        </div>
    </div>
</nav>

<style>

/* nav */
.top-nav {
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: static;
    border-bottom: 1px solid #ddd;
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.logo-img {
    width: 28px;
    height: 28px;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50; 
    text-decoration: none;
}

.logo-text:hover {
    color: #007bff;
}



.welcome-text {
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
    text-decoration: none;
}

.nav-links {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.nav-links a {
    text-decoration: none;
    color: #ffffff;
    font-weight: 500;
    padding: 0.6rem 1.2rem;
    border-radius: 6px;
    transition: all 0.2s;
    background: #007bff;
    font-size: 0.9rem;
}

.nav-links a:hover {
    background: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.logout-link {
    background: #dc3545 !important;
}

.logout-link:hover {
    background: #c82333 !important;
}
</style>
