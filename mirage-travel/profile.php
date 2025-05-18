<?php
// titre
$pageTitle = "Mon Profil";

// fonctions
include_once 'includes/functions.php';
include_once 'includes/session.php';

// verif si l'user ets log
requireLogin();

// user info
$user = getUserById($_SESSION['user_id']);

// les reservations ici
$userBookings = getUserBookings($_SESSION['user_id']);

// variables 
$success = '';
$error = '';

// modif des profils ms c apres
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // pr apres avec le java
    $success = "Profil mis à jour avec succès.";
}

// header
include_once 'includes/header.php';
?>

<div class="container">
    <?php if ($success): ?>
        <div class="success-message">
            <p><?php echo $success; ?></p>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error-message">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <div class="profile-section">
        <img src="images/image8.jpg" alt="Photo de profil" class="profile-image">
        <h2 class="text-center">Informations personnelles</h2>
        
        <div class="form-group">
            <label><i class="fas fa-user"></i> Nom :</label>
            <span><?php echo $user['nom']; ?></span>
            <button class="edit-button"><i class="fas fa-pencil-alt"></i></button>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-user"></i> Prénom :</label>
            <span><?php echo $user['prenom']; ?></span>
            <button class="edit-button"><i class="fas fa-pencil-alt"></i></button>
        </div>

        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email :</label>
            <span><?php echo $user['email']; ?></span>
            <button class="edit-button"><i class="fas fa-pencil-alt"></i></button>
        </div>

        <div class="form-group">
            <label><i class="fas fa-phone"></i> Téléphone :</label>
            <span><?php echo isset($user['telephone']) ? $user['telephone'] : 'Non renseigné'; ?></span>
            <button class="edit-button"><i class="fas fa-pencil-alt"></i></button>
        </div>
    </div>

    <?php if (isset($user['destinations_favorites']) || isset($user['types_voyage_preferes'])): ?>
    <div class="profile-section">
        <h2>Préférences de voyage</h2>
        <?php if (isset($user['destinations_favorites'])): ?>
        <div class="form-group">
            <label><i class="fas fa-map-marker-alt"></i> Destinations favorites :</label>
            <span><?php echo implode(', ', $user['destinations_favorites']); ?></span>
            <button class="edit-button"><i class="fas fa-pencil-alt"></i></button>
        </div>
        <?php endif; ?>

        <?php if (isset($user['types_voyage_preferes'])): ?>
        <div class="form-group">
            <label><i class="fas fa-hiking"></i> Type de voyage préféré :</label>
            <span><?php echo implode(', ', $user['types_voyage_preferes']); ?></span>
            <button class="edit-button"><i class="fas fa-pencil-alt"></i></button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="profile-section">
        <h2>Mes voyages réservés</h2>
        
        <?php if (empty($userBookings)): ?>
            <p>Vous n'avez pas encore réservé de voyage.</p>
        <?php else: ?>
            <?php foreach ($userBookings as $booking): 
                $trip = getTripById($booking['trip_id']);
                if (!$trip) continue;
            ?>
            <div class="trip-card">
                <img src="images/<?php echo $trip['image']; ?>" alt="<?php echo $trip['titre']; ?>" class="trip-image">
                <h3><?php echo $trip['titre']; ?></h3>
                <p><i class="fas fa-calendar-alt"></i> Date : <?php echo $booking['date_depart']; ?> au <?php echo $booking['date_retour']; ?></p>
                <p><i class="fas fa-check-circle icon-success"></i> Statut : <?php echo $booking['statut']; ?></p>
                <a href="trip-details.php?id=<?php echo $trip['id']; ?>"><button><i class="fas fa-info-circle"></i> Voir le détail</button></a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
// footer
include_once 'includes/footer.php';
?>