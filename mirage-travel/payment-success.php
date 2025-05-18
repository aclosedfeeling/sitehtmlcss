<?php
include_once 'includes/functions.php';
include_once 'includes/session.php';

requireLogin();

// verif que l'ID de réservation est présent
if (!isset($_GET['booking_id'])) {
    header("Location: index.php");
    exit();
}

$bookingId = intval($_GET['booking_id']);
$booking = getBookingById($bookingId);

if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    header("Location: index.php");
    exit();
}

$trip = getTripById($booking['trip_id']);

$pageTitle = "Confirmation de paiement";

include_once 'includes/header.php';
?>

<div class="container">
    <div class="payment-success">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h2>Paiement accepté</h2>
        <p class="success-message">Votre réservation a été confirmée et votre paiement a été traité avec succès.</p>
        
        <div class="booking-details">
            <h3>Détails de votre réservation</h3>
            <p><strong>Référence de réservation :</strong> #<?php echo $booking['id']; ?></p>
            <p><strong>Référence de transaction :</strong> <?php echo $booking['transaction_id']; ?></p>
            <p><strong>Voyage :</strong> <?php echo $trip['titre']; ?></p>
            <p><strong>Destination :</strong> <?php echo $trip['destination']; ?></p>
            <p><strong>Date de départ :</strong> <?php echo $booking['date_depart']; ?></p>
            <p><strong>Date de retour :</strong> <?php echo $booking['date_retour']; ?></p>
            <p><strong>Nombre de personnes :</strong> <?php echo $booking['nb_personnes']; ?></p>
            <p><strong>Montant total payé :</strong> <?php echo $booking['prix_total']; ?>€</p>
        </div>
        
        <div class="next-steps">
            <h3>Et maintenant ?</h3>
            <ul>
                <li>Un email de confirmation a été envoyé à votre adresse.</li>
                <li>Vous pouvez consulter les détails de votre réservation dans votre espace personnel.</li>
                <li>Notre équipe vous contactera dans les prochains jours pour finaliser les détails de votre voyage.</li>
            </ul>
        </div>
        
        <div class="success-actions">
            <a href="profile.php" class="btn-profile">
                <i class="fas fa-user"></i> Accéder à mon profil
            </a>
            <a href="index.php" class="btn-home">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</div>

<?php
include_once 'includes/footer.php';
?>