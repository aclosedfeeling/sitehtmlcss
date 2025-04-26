<?php
// titre
$pageTitle = "Inscription";

// implementations des fonct
include_once 'includes/functions.php';
include_once 'includes/session.php';

// si le mec est deja log il le redirige direct pcq dcp la page register sert a ça
redirectIfLoggedIn();

// variable erreur et succes
$error = '';
$success = '';

// FORMULAIR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';
    
    // Validation simple
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($password !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (userExists('', $email)) {
        $error = "Cet email est déjà utilisé.";
    } else {
        // on prend le nom prenom pr le login
        $login = strtolower($prenom . '.' . $nom);
        
        // Verif existance login 
        if (userExists($login, '')) {
            // rajt un chiffre aleatoire 
            $login .= rand(1, 99);
        }
        
        // Créer l'user
        $userData = [
            'login' => $login,
            'password' => $password, 
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email
        ];
        
        $newUser = addUser($userData);
        
        if ($newUser) {
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            
          
        } else {
            $error = "Une erreur est survenue lors de l'inscription.";
        }
    }
}

// header
include_once 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center">Créez votre compte</h2>
        <p class="text-center mb-5">Rejoignez Mirage Travel pour découvrir des expériences uniques dans les plus beaux déserts du monde.</p>
        
        <?php if ($error): ?>
            <div class="error-message">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
                <p><?php echo $success; ?></p>
                <p><a href="login.php">Se connecter</a></p>
            </div>
        <?php else: ?>
            <form method="POST" action="register.php">
                <div class="form-group">
                    <label for="nom"><i class="fas fa-user"></i> Nom :</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                </div>

                <div class="form-group">
                    <label for="prenom"><i class="fas fa-user"></i> Prénom :</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email :</label>
                    <input type="email" id="email" name="email" placeholder="votre@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Mot de passe :</label>
                    <input type="password" id="password" name="password" placeholder="Choisissez un mot de passe sécurisé" required>
                </div>

                <div class="form-group">
                    <label for="confirm-password"><i class="fas fa-lock"></i> Confirmer le mot de passe :</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmez votre mot de passe" required>
                </div>

                <div class="text-center">
                    <button type="submit"><i class="fas fa-user-plus"></i> S'inscrire</button>
                </div>
                
                <p class="text-center mt-5">Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php
// en bas 
include_once 'includes/footer.php';
?>