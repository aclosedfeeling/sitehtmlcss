<?php

include_once 'includes/functions.php';
include_once 'includes/session.php';

requireLogin();

// données de perso sont la ou pas
if (!isset($_SESSION['trip_customize'])) {
    header("Location: index.php");
    exit();
}

// donnée de paimeent soumise ou pas
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['trip_id'], $_POST['price'], $_POST['nb_personnes'])) {
    header("Location: index.php");
    exit();
}

// donnees de tout dans le voyage prix option id etc
$tripId = intval($_POST['trip_id']);
$totalPrice = floatval($_POST['price']);
$nbPersonnes = intval($_POST['nb_personnes']);
$chosenOptions = $_SESSION['trip_customize']['options'];

// details du voyage
$trip = getTripById($tripId);

if (!$trip) {
    header("Location: index.php");
    exit();
}

// simulation d' une vérification de coordonnées bancaires
$cardNumber1 = isset($_POST['card_number_1']) ? $_POST['card_number_1'] : '';
$cardNumber2 = isset($_POST['card_number_2']) ? $_POST['card_number_2'] : '';
$cardNumber3 = isset($_POST['card_number_3']) ? $_POST['card_number_3'] : '';
$cardNumber4 = isset($_POST['card_number_4']) ? $_POST['card_number_4'] : '';
$cardName = isset($_POST['card_name']) ? $_POST['card_name'] : '';
$cardExpiryMonth = isset($_POST['card_expiry_month']) ? $_POST['card_expiry_month'] : '';
$cardExpiryYear = isset($_POST['card_expiry_year']) ? $_POST['card_expiry_year'] : '';
$cardCvv = isset($_POST['card_cvv']) ? $_POST['card_cvv'] : '';

// verif des champs remplis
if (empty($cardNumber1) || empty($cardNumber2) || empty($cardNumber3) || empty($cardNumber4) ||
    empty($cardName) || empty($cardExpiryMonth) || empty($cardExpiryYear) || empty($cardCvv)) {
    header("Location: payment-error.php");
    exit();
}

// simulation validation
$cardNumberFull = $cardNumber1 . $cardNumber2 . $cardNumber3 . $cardNumber4;
$isValidCard = strlen($cardNumberFull) === 16 && ctype_digit($cardNumberFull) && strlen($cardCvv) === 3 && ctype_digit($cardCvv);

// si la carte marche pas on redirige vers la page d'erreur
if (!$isValidCard) {
    header("Location: payment-error.php");
    exit();
}

// enregistre la reservation
$booking = saveBooking(
    $_SESSION['user_id'],
    $tripId,
    $chosenOptions,
    $totalPrice,
    $nbPersonnes,
    $trip['date_debut'],
    $trip['date_fin']
);

// supp les données de perso
unset($_SESSION['trip_customize']);

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
            <p><strong>Voyage :</strong> <?php echo $trip['titre']; ?></p>
            <p><strong>Destination :</strong> <?php echo $trip['destination']; ?></p>
            <p><strong>Date de départ :</strong> <?php echo $trip['date_debut']; ?></p>
            <p><strong>Date de retour :</strong> <?php echo $trip['date_fin']; ?></p>
            <p><strong>Nombre de personnes :</strong> <?php echo $nbPersonnes; ?></p>
            <p><strong>Montant total payé :</strong> <?php echo $totalPrice; ?>€</p>
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