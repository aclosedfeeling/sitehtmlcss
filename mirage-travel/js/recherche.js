/**
 * Recherche et tri des voyages
 */

// Initialiser le tri des résultats de recherche
function initialiserTriRecherche() {
    // Vérifier si on est sur une page de résultats de recherche
    var resultatRecherche = document.querySelector('.featured-trips');
    if (!resultatRecherche) return;
    
    // Créer les boutons de tri
    creerBoutonsTri();
    
    // Activer le tri des résultats
    activerTriResultats();
}

// Créer les boutons de tri
function creerBoutonsTri() {
    // Créer un conteneur pour les options de tri
    var optionsTri = document.createElement('div');
    optionsTri.className = 'options-tri';
    optionsTri.innerHTML = '<span>Trier par : </span>';
    
    // Créer les différents boutons de tri
    var options = [
        { id: 'prix', texte: 'Prix', icone: 'fas fa-euro-sign' },
        { id: 'duree', texte: 'Durée', icone: 'fas fa-clock' },
        { id: 'date', texte: 'Date', icone: 'fas fa-calendar-alt' }
    ];
    
    options.forEach(function(option) {
        var bouton = document.createElement('button');
        bouton.id = 'tri-' + option.id;
        bouton.className = 'bouton-tri';
        bouton.setAttribute('data-tri', option.id);
        bouton.setAttribute('data-ordre', 'asc'); // Par défaut: ordre ascendant
        bouton.innerHTML = '<i class="' + option.icone + '"></i> ' + option.texte + ' <i class="fas fa-sort"></i>';
        
        optionsTri.appendChild(bouton);
    });
    
    // add le conteneur des options de tri avant les résultats
    var container = document.querySelector('.container');
    var featuredTrips = document.querySelector('.featured-trips');
    if (container && featuredTrips) {
        container.insertBefore(optionsTri, featuredTrips);
    }
}

// activer le tri des résultats
function activerTriResultats() {
    var boutonsTri = document.querySelectorAll('.bouton-tri');
    
    boutonsTri.forEach(function(bouton) {
        bouton.addEventListener('click', function() {
            // recup le critère de tri et l'ordre actuel
            var critere = bouton.getAttribute('data-tri');
            var ordre = bouton.getAttribute('data-ordre');
            
            // inverser l'ordre pour le prochain clic
            var nouvelOrdre = ordre === 'asc' ? 'desc' : 'asc';
            bouton.setAttribute('data-ordre', nouvelOrdre);
            
            // maj l'icône pour indiquer l'ordre
            var iconeOrdre = bouton.querySelector('.fa-sort, .fa-sort-up, .fa-sort-down');
            if (iconeOrdre) {
                iconeOrdre.className = nouvelOrdre === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
            }
            
            // Trier les résultats
            trierResultats(critere, nouvelOrdre);
            
            // Réinitialiser les autres boutons
            boutonsTri.forEach(function(autreBouton) {
                if (autreBouton !== bouton) {
                    autreBouton.setAttribute('data-ordre', 'asc');
                    var autreIcone = autreBouton.querySelector('.fa-sort, .fa-sort-up, .fa-sort-down');
                    if (autreIcone) {
                        autreIcone.className = 'fas fa-sort';
                    }
                }
            });
        });
    });
}

// Trier les résultats selon un critère et un ordre
function trierResultats(critere, ordre) {
    var cartes = Array.from(document.querySelectorAll('.trip-card'));
    var parent = document.querySelector('.featured-trips');
    
    if (!parent || cartes.length === 0) return;
    
    // Fonction de comparaison selon le critère
    var comparer = function(a, b) {
        var valeurA, valeurB;
        
        if (critere === 'prix') {
            // Extraire le prix (nombre après le €)
            valeurA = parseFloat(a.querySelector('.fa-euro-sign').parentNode.textContent.match(/\d+(\.\d+)?/)[0]);
            valeurB = parseFloat(b.querySelector('.fa-euro-sign').parentNode.textContent.match(/\d+(\.\d+)?/)[0]);
        } 
        else if (critere === 'duree') {
            // Extraire la durée (nombre avant "jours")
            valeurA = parseInt(a.querySelector('.fa-calendar-alt').parentNode.textContent.match(/\d+/)[0]);
            valeurB = parseInt(b.querySelector('.fa-calendar-alt').parentNode.textContent.match(/\d+/)[0]);
        } 
        else if (critere === 'date') {
            // Si les dates sont disponibles, les comparer
            // Sinon, utiliser le titre comme critère de secours
            var dateElementA = a.querySelector('.fa-calendar-alt').parentNode;
            var dateElementB = b.querySelector('.fa-calendar-alt').parentNode;
            
            if (dateElementA && dateElementB) {
                valeurA = dateElementA.textContent;
                valeurB = dateElementB.textContent;
            } else {
                valeurA = a.querySelector('h3').textContent;
                valeurB = b.querySelector('h3').textContent;
            }
        } 
        else {
            // Par défaut, trier par titre
            valeurA = a.querySelector('h3').textContent;
            valeurB = b.querySelector('h3').textContent;
        }
        
        // Appliquer l'ordre de tri
        if (ordre === 'asc') {
            return valeurA > valeurB ? 1 : -1;
        } else {
            return valeurA < valeurB ? 1 : -1;
        }
    };
    
    // Trier les cartes
    cartes.sort(comparer);
    
    // Réorganiser les cartes dans le DOM
    cartes.forEach(function(carte) {
        parent.appendChild(carte);
    });
}

// maj l'affichage du prix dans le slider de budget
function initialiserAffichageBudget() {
    var sliderBudget = document.getElementById('budget');
    var affichageBudget = document.getElementById('budget-value');
    
    if (sliderBudget && affichageBudget) {
        // maj l'affichage à l'initialisation
        affichageBudget.textContent = sliderBudget.value + '€';
        
        // maj l'affichage lors des changements
        sliderBudget.addEventListener('input', function() {
            affichageBudget.textContent = sliderBudget.value + '€';
        });
    }
}

// init les fonctionnaliter de recherche
window.addEventListener('DOMContentLoaded', function() {
    initialiserTriRecherche();
    initialiserAffichageBudget();
});