<!-- Header -->
<nav class="top-nav">
    <div class="nav-container">
        <div class="logo">
            <img src="../../views/assets/icons/icon-32.png" alt="CupomApp Logo" class="logo-img">
            <a href="../associado/home.php" class="logo-text">CupomApp</a>
        </div>
        <div class="user-info">
            <a href="perfil.php" class="welcome-text">
                Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?>!
            </a>
            <div class="nav-links">
                <a href="meus_cupons.php">Meus Cupons</a>
                <a href="../logout.php" class="logout-link">Sair</a>
            </div>
        </div>
    </div>
</nav>


<style>

    /* nav */
    .top-nav {
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid #ddd;
    }

    .nav-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .user-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .welcome-text {
        font-weight: 500;
        color: #333;
        text-decoration: none;
        transition: color 0.2s;
    }

    .welcome-text:hover {
        color: #0066ff;
    }

    .nav-links {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .nav-links a {
        text-decoration: none;
        color: #2c3e50;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .nav-links a:hover {
        background: #e9ecef;
    }

    .logout-link {
        color: #dc3545 !important;
    }

    .logout-link:hover {
        background: #f8d7da !important;
    }
</style>
