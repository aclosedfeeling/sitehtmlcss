<?php

include_once 'includes/functions.php';
include_once 'includes/session.php';

requireLogin();

// verifier si tte les donnee de perso sont la
if (!isset($_SESSION['trip_customize'])) {
    header("Location: index.php");
    exit();
}

// ici sont stocke les donnee de perso de lautre php
$tripId = $_SESSION['trip_customize']['trip_id'];
$chosenOptions = $_SESSION['trip_customize']['options'];
$nbPersonnes = $_SESSION['trip_customize']['nb_personnes'];

$trip = getTripById($tripId);

if (!$trip) {
    header("Location: index.php");
    exit();
}

// prix total qu'on avait fais dans l'autre fichier
$totalPrice = calculateTotalPrice($trip, $chosenOptions, $nbPersonnes);

$pageTitle = "Paiement";

include_once 'includes/header.php';
?>

<div class="container">
    <div class="payment-page">
        <h2>Paiement de votre voyage</h2>
        
        <div class="payment-summary">
            <h3>Récapitulatif de la commande</h3>
            <p><strong>Voyage :</strong> <?php echo $trip['titre']; ?></p>
            <p><strong>Destination :</strong> <?php echo $trip['destination']; ?></p>
            <p><strong>Date de départ :</strong> <?php echo $trip['date_debut']; ?></p>
            <p><strong>Date de retour :</strong> <?php echo $trip['date_fin']; ?></p>
            <p><strong>Nombre de personnes :</strong> <?php echo $nbPersonnes; ?></p>
            <p class="total-price"><strong>Prix total :</strong> <?php echo $totalPrice; ?>€</p>
        </div>
        
        <div class="payment-form">
            <h3>Informations de paiement</h3>
            <form method="POST" action="payment-success.php">
                <!-- Champs cachés pour conserver les infos de la commande -->
                <input type="hidden" name="trip_id" value="<?php echo $tripId; ?>">
                <input type="hidden" name="price" value="<?php echo $totalPrice; ?>">
                <input type="hidden" name="nb_personnes" value="<?php echo $nbPersonnes; ?>">
                
                <div class="form-group">
                    <label for="card_number"><i class="fas fa-credit-card"></i> Numéro de carte :</label>
                    <div class="card-number-inputs">
                        <input type="text" id="card_number_1" name="card_number_1" maxlength="4" required placeholder="0000">
                        <input type="text" id="card_number_2" name="card_number_2" maxlength="4" required placeholder="0000">
                        <input type="text" id="card_number_3" name="card_number_3" maxlength="4" required placeholder="0000">
                        <input type="text" id="card_number_4" name="card_number_4" maxlength="4" required placeholder="0000">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="card_name"><i class="fas fa-user"></i> Nom du titulaire :</label>
                    <input type="text" id="card_name" name="card_name" required placeholder="Nom et prénom">
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="card_expiry"><i class="fas fa-calendar"></i> Date d'expiration :</label>
                        <div class="expiry-inputs">
                            <select id="card_expiry_month" name="card_expiry_month" required>
                                <option value="">Mois</option>
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                                <?php endfor; ?>
                            </select>
                            <select id="card_expiry_year" name="card_expiry_year" required>
                                <option value="">Année</option>
                                <?php 
                                $currentYear = date('Y');
                                for($i = $currentYear; $i <= $currentYear + 10; $i++): 
                                ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group half">
                        <label for="card_cvv"><i class="fas fa-lock"></i> Code de sécurité (CVV) :</label>
                        <input type="text" id="card_cvv" name="card_cvv" maxlength="3" required placeholder="123">
                    </div>
                </div>
                
                <div class="payment-actions">
                    <button type="submit" class="btn-proceed"><i class="fas fa-lock"></i> Payer maintenant (<?php echo $totalPrice; ?>€)</button>
                    <a href="trip-summary.php" class="btn-return"><i class="fas fa-arrow-left"></i> Retour au récapitulatif</a>
                </div>
            </form>
        </div>
        
        <div class="payment-security">
            <p><i class="fas fa-shield-alt"></i> Paiement sécurisé - Vos données de paiement sont protégées</p>
            <p><i class="fas fa-lock"></i> Connexion chiffrée - Vos informations personnelles sont cryptées</p>
        </div>
    </div>
</div>

<style>
    .payment-page {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .payment-summary {
        background-color: #FFFFFF;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .total-price {
        font-size: 1.2rem;
        color: #C66B3D;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #E0DACA;
    }
    
    .payment-form {
        background-color: #FFFFFF;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .card-number-inputs {
        display: flex;
        gap: 10px;
    }
    
    .card-number-inputs input {
        flex: 1;
        text-align: center;
    }
    
    .form-row {
        display: flex;
        gap: 20px;
    }
    
    .half {
        flex: 1;
    }
    
    .expiry-inputs {
        display: flex;
        gap: 10px;
    }
    
    .expiry-inputs select {
        flex: 1;
    }
    
    .payment-actions {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .btn-proceed, .btn-return {
        padding: 12px 24px;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-proceed {
        background: linear-gradient(to right, #4CAF50, #45a049);
        color: white;
        border: none;
        font-size: 1.1rem;
    }
    
    .btn-proceed:hover {
        background: linear-gradient(to right, #45a049, #4CAF50);
    }
    
    .btn-return {
        background: #1B262C;
        color: white;
        border: none;
    }
    
    .btn-return:hover {
        background: #2E4750;
    }
    
    .payment-security {
        text-align: center;
        margin-top: 20px;
        font-size: 0.9rem;
        color: #777;
    }
    
    .payment-security p {
        margin: 5px 0;
    }
</style>

<?php
include_once 'includes/footer.php';
?>