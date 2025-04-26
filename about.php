<?php

$pageTitle = "À propos de Mirage Travel";

include_once 'includes/header.php';
?>

<div class="container">
    <section>
        <h2 class="text-center">Notre mission</h2>
        <img src="images/image4.jpg" alt="Notre équipe dans le désert" class="about-image">
        
        <div class="profile-section">
            <h3><i class="fas fa-compass"></i> Qui sommes-nous ?</h3>
            <p>Mirage Travel est votre spécialiste des voyages dans le désert depuis 2015. Fondée par des passionnés d'aventure et de grands espaces, notre agence propose des circuits uniques pour découvrir la beauté et la sérénité des paysages désertiques.</p>
            
            <h3><i class="fas fa-heart"></i> Notre passion</h3>
            <p>Notre passion pour les déserts nous pousse à explorer continuellement de nouveaux horizons pour vous offrir des expériences inédites. Nous sommes convaincus que ces espaces immenses et préservés offrent parmi les plus belles expériences de voyage.</p>
            
            <h3><i class="fas fa-leaf"></i> Notre engagement</h3>
            <p>Nous nous engageons à proposer des voyages responsables, respectueux des populations locales et des écosystèmes fragiles. Tous nos circuits sont conçus en partenariat avec des acteurs locaux et visent à minimiser notre impact environnemental.</p>
        </div>
    </section>

    <section>
        <h2 class="text-center mt-5">Nos destinations</h2>
        <div class="featured-trips">
            <?php
            // charger les voyages
            include_once 'includes/functions.php';
            $destinations = getTrips();
            
            // afficher au max 3 destinations
            $count = 0;
            foreach ($destinations as $trip) {
                if ($count >= 3) break;
                $count++;
            ?>
            <div class="trip-card">
                <img src="images/<?php echo $trip['image']; ?>" alt="<?php echo $trip['titre']; ?>" class="trip-image">
                <h3><?php echo $trip['titre']; ?></h3>
                <p><?php echo substr($trip['description'], 0, 120); ?>...</p>
                <a href="trip-details.php?id=<?php echo $trip['id']; ?>"><button><i class="fas fa-info-circle"></i> En savoir plus</button></a>
            </div>
            <?php } ?>
        </div>
    </section>

    <section>
        <h2 class="text-center mt-5">Recherche rapide</h2>
        <div class="form-container">
            <form method="GET" action="search.php">
                <input type="hidden" name="search" value="1">
                <div class="form-group">
                    <label for="quick-search"><i class="fas fa-search"></i> Rechercher un voyage :</label>
                    <input type="text" id="quick-search" name="keywords" placeholder="Ex: Sahara, Camping, 7 jours...">
                </div>
                <div class="text-center">
                    <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
                </div>
            </form>
        </div>
    </section>
    
    <section>
        <h2 class="text-center mt-5">Témoignages de nos clients</h2>
        <div class="testimonials">
            <div class="testimonial-card">
                <div class="testimonial-avatar">
                    <img src="images/image8.jpg" alt="Avatar">
                </div>
                <div class="testimonial-content">
                    <h4>Jean Dupont</h4>
                    <p class="testimonial-trip">Traversée du Sahara</p>
                    <p>"Une expérience inoubliable ! Les guides étaient exceptionnels et les paysages à couper le souffle. Je recommande vivement Mirage Travel pour découvrir le désert comme jamais."</p>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-avatar">
                    <img src="images/image9.jpg" alt="Avatar">
                </div>
                <div class="testimonial-content">
                    <h4>Marie Martin</h4>
                    <p class="testimonial-trip">Nuit sous les étoiles</p>
                    <p>"La nuit passée dans le désert à observer les étoiles restera gravée dans ma mémoire. L'organisation était parfaite, et l'astronome qui nous accompagnait était passionnant. Merci Mirage Travel !"</p>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<?php
include_once 'includes/footer.php';
?>