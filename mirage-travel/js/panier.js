// init la gestion du panier
function initialiserPanier() {
    // SUPPRESSION de la vérif utilisateur connecté
    
    // creer l'icône du panier dans la navigation
    creerIconePanier();
    
    // add les fctnalite pour ajouter/retirer du panier
    initialiserBoutonsAjoutPanier();
    
    // init l'affichage du panier
    initialiserAffichagePanier();
}

// creer l'icône du panier dans la navigation
function creerIconePanier() {
    // verif si l'icône existe déjà
    if (document.getElementById('panier-icon')) return;
    
    // creer un élément li pour le panier
    var li = document.createElement('li');
    
    // creer un lien pour le panier
    var lien = document.createElement('a');
    lien.href = '#';
    lien.id = 'panier-icon';
    lien.innerHTML = '<i class="fas fa-shopping-cart"></i> Panier <span class="panier-count">0</span>';
    
    // add un gestionnaire d'événement pour afficher/masquer le panier
    lien.addEventListener('click', function(event) {
        event.preventDefault();
        toggleAffichagePanier();
    });
    
    // add le lien au li, et le li a la navigation
    li.appendChild(lien);
    var nav = document.querySelector('nav ul');
    if (nav) {
        nav.appendChild(li);
    }
}

// init les boutons pour ajouter au panier
function initialiserBoutonsAjoutPanier() {
    // sur la page de détails d'un voyage
    var boutonPersonnaliser = document.querySelector('.btn-customize');
    if (boutonPersonnaliser) {
        // recup l'ID du voyage
        var url = boutonPersonnaliser.getAttribute('href');
        var match = url.match(/id=(\d+)/);
        if (match) {
            var voyageId = match[1];
            
            // creer un bouton pour ajouter au panier
            var boutonAjouter = document.createElement('button');
            boutonAjouter.className = 'btn-add-to-cart';
            boutonAjouter.innerHTML = '<i class="fas fa-cart-plus"></i> Ajouter au panier';
            boutonAjouter.setAttribute('data-id', voyageId);
            
            // add un gestionnaire d'devenemens pour ajouter au panier
            boutonAjouter.addEventListener('click', function() {
                ajouterAuPanier(voyageId);
            });
            
            // add le bouton après le bouton de perso 
            boutonPersonnaliser.parentNode.appendChild(boutonAjouter);
        }
    }
    
    // sur la page des result de recherche
    var boutonsDetail = document.querySelectorAll('.trip-card a');
    boutonsDetail.forEach(function(bouton) {
        var url = bouton.getAttribute('href');
        var match = url.match(/id=(\d+)/);
        if (match) {
            var voyageId = match[1];
            var carte = bouton.closest('.trip-card');
            
            // bouton existe deja ?
            if (carte && !carte.querySelector('.btn-add-to-cart')) {
                // creer un bouton pour ajouter au panier
                var boutonAjouter = document.createElement('button');
                boutonAjouter.className = 'btn-add-to-cart';
                boutonAjouter.innerHTML = '<i class="fas fa-cart-plus"></i> Ajouter au panier';
                boutonAjouter.setAttribute('data-id', voyageId);
                
                // add un gestionnaire d'evenements pour ajouter au panier
                boutonAjouter.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    ajouterAuPanier(voyageId);
                });
                
                // add le bouton apres le bouton de detail 
                carte.appendChild(boutonAjouter);
            }
        }
    });
}

// init l'affichage du panier
function initialiserAffichagePanier() {
    // creer le conteneur du panier s'il n'existe pas deja
    if (!document.getElementById('panier-container')) {
        var panierContainer = document.createElement('div');
        panierContainer.id = 'panier-container';
        panierContainer.className = 'panier-container';
        panierContainer.style.display = 'none';
        
        // add le conteneur a la page
        document.body.appendChild(panierContainer);
        
        // maj l'affichage du panier
        mettreAJourAffichagePanier();
    }
}

// afficher/masquer le panier
function toggleAffichagePanier() {
    var panierContainer = document.getElementById('panier-container');
    if (panierContainer) {
        if (panierContainer.style.display === 'none') {
            panierContainer.style.display = 'block';
            mettreAJourAffichagePanier();
        } else {
            panierContainer.style.display = 'none';
        }
    }
}

// maj l'affichage du panier
function mettreAJourAffichagePanier() {
    var panierContainer = document.getElementById('panier-container');
    if (!panierContainer) return;
    
    // recup les voyages dans le panier
    var panier = recupererPanier();
    
    // maj le compteur du panier
    var panierCount = document.querySelector('.panier-count');
    if (panierCount) {
        panierCount.textContent = panier.length;
    }
    
    // si le panier vides
    if (panier.length === 0) {
        panierContainer.innerHTML = '<h3>Votre panier</h3><p>Votre panier est vide.</p>';
        return;
    }
    
    // construire le contenu du panier
    var contenu = '<h3>Votre panier</h3><ul class="panier-liste">';
    
    panier.forEach(function(voyage) {
        contenu += '<li class="panier-item" data-id="' + voyage.id + '">' +
                   '<span class="panier-item-titre">' + voyage.titre + '</span>' +
                   '<span class="panier-item-prix">' + voyage.prix + '€</span>' +
                   '<button class="panier-item-supprimer" data-id="' + voyage.id + '">' +
                   '<i class="fas fa-trash"></i></button></li>';
    });
    
    contenu += '</ul>';
    
    // add un bouton pour passer à la caisse
    contenu += '<div class="panier-actions">' +
               '<a href="trip-details.php?id=' + panier[0].id + '" class="btn-checkout">' +
               '<i class="fas fa-credit-card"></i> Passer à la caisse</a></div>';
    
    // maj le contenu du panier
    panierContainer.innerHTML = contenu;
    
    // add des gestionnaires d'événements pour les boutons de suppression
    var boutonsSuppression = document.querySelectorAll('.panier-item-supprimer');
    boutonsSuppression.forEach(function(bouton) {
        bouton.addEventListener('click', function() {
            var voyageId = bouton.getAttribute('data-id');
            retirerDuPanier(voyageId);
        });
    });
}

// recup le panier depuis le local storage (?)
function recupererPanier() {
    var panierJSON = localStorage.getItem('mirage_travel_panier');
    return panierJSON ? JSON.parse(panierJSON) : [];
}

// save le panier dans le stockage local
function sauvegarderPanier(panier) {
    localStorage.setItem('mirage_travel_panier', JSON.stringify(panier));
}

// add un voyage au panier
function ajouterAuPanier(voyageId) {
    // recup le panier deja fais 
    var panier = recupererPanier();
    
    // verif si le voyage est deja dedans
    for (var i = 0; i < panier.length; i++) {
        if (panier[i].id === voyageId) {
            alert('Ce voyage est déjà dans votre panier.');
            return;
        }
    }
    
    // recup les informations du voyage depuis la page
    var titre = '';
    var prix = 0;
    
    if (document.querySelector('.trip-header h2')) {
        titre = document.querySelector('.trip-header h2').textContent;
    } else {
        // sinon on cherche dans les cartes de voyage 
        var carte = document.querySelector('.trip-card[data-id="' + voyageId + '"]');
        if (carte) {
            var titreElement = carte.querySelector('h3');
            if (titreElement) {
                titre = titreElement.textContent;
            }
        }
    }
    
    if (document.querySelector('.trip-header .fa-euro-sign')) {
        var texte = document.querySelector('.trip-header .fa-euro-sign').parentNode.textContent;
        var match = texte.match(/\d+(\.\d+)?/);
        if (match) {
            prix = parseFloat(match[0]);
        }
    }
    
    // valeur pas defaut si ya pas de titre
    if (!titre) {
        titre = 'Voyage #' + voyageId;
    }
    
    // add le voyage au panier
    panier.push({
        id: voyageId,
        titre: titre,
        prix: prix
    });
    
    // save le panier
    sauvegarderPanier(panier);
    
    // maj l'affichage du panier
    mettreAJourAffichagePanier();
    
    // afficher un message de confirmation
    alert('Voyage ajouté au panier !');
}

// retirer un voyage du panier
function retirerDuPanier(voyageId) {
    // recup le panier actuel
    var panier = recupererPanier();
    
    // filtrer le panier pour retirer le voyage
    panier = panier.filter(function(voyage) {
        return voyage.id !== voyageId;
    });
    
    // save le panier
    sauvegarderPanier(panier);
    
    // maj l'affichage du panier
    mettreAJourAffichagePanier();
}

// init les fctionnaliter du panier
initialiserPanier();