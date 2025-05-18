<?php
include_once 'includes/functions.php';
include_once 'includes/session.php';

requireLogin();

// verif l'id de voyage 
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// recup l'id de voyage 
$tripId = intval($_GET['id']);

// recup les details de voyage 
$trip = getTripById($tripId);

// redirect si erreur de voyage
if (!$trip) {
    header("Location: index.php");
    exit();
}

// Initialiser les options choisies (par défaut)
$chosenOptions = array();
foreach ($trip['etapes'] as $index => $etape) {
    $chosenOptions[$index] = array();
    
    //hebergement par def
    foreach ($etape['options']['hebergement'] as $hebergement) {
        if ($hebergement['defaut']) {
            $chosenOptions[$index]['hebergement'] = $hebergement['nom'];
            break;
        }
    }
    
    // restau par def
    foreach ($etape['options']['restauration'] as $restauration) {
        if ($restauration['defaut']) {
            $chosenOptions[$index]['restauration'] = $restauration['nom'];
            break;
        }
    }
    
    // activité par def
    $chosenOptions[$index]['activites'] = array();
    if (isset($etape['options']) && isset($etape['options']['activites']) && is_array($etape['options']['activites'])) {
    $chosenOptions[$index]['activites'] = array();
    foreach ($etape['options']['activites'] as $activite) {
        if (isset($activite['defaut']) && $activite['defaut']) {
            $chosenOptions[$index]['activites'][] = $activite['nom'];
        }
    }
} else {
    // init avec tab vide si pas dactiviter
    $chosenOptions[$index]['activites'] = array();
}
    
    // transport par def (si ya)
    if (isset($etape['options']['transport'])) {
        foreach ($etape['options']['transport'] as $transport) {
            if ($transport['defaut']) {
                $chosenOptions[$index]['transport'] = $transport['nom'];
                break;
            }
        }
    }
}

// nb de gens (par défaut , 1)
$nbPersonnes = 1;

// formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nb_personnes'])) {
        $nbPersonnes = max(1, intval($_POST['nb_personnes']));
    }
    
    // différentes options
    foreach ($trip['etapes'] as $index => $etape) {
        // hébergement
        if (isset($_POST["hebergement_$index"])) {
            $chosenOptions[$index]['hebergement'] = $_POST["hebergement_$index"];
        }
        
        // restauration
        if (isset($_POST["restauration_$index"])) {
            $chosenOptions[$index]['restauration'] = $_POST["restauration_$index"];
        }
        
        // activités
        $chosenOptions[$index]['activites'] = array();
        foreach ($etape['options']['activites'] as $activite) {
            $activiteKey = "activite_{$index}_{$activite['nom']}";
            if (isset($_POST[$activiteKey]) && $_POST[$activiteKey] === 'on') {
                $chosenOptions[$index]['activites'][] = $activite['nom'];
            }
        }
        
        // transport (si disponible)
        if (isset($etape['options']['transport']) && isset($_POST["transport_$index"])) {
            $chosenOptions[$index]['transport'] = $_POST["transport_$index"];
        }
    }
    
    // stock les options que l'user voulait pdt la page recap
    $_SESSION['trip_customize'] = array(
        'trip_id' => $tripId,
        'options' => $chosenOptions,
        'nb_personnes' => $nbPersonnes
    );
    
    // redirect page recap  (summary en anglais)
    header("Location: trip-summary.php");
    exit();
}

// titre
$pageTitle = "Personnalisation du voyage : " . $trip['titre'];

include_once 'includes/header.php';
?>

<div class="container">
    <div class="trip-customize">
        <h2>Personnalisez votre voyage : <?php echo $trip['titre']; ?></h2>
        <p class="trip-destination"><i class="fas fa-map-marker-alt"></i> <?php echo $trip['destination']; ?></p>
        
        <form method="POST" action="trip-customize.php?id=<?php echo $tripId; ?>">
            <div class="form-group">
                <label for="nb_personnes"><i class="fas fa-users"></i> Nombre de personnes :</label>
                <input type="number" id="nb_personnes" name="nb_personnes" min="1" max="10" value="<?php echo $nbPersonnes; ?>">
            </div>
            
            <div class="etapes-container">
                <h3><i class="fas fa-route"></i> Étapes du voyage</h3>
                
                <?php foreach ($trip['etapes'] as $index => $etape): ?>
                <div class="etape-customize-card">
                    <h4>Jour <?php echo $etape['jour']; ?> : <?php echo $etape['titre']; ?></h4>
                    <p><i class="fas fa-map-pin"></i> <?php echo $etape['lieu']; ?></p>
                    <p><?php echo $etape['description']; ?></p>
                    <!--ici il y aura touttes les différentes options pour le voyage, lis bien lestitres pour savoir de quoi il s'agit 
 -->
                    
                    <div class="options-container">
                        <div class="option-section">
                            <h5><i class="fas fa-bed"></i> Hébergement</h5>
                            <?php foreach ($etape['options']['hebergement'] as $hebergement): ?>
                            <div class="option-item">
                                <input type="radio" id="hebergement_<?php echo $index; ?>_<?php echo $hebergement['nom']; ?>" 
                                       name="hebergement_<?php echo $index; ?>" 
                                       value="<?php echo $hebergement['nom']; ?>"
                                       <?php echo ($chosenOptions[$index]['hebergement'] === $hebergement['nom']) ? 'checked' : ''; ?>>
                                <label for="hebergement_<?php echo $index; ?>_<?php echo $hebergement['nom']; ?>">
                                    <?php echo $hebergement['nom']; ?>
                                    <?php if ($hebergement['prix'] > 0): ?>
                                        <span class="option-price">(+<?php echo $hebergement['prix']; ?>€/pers.)</span>
                                    <?php endif; ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="option-section">
                            <h5><i class="fas fa-utensils"></i> Restauration</h5>
                            <?php foreach ($etape['options']['restauration'] as $restauration): ?>
                            <div class="option-item">
                                <input type="radio" id="restauration_<?php echo $index; ?>_<?php echo $restauration['nom']; ?>" 
                                       name="restauration_<?php echo $index; ?>" 
                                       value="<?php echo $restauration['nom']; ?>"
                                       <?php echo ($chosenOptions[$index]['restauration'] === $restauration['nom']) ? 'checked' : ''; ?>>
                                <label for="restauration_<?php echo $index; ?>_<?php echo $restauration['nom']; ?>">
                                    <?php echo $restauration['nom']; ?>
                                    <?php if ($restauration['prix'] > 0): ?>
                                        <span class="option-price">(+<?php echo $restauration['prix']; ?>€/pers.)</span>
                                    <?php endif; ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="option-section">
                            <h5><i class="fas fa-hiking"></i> Activités</h5>
                            <?php
// Vérification basique avant le foreach
if (isset($etape['options']) && isset($etape['options']['activites'])) {
    foreach ($etape['options']['activites'] as $activite) {
        // Code existant à l'intérieur de la boucle
?>
    <div class="option-item">
        <input type="checkbox" id="activite_<?php echo $index; ?>_<?php echo $activite['nom']; ?>" 
               name="activite_<?php echo $index; ?>_<?php echo $activite['nom']; ?>"
               <?php 
               // Vérification avant d'utiliser in_array
               if (isset($chosenOptions[$index]) && isset($chosenOptions[$index]['activites'])) {
                   echo in_array($activite['nom'], $chosenOptions[$index]['activites']) ? 'checked' : '';
               }
               ?>>
        <label for="activite_<?php echo $index; ?>_<?php echo $activite['nom']; ?>">
            <?php echo $activite['nom']; ?>
            <?php 
            // Vérification du prix
            if (isset($activite['prix']) && $activite['prix'] > 0) {
                echo '<span class="option-price">(+' . $activite['prix'] . '€/pers.)</span>';
            }
            ?>
        </label>
    </div>
<?php
    } // Fin du foreach
} else {
    // Message si aucune activité n'est disponible
    echo '<p>Aucune activité disponible pour cette étape.</p>';
}
?>
                        </div>
                        
                        
                        <?php if (isset($etape['options']['transport'])): ?>
                        <div class="option-section">
                            <h5><i class="fas fa-car"></i> Transport</h5>
                            <?php foreach ($etape['options']['transport'] as $transport): ?>
                            <div class="option-item">
                                <input type="radio" id="transport_<?php echo $index; ?>_<?php echo $transport['nom']; ?>" 
                                       name="transport_<?php echo $index; ?>" 
                                       value="<?php echo $transport['nom']; ?>"
                                       <?php echo (isset($chosenOptions[$index]['transport']) && $chosenOptions[$index]['transport'] === $transport['nom']) ? 'checked' : ''; ?>>
                                <label for="transport_<?php echo $index; ?>_<?php echo $transport['nom']; ?>">
                                    <?php echo $transport['nom']; ?>
                                    <?php if ($transport['prix'] > 0): ?>
                                        <span class="option-price">(+<?php echo $transport['prix']; ?>€)</span>
                                    <?php endif; ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-check"></i> Valider la personnalisation</button>
                <a href="trip-details.php?id=<?php echo $tripId; ?>" class="btn-cancel"><i class="fas fa-times"></i> Annuler</a>
            </div>
        </form>
    </div>
</div>



<?php

include_once 'includes/footer.php';
?>