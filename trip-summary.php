<?php

include_once 'includes/functions.php';
include_once 'includes/session.php';

requireLogin();

if (!isset($_SESSION['trip_customize'])) {
    header("Location: index.php");
    exit();
}

$tripId = $_SESSION['trip_customize']['trip_id'];
$chosenOptions = $_SESSION['trip_customize']['options'];
$nbPersonnes = $_SESSION['trip_customize']['nb_personnes'];

$trip = getTripById($tripId);

if (!$trip) {
    header("Location: index.php");
    exit();
}

$totalPrice = calculateTotalPrice($trip, $chosenOptions, $nbPersonnes);

$pageTitle = "Récapitulatif du voyage : " . $trip['titre'];

include_once 'includes/header.php';
?>

<div class="container">
    <div class="trip-summary">
        <h2>Récapitulatif de votre voyage</h2>
        
        <div class="summary-header">
            <h3><?php echo $trip['titre']; ?></h3>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo $trip['destination']; ?></p>
            <p><i class="fas fa-calendar-alt"></i> Durée : <?php echo $trip['duree']; ?> jours</p>
            <p><i class="fas fa-users"></i> Nombre de personnes : <?php echo $nbPersonnes; ?></p>
            <p class="total-price"><i class="fas fa-euro-sign"></i> Prix total : <strong><?php echo $totalPrice; ?>€</strong></p>
        </div>
        
        <div class="summary-etapes">
            <h3><i class="fas fa-route"></i> Détail de votre voyage personnalisé</h3>
            
            <?php foreach ($trip['etapes'] as $index => $etape): ?>
            <div class="etape-summary-card">
                <h4>Jour <?php echo $etape['jour']; ?> : <?php echo $etape['titre']; ?></h4>
                <p><i class="fas fa-map-pin"></i> <?php echo $etape['lieu']; ?></p>
                
                <div class="options-summary">
                    <!-- hebergement -->
                    <div class="option-summary-item">
                        <strong><i class="fas fa-bed"></i> Hébergement : </strong>
                        <?php echo $chosenOptions[$index]['hebergement']; ?>
                        <?php 
                        foreach ($etape['options']['hebergement'] as $hebergement) {
                            if ($hebergement['nom'] === $chosenOptions[$index]['hebergement'] && $hebergement['prix'] > 0) {
                                echo " <span class=\"option-price\">(+{$hebergement['prix']}€/pers.)</span>";
                            }
                        }
                        ?>
                    </div>
                    
                    <!-- restau -->
                    <div class="option-summary-item">
                        <strong><i class="fas fa-utensils"></i> Restauration : </strong>
                        <?php echo $chosenOptions[$index]['restauration']; ?>
                        <?php 
                        foreach ($etape['options']['restauration'] as $restauration) {
                            if ($restauration['nom'] === $chosenOptions[$index]['restauration'] && $restauration['prix'] > 0) {
                                echo " <span class=\"option-price\">(+{$restauration['prix']}€/pers.)</span>";
                            }
                        }
                        ?>
                    </div>
                    
                    <!-- activités -->
                    <div class="option-summary-item">
                        <strong><i class="fas fa-hiking"></i> Activités : </strong>
                        <?php if (empty($chosenOptions[$index]['activites'])): ?>
                            <em>Aucune activité sélectionnée</em>
                        <?php else: ?>
                            <ul>
                                <?php foreach ($chosenOptions[$index]['activites'] as $activiteNom): ?>
                                    <li>
                                        <?php echo $activiteNom; ?>
                                        <?php 
                                        foreach ($etape['options']['activites'] as $activite) {
                                            if ($activite['nom'] === $activiteNom && $activite['prix'] > 0) {
                                                echo " <span class=\"option-price\">(+{$activite['prix']}€/pers.)</span>";
                                            }
                                        }
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                    <!-- transport (si ya) -->
                    <?php if (isset($etape['options']['transport']) && isset($chosenOptions[$index]['transport'])): ?>
                    <div class="option-summary-item">
                        <strong><i class="fas fa-car"></i> Transport : </strong>
                        <?php echo $chosenOptions[$index]['transport']; ?>
                        <?php 
                        foreach ($etape['options']['transport'] as $transport) {
                            if ($transport['nom'] === $chosenOptions[$index]['transport'] && $transport['prix'] > 0) {
                                echo " <span class=\"option-price\">(+{$transport['prix']}€)</span>";
                            }
                        }
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="summary-actions">
            <a href="payment.php" class="btn-payment"><i class="fas fa-credit-card"></i> Procéder au paiement</a>
            <a href="trip-customize.php?id=<?php echo $tripId; ?>" class="btn-modify"><i class="fas fa-edit"></i> Modifier votre voyage</a>
        </div>
    </div>
</div>

<style>
    .trip-summary {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .summary-header {
        background-color: #FFFFFF;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .total-price {
        font-size: 1.3rem;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #E0DACA;
    }
    
    .summary-etapes {
        margin-top: 30px;
    }
    
    .etape-summary-card {
        background-color: #FFFFFF;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .options-summary {
        margin-top: 15px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .option-summary-item {
        margin-bottom: 12px;
    }
    
    .option-price {
        color: #C66B3D;
        font-size: 0.9em;
    }
    
    .summary-actions {
        margin-top: 30px;
        display: flex;
        justify-content: center;
        gap: 20px;
    }
    
    .btn-payment, .btn-modify {
        padding: 12px 24px;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-payment {
        background: linear-gradient(to right, #4CAF50, #45a049);
        color: white;
        border: none;
    }
    
    .btn-payment:hover {
        background: linear-gradient(to right, #45a049, #4CAF50);
    }
    
    .btn-modify {
        background: #1B262C;
        color: white;
        border: none;
    }
    
    .btn-modify:hover {
        background: #2E4750;
    }
    
    ul {
        margin: 5px 0 0 20px;
        padding: 0;
    }
    
    li {
        margin-bottom: 5px;
    }
</style>

<?php
include_once 'includes/footer.php';
?>