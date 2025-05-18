// theme de cookie existant ?
function verifierCookieTheme() {
    var nom = "theme=";
    var cookies = document.cookie.split(';');
    
    for(var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(nom) == 0) {
            var valeurTheme = cookie.substring(nom.length, cookie.length);
            chargerTheme(valeurTheme);
            return;
        }
    }
    
    // aucun cookie trouver on met le theme par defaut
    chargerTheme('defaut');
}

// def le cookie de theme
function definirCookieTheme(theme) {
    // cookie valide pour 30 jours
    var dateExpiration = new Date();
    dateExpiration.setTime(dateExpiration.getTime() + (30 * 24 * 60 * 60 * 1000));
    var expires = "expires=" + dateExpiration.toUTCString();
    
    document.cookie = "theme=" + theme + ";" + expires + ";path=/";
}

// changer le theme
function changerTheme() {
    var themeActuel = document.getElementById('theme-switcher').getAttribute('data-theme');
    var nouveauTheme;
    
    if (themeActuel === 'defaut') {
        nouveauTheme = 'sombre';
    } else if (themeActuel === 'sombre') {
        nouveauTheme = 'contraste';
    } else {
        nouveauTheme = 'defaut';
    }
    
    chargerTheme(nouveauTheme);
    definirCookieTheme(nouveauTheme);
}

// charger un theme spécifique
function chargerTheme(theme) {
    var lien = document.getElementById('theme-css');
    if (!lien) {
        lien = document.createElement('link');
        lien.id = 'theme-css';
        lien.rel = 'stylesheet';
        document.head.appendChild(lien);
    }
    
    var bouton = document.getElementById('theme-switcher');
    if (bouton) {
        bouton.setAttribute('data-theme', theme);
        
        // maj du texte du bouton selon le theme present
        if (theme === 'sombre') {
            bouton.innerHTML = '<i class="fas fa-moon"></i> Mode sombre';
        } else if (theme === 'contraste') {
            bouton.innerHTML = '<i class="fas fa-adjust"></i> Mode contrasté';
        } else {
            bouton.innerHTML = '<i class="fas fa-sun"></i> Mode clair';
        }
    }
    
    // charger le fichier CSS correspondant au thème
    if (theme === 'defaut') {
        lien.href = '';  // pas de fichier necessaire
    } else {
        lien.href = 'css/theme-' + theme + '.css';
    }
}

// init le bouton de changement de tjeme
function initialiserBoutonTheme() {
    // creer un bouton s'il n'existe pas déjà
    if (!document.getElementById('theme-switcher')) {
        var bouton = document.createElement('button');
        bouton.id = 'theme-switcher';
        bouton.className = 'theme-switcher-btn';
        bouton.innerHTML = '<i class="fas fa-sun"></i> Mode clair';
        bouton.setAttribute('data-theme', 'defaut');
        
        // add le bouton a la navigation
        var nav = document.querySelector('nav ul');
        if (nav) {
            var li = document.createElement('li');
            li.appendChild(bouton);
            nav.appendChild(li);
        }
        
        // Ajouter l'levenement de clic
        bouton.addEventListener('click', changerTheme);
    }
}

// exe au chargement de la page
window.onload = function() {
    initialiserBoutonTheme();
    verifierCookieTheme();
};