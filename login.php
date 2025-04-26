<?php
// titre
$pageTitle = "Connexion";

// fonct
include_once 'includes/functions.php';
include_once 'includes/session.php';

// redirect si deja log
redirectIfLoggedIn();

// variable erreur
$error = '';

// form connextion 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // valid  
    if (empty($login) || empty($password)) {
        $error = "Veuillez saisir votre email et votre mot de passe.";
    } else {
        // auth 
        $user = authenticateUser($login, $password);
        
        if ($user) {
            // connecter
            loginUser($user);
            
            // redirect vers lacceuil une fois co
            header("Location: index.php");
            exit();
        } else {
            $error = "Identifiants incorrects ou compte banni.";
        }
    }
}

// header
include_once 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center">Accédez à votre compte</h2>
        <p class="text-center mb-5">Entrez vos identifiants pour accéder à votre espace personnel et gérer vos voyages.</p>
        
        <?php if ($error): ?>
            <div class="error-message">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email :</label>
                <input type="text" id="email" name="email" placeholder="votre@email.com ou login" required>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Mot de passe :</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>

            <div class="text-center">
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
            </div>
            <p class="text-center mt-5">Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
        </form>
    </div>
</div>

            <?php
// FOOTER 
include_once 'includes/footer.php';
?>