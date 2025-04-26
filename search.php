<?php
// titre de chaques pages
$pageTitle = "Rechercher un voyage";

// ici il prend les fonctions qu'on a fais la derniere fois
include_once 'includes/functions.php';

// recherche en fonction du voyage 
$results = [];
$searched = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searched = true;
    $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
    $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
    $dateDepart = isset($_GET['date-depart']) ? $_GET['date-depart'] : '';
    $duree = isset($_GET['duree']) ? $_GET['duree'] : '';
    $budget = isset($_GET['budget']) ? $_GET['budget'] : '';
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    
    $results = searchTrips($keywords, $destination, $dateDepart, $duree, $budget, $type);
} else {
    // teaser des voyages popu 
    $results = getTrips(3);
}

// header
include_once 'includes/header.php';
?>

<div class="container">
    <h2 class="text-center mb-5">Trouvez l'aventure de vos rêves</h2>
    
    <div class="destinations-preview">
        <img src="images/image5.jpg" alt="Sahara" class="destination-image">
        <img src="images/image6.jpg" alt="Désert de Gobi" class="destination-image">
        <img src="images/image7.jpg" alt="Désert d'Atacama" class="destination-image">
    </div>

    <div class="form-container">
        <h3 class="text-center mb-5">Filtrer les voyages</h3>
        
        <form method="GET" action="search.php">
            <input type="hidden" name="search" value="1">
            
            <div class="form-group">
                <label for="keywords"><i class="fas fa-search"></i> Mots-clés :</label>
                <input type="text" id="keywords" name="keywords" placeholder="Ex: Sahara, aventure, astronomie...">
            </div>
            
            <div class="form-group">
                <label for="destination"><i class="fas fa-map-marker-alt"></i> Destination :</label>
                <select id="destination" name="destination">
                    <option value="">Toutes les destinations</option>
                    <option value="Maroc">Maroc</option>
                    <option value="Tunisie">Tunisie</option>
                    <option value="Jordanie">Jordanie</option>
                    <option value="Chili">Chili</option>
                    <option value="Namibie">Namibie</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="date-depart"><i class="fas fa-calendar-alt"></i> Date de départ :</label>
                <input type="date" id="date-depart" name="date-depart">
            </div>

            <div class="form-group">
                <label for="duree"><i class="fas fa-clock"></i> Durée :</label>
                <select id="duree" name="duree">
                    <option value="">Toutes les durées</option>
                    <option value="3-5">3-5 jours</option>
                    <option value="6-10">6-10 jours</option>
                    <option value="11-15">11-15 jours</option>
                    <option value="Plus de 15 jours">Plus de 15 jours</option>
                </select>
            </div>

            <div class="form-group">
                <label for="budget"><i class="fas fa-euro-sign"></i> Budget maximum :</label>
                <input type="range" id="budget" name="budget" min="500" max="5000" step="100" value="2000">
                <div class="budget-range-labels">
                    <span>500€</span>
                    <span id="budget-value">2000€</span>
                    <span>5000€</span>
                </div>
            </div>

            <div class="form-group">
                <label for="type"><i class="fas fa-hiking"></i> Type de voyage :</label>
                <select id="type" name="type">
                    <option value="">Tous les types</option>
                    <option value="Aventure">Aventure</option>
                    <option value="Culturel">Culturel</option>
                    <option value="Photographique">Photographique</option>
                    <option value="Bien-être">Bien-être</option>
                    <option value="Astronomie">Astronomie</option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
            </div>
        </form>
    </div>
    
    <div class="featured-trips mt-5">
        <h2 class="text-center grid-full-width">
            <?php echo $searched ? 'Résultats de la recherche' : 'Voyages populaires'; ?>
        </h2>
        
        <?php if (empty($results)): ?>
            <p class="text-center">Aucun voyage trouvé. Veuillez modifier vos critères de recherche.</p>
        <?php else: ?>
            <?php foreach ($results as $trip): ?>
            <div class="trip-card">
                <img src="images/<?php echo $trip['image']; ?>" alt="<?php echo $trip['titre']; ?>" class="trip-image">
                <h3><?php echo $trip['titre']; ?></h3>
                <p><i class="fas fa-map-marker-alt"></i> <?php echo $trip['destination']; ?></p>
                <p><i class="fas fa-calendar-alt"></i> <?php echo $trip['duree']; ?> jours</p>
                <p><i class="fas fa-euro-sign"></i> À partir de <?php echo $trip['prix_base']; ?>€</p>
                <a href="trip-details.php?id=<?php echo $trip['id']; ?>"><button><i class="fas fa-info-circle"></i> Détails</button></a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
// inverse du header
include_once 'includes/footer.php';
?>