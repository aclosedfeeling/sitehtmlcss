// init le calcul dynamique du prix
function initialiserCalculPrix() {
    // verif si on est sur la page de personnalisation d'un voyage
    var pagePersonnalisation = document.querySelector('.trip-customize');
    if (!pagePersonnalisation) return;
    
    // recup le prix de base du voyage
    var prixBase = recupererPrixBase();
    
    // creer un élément pour afficher le prix estimé
    creerAffichagePrix(prixBase);
    
    // add des ecouteurs d'événements pour les options
    ajouterEcouteursOptions();
}

// recup le prix de base du voyage
function recupererPrixBase() {
    // essayer de trouver le prix de base dans la page
    var elementPrix = document.querySelector('.trip-header .fa-euro-sign');
    if (elementPrix) {
        var texte = elementPrix.parentNode.textContent;
        var match = texte.match(/\d+(\.\d+)?/);
        if (match) {
            return parseFloat(match[0]);
        }
    }
    
    // valeur par def si le prix n'est pas trouvé
    return 0;
}

// creer un élément pour afficher le prix estimé
function creerAffichagePrix(prixBase) {
    var nbPersonnes = document.getElementById('nb_personnes').value || 1;
    var prixTotal = prixBase * nbPersonnes;
    
    var divPrix = document.createElement('div');
    divPrix.id = 'prix-estime';
    divPrix.className = 'prix-estime';
    divPrix.innerHTML = '<h3><i class="fas fa-euro-sign"></i> Prix estimé</h3>' +
                        '<p class="total-price">Prix de base: <span id="prix-base">' + prixBase + '</span>€</p>' +
                        '<p>Nombre de personnes: <span id="nb-personnes">' + nbPersonnes + '</span></p>' +
                        '<p class="supplements">Suppléments: <span id="prix-supplements">0</span>€</p>' +
                        '<p class="prix-total">Total: <span id="prix-total">' + prixTotal + '</span>€</p>';
    
    var actionsDiv = document.querySelector('.form-actions');
    if (actionsDiv) {
        actionsDiv.parentNode.insertBefore(divPrix, actionsDiv);
    } else {
        var form = document.querySelector('form');
        if (form) {
            form.appendChild(divPrix);
        }
    }
}

// add des écouteurs d'événements pour les options
function ajouterEcouteursOptions() {
    // Écouteur pour le nombre de personnes
    var inputNbPersonnes = document.getElementById('nb_personnes');
    if (inputNbPersonnes) {
        inputNbPersonnes.addEventListener('change', calculerPrixTotal);
    }
    
    // ecouteur pour les options d'hébergement
    var optionsHebergement = document.querySelectorAll('input[name^="hebergement_"]');
    optionsHebergement.forEach(function(option) {
        option.addEventListener('change', calculerPrixTotal);
    });
    
    // ecouteur pour les options de restauration
    var optionsRestauration = document.querySelectorAll('input[name^="restauration_"]');
    optionsRestauration.forEach(function(option) {
        option.addEventListener('change', calculerPrixTotal);
    });
    
    // ecouteur pour les options d'activités
    var optionsActivites = document.querySelectorAll('input[name^="activite_"]');
    optionsActivites.forEach(function(option) {
        option.addEventListener('change', calculerPrixTotal);
    });
    
    // ecouteur pour les options de transport
    var optionsTransport = document.querySelectorAll('input[name^="transport_"]');
    optionsTransport.forEach(function(option) {
        option.addEventListener('change', calculerPrixTotal);
    });
}

// calc le prix total
function calculerPrixTotal() {
    var prixBase = parseFloat(document.getElementById('prix-base').textContent);
    var nbPersonnes = parseInt(document.getElementById('nb_personnes').value) || 1;
    
    // maj l'affichage du nombre de personnes
    document.getElementById('nb-personnes').textContent = nbPersonnes;
    
    // calc les suppléments pour l'hébergement
    var supplementsHebergement = calculerSupplementsHebergement(nbPersonnes);
    
    // calc les suppléments pour la restauration
    var supplementsRestauration = calculerSupplementsRestauration(nbPersonnes);
    
    // calc les suppléments pour les activités
    var supplementsActivites = calculerSupplementsActivites(nbPersonnes);
    
    // calc les suppléments pour le transport
    var supplementsTransport = calculerSupplementsTransport();
    
    // calc le total des suppléments
    var totalSupplements = supplementsHebergement + supplementsRestauration + supplementsActivites + supplementsTransport;
    document.getElementById('prix-supplements').textContent = totalSupplements.toFixed(2);
    
    // calc et afficher le prix total
    var prixTotal = (prixBase * nbPersonnes) + totalSupplements;
    document.getElementById('prix-total').textContent = prixTotal.toFixed(2);
}

// cacl les suppléments pour l'hébergement
function calculerSupplementsHebergement(nbPersonnes) {
    var total = 0;
    var optionsHebergement = document.querySelectorAll('input[name^="hebergement_"]:checked');
    
    optionsHebergement.forEach(function(option) {
        var label = document.querySelector('label[for="' + option.id + '"]');
        if (label) {
            var prixMatch = label.textContent.match(/\+(\d+(\.\d+)?)€\/pers\./);
            if (prixMatch) {
                total += parseFloat(prixMatch[1]) * nbPersonnes;
            }
        }
    });
    
    return total;
}

// calc les suppléments pour la restauration
function calculerSupplementsRestauration(nbPersonnes) {
    var total = 0;
    var optionsRestauration = document.querySelectorAll('input[name^="restauration_"]:checked');
    
    optionsRestauration.forEach(function(option) {
        var label = document.querySelector('label[for="' + option.id + '"]');
        if (label) {
            var prixMatch = label.textContent.match(/\+(\d+(\.\d+)?)€\/pers\./);
            if (prixMatch) {
                total += parseFloat(prixMatch[1]) * nbPersonnes;
            }
        }
    });
    
    return total;
}

// calc les suppléments pour les activités
function calculerSupplementsActivites(nbPersonnes) {
    var total = 0;
    var optionsActivites = document.querySelectorAll('input[name^="activite_"]:checked');
    
    optionsActivites.forEach(function(option) {
        var label = document.querySelector('label[for="' + option.id + '"]');
        if (label) {
            var prixMatch = label.textContent.match(/\+(\d+(\.\d+)?)€\/pers\./);
            if (prixMatch) {
                total += parseFloat(prixMatch[1]) * nbPersonnes;
            }
        }
    });
    
    return total;
}

// calc les suppléments pour le transport
function calculerSupplementsTransport() {
    var total = 0;
    var optionsTransport = document.querySelectorAll('input[name^="transport_"]:checked');
    
    optionsTransport.forEach(function(option) {
        var label = document.querySelector('label[for="' + option.id + '"]');
        if (label) {
            var prixMatch = label.textContent.match(/\+(\d+(\.\d+)?)€/);
            if (prixMatch) {
                total += parseFloat(prixMatch[1]);
            }
        }
    });
    
    return total;
}

// init les fonctionnalités liées aux voyages
window.addEventListener('DOMContentLoaded', function() {
    initialiserCalculPrix();
});