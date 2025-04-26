<?php

include_once 'includes/functions.php';
include_once 'includes/session.php';

requireLogin();

$pageTitle = "Erreur de paiement";

include_once 'includes/header.php';
?>

<div class="container">
    <div class="payment-error">
        <div class="error-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        
        <h2>Erreur de paiement</h2>
        <p class="error-message">Nous n'avons pas pu traiter votre paiement. Veuillez vérifier vos informations et réessayer.</p>
        
        <div class="error-details">
            <h3>Causes possibles :</h3>
            <ul>
                <li>Informations de carte bancaire incorrectes</li>
                <li>Numéro de carte invalide</li>
                <li>Date d'expiration dépassée</li>
                <li>Code de sécurité (CVV) incorrect</li>
                <li>Fonds insuffisants sur le compte</li>
                <li>Problème technique temporaire</li>
            </ul>
        </div>
        
        <div class="error-actions">
            <?php if (isset($_SESSION['trip_customize'])): ?>
                <a href="payment.php" class="btn-retry">
                    <i class="fas fa-redo"></i> Réessayer le paiement
                </a>
                <a href="trip-summary.php" class="btn-summary">
                    <i class="fas fa-list"></i> Retour au récapitulatif
                </a>
            <?php else: ?>
                <a href="index.php" class="btn-home">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
            <?php endif; ?>
        </div>
        
        <div class="contact-support">
            <p>Si le problème persiste, n'hésitez pas à contacter notre service client :</p>
            <p><i class="fas fa-envelope"></i> support@miragetravel.com</p>
            <p><i class="fas fa-phone"></i> +33 (0)1 23 45 67 89</p>
        </div>
    </div>
</div>

<?php
include_once 'includes/footer.php';
?>