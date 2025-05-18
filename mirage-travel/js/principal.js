/**
 * en gros c ici ou tout les fichier js seront charger en fonction de la page actuelle
 */

// la fonction pr le chargement dynamique d'un script js
function chargerScript(url) {
    var script = document.createElement('script');
    script.src = url;
    document.head.appendChild(script);
}

// c quoi la page actuelle
function determinerPageActuelle() {
    var url = window.location.pathname;
    var fichier = url.substring(url.lastIndexOf('/') + 1);
    
    // Si on sait pas, on considere que c'est index.php pcq c la base (lacceuil)
    if (fichier === '') {
        fichier = 'index.php';
    }
    
    return fichier;
}

// charger les scripts en fonction de la page
function chargerScriptsPage() {
    // charger les scripts communs a toutes les pages
    chargerScript('js/theme.js');
    
    // determiner la page actuelle
    var pageActuelle = determinerPageActuelle();
    
    // load les scripts en fonction de la page
    switch (pageActuelle) {
        case 'index.php':
            // index page daccueil
            chargerScript('js/panier.js');
            break;
            
        case 'register.php':
            // inscription
            chargerScript('js/validation.js');
            break;
            
        case 'login.php':
            // connexion
            chargerScript('js/validation.js');
            break;
            
        case 'profile.php':
            // profil utilisateur
            chargerScript('js/profil.js');
            chargerScript('js/panier.js');
            break;
            
        case 'admin.php':
            // Page d'admin 
            chargerScript('js/admin.js');
            break;
            
        case 'search.php':
            // recherche de voyages
            chargerScript('js/recherche.js');
            chargerScript('js/panier.js');
            break;
            
        case 'trip-details.php':
            // détails d'un voyage
            chargerScript('js/panier.js');
            break;
            
        case 'trip-customize.php':
            // personnalisation d'un voyage
            chargerScript('js/voyages.js');
            break;
            
        case 'trip-summary.php':
            // récapitulatif d'un voyage
            chargerScript('js/voyages.js');
            break;
            
        case 'payment.php':
            //paiement
            chargerScript('js/validation.js');
            break;
            
        default:
            // Autres pages
            chargerScript('js/panier.js');
            break;
    }
}

// exe le chargement des scripts au chargement de la page
document.addEventListener('DOMContentLoaded', chargerScriptsPage);