<?php
include_once 'session.php';
include_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Mirage Travel</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1><?php echo isset($pageTitle) ? $pageTitle : 'Mirage Travel'; ?></h1>
        <p>Découvrez la magie du désert</p>
        <img src="images/logo.png" alt="Mirage Travel Logo" class="site-logo">
    </header>
    
    <nav>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Accueil</a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> À propos</a></li>
            <li><a href="search.php"><i class="fas fa-search"></i> Rechercher</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
                <?php if (isAdmin()): ?>
                    <li><a href="admin.php"><i class="fas fa-user-shield"></i> Administration</a></li>
                <?php endif; ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            <?php else: ?>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> S'inscrire</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Se connecter</a></li>
            <?php endif; ?>
        </ul>
    </nav>