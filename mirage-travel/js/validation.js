// init le tri des results de recherche
function initialiserTriRecherche() {
    // verif si on est sur une page de result de recherche
    var resultatRecherche = document.querySelector('.featured-trips');
    if (!resultatRecherche) return;
    
    // creer les boutons de tri
    creerBoutonsTri();
    
    // activer le tri des résultats
    activerTriResultats();
}

// creer les boutons de tri
function creerBoutonsTri() {
    // creer un conteneur pr les options de tri
    var optionsTri = document.createElement('div');
    optionsTri.className = 'options-tri';
    optionsTri.innerHTML = '<span>Trier par : </span>';
    
    // creer les diff boutons de tri
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
        bouton.setAttribute('data-ordre', 'asc'); // ordre ascendant par defaut 
        bouton.innerHTML = '<i class="' + option.icone + '"></i> ' + option.texte + ' <i class="fas fa-sort"></i>';
        
        optionsTri.appendChild(bouton);
    });
    
    // add le conteneur des options de tri avant les results
    var container = document.querySelector('.container');
    var featuredTrips = document.querySelector('.featured-trips');
    if (container && featuredTrips) {
        container.insertBefore(optionsTri, featuredTrips);
    }
}

// Activer le tri des résultats
function activerTriResultats() {
    var boutonsTri = document.querySelectorAll('.bouton-tri');
    
    boutonsTri.forEach(function(bouton) {
        bouton.addEventListener('click', function() {
            // Récupérer le critère de tri et l'ordre actuel
            var critere = bouton.getAttribute('data-tri');
            var ordre = bouton.getAttribute('data-ordre');
            
            // Inverser l'ordre pour le prochain clic
            var nouvelOrdre = ordre === 'asc' ? 'desc' : 'asc';
            bouton.setAttribute('data-ordre', nouvelOrdre);
            
            // maj l'icone pour indiquer lordre
            var iconeOrdre = bouton.querySelector('.fa-sort, .fa-sort-up, .fa-sort-down');
            if (iconeOrdre) {
                iconeOrdre.className = nouvelOrdre === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
            }
            
            // trier les résultats
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

// trier les result selon un critere et un ordre
function trierResultats(critere, ordre) {
    var cartes = Array.from(document.querySelectorAll('.trip-card'));
    var parent = document.querySelector('.featured-trips');
    
    if (!parent || cartes.length === 0) return;
    
    // fonction de comparaison selon cqui est demander
    var comparer = function(a, b) {
        var valeurA, valeurB;
        
        if (critere === 'prix') {
            // exctract le prix (nombre apre le €)
            valeurA = parseFloat(a.querySelector('.fa-euro-sign').parentNode.textContent.match(/\d+(\.\d+)?/)[0]);
            valeurB = parseFloat(b.querySelector('.fa-euro-sign').parentNode.textContent.match(/\d+(\.\d+)?/)[0]);
        } 
        else if (critere === 'duree') {
            // extraire la durée (nombre avt "jours")
            valeurA = parseInt(a.querySelector('.fa-calendar-alt').parentNode.textContent.match(/\d+/)[0]);
            valeurB = parseInt(b.querySelector('.fa-calendar-alt').parentNode.textContent.match(/\d+/)[0]);
        } 
        else if (critere === 'date') {
            // si les dates sont disponibles, les comparer
            // sinn, utiliser le titre comme critere de secours
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
            // par defaut, trier par titre
            valeurA = a.querySelector('h3').textContent;
            valeurB = b.querySelector('h3').textContent;
        }
        
        // aplly l'ordre de tri
        if (ordre === 'asc') {
            return valeurA > valeurB ? 1 : -1;
        } else {
            return valeurA < valeurB ? 1 : -1;
        }
    };
    
    // trier les cartes
    cartes.sort(comparer);
    
    // reorganiser les cartes dans le DOM
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

// init les fctnalité de recherche
window.addEventListener('DOMContentLoaded', function() {
    initialiserTriRecherche();
    initialiserAffichageBudget();
});