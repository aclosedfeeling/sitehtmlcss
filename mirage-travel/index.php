<?php
// titre
$pageTitle = "Accueil";

// demarage de session
include_once 'includes/functions.php';

// voyage popu (que 3)
$featuredTrips = getTrips(3);

// header
include_once 'includes/header.php';
?>

<div class="container">
    <img src="images/image1.jpg" alt="Vue panoramique du désert" class="hero-image">
    
    <section>
        <h2 class="text-center mb-5">Bienvenue au cœur du désert</h2>
        <p>Chez Mirage Travel, nous vous proposons des aventures extraordinaires au cœur des plus beaux déserts du monde. Que vous rêviez d'admirer les étoiles du Sahara, de découvrir les paysages lunaires du désert d'Atacama ou d'explorer les vastes étendues du désert de Gobi, nos circuits soigneusement élaborés vous permettront de vivre une expérience inoubliable.</p>
    </section>
    
    <section class="featured-trips">
        <h2 class="text-center grid-full-width">Nos voyages à la une</h2>
        
        <?php foreach ($featuredTrips as $trip): ?>
        <div class="trip-card">
            <img src="images/<?php echo $trip['image']; ?>" alt="<?php echo $trip['titre']; ?>" class="trip-image">
            <h3><?php echo $trip['titre']; ?></h3>
            <p><?php echo $trip['description']; ?></p>
            <a href="trip-details.php?id=<?php echo $trip['id']; ?>"><button><i class="fas fa-info-circle"></i> En savoir plus</button></a>
        </div>
        <?php endforeach; ?>
    </section>
</div>

<?php
// linverse du header
include_once 'includes/footer.php';
?>