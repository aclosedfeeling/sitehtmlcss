<?php
include_once 'includes/functions.php';
include_once 'includes/session.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$tripId = intval($_GET['id']);

$trip = getTripById($tripId);

if (!$trip) {
    header("Location: index.php");
    exit();
}

$pageTitle = $trip['titre'];


include_once 'includes/header.php';
?>

<div class="container">
    <div class="trip-details">
        <img src="images/<?php echo $trip['image']; ?>" alt="<?php echo $trip['titre']; ?>" class="hero-image">
        
        <div class="trip-header">
            <h2><?php echo $trip['titre']; ?></h2>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo $trip['destination']; ?></p>
            <p><i class="fas fa-calendar-alt"></i> Durée : <?php echo $trip['duree']; ?> jours</p>
            <p><i class="fas fa-euro-sign"></i> À partir de <?php echo $trip['prix_base']; ?>€ par personne</p>
        </div>
        
        <div class="trip-description">
            <h3><i class="fas fa-info-circle"></i> Description</h3>
            <p><?php echo $trip['description']; ?></p>
        </div>
        
        <div class="trip-etapes">
            <h3><i class="fas fa-route"></i> Étapes du voyage</h3>
            
            <?php foreach ($trip['etapes'] as $index => $etape): ?>
            <div class="etape-card">
                <h4>Jour <?php echo $etape['jour']; ?> : <?php echo $etape['titre']; ?></h4>
                <p><i class="fas fa-map-pin"></i> <?php echo $etape['lieu']; ?></p>
                <p><?php echo $etape['description']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="trip-actions">
            <?php if (isLoggedIn()): ?>
                <a href="trip-customize.php?id=<?php echo $trip['id']; ?>">
                    <button class="btn-customize"><i class="fas fa-cog"></i> Personnaliser ce voyage</button>
                </a>
            <?php else: ?>
                <p>Connectez-vous pour personnaliser et réserver ce voyage.</p>
                <a href="login.php">
                    <button><i class="fas fa-sign-in-alt"></i> Se connecter</button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// footer
include_once 'includes/footer.php';
?>