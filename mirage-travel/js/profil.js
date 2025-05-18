// init l'edition des champs du profil
function initialiserEditionProfil() {
    // select tous les boutons d'édition
    var boutonsEdition = document.querySelectorAll('.edit-button');
    
    boutonsEdition.forEach(function(bouton) {
        // add un gestionnaire devenements clic sur chaque bouton
        bouton.addEventListener('click', function() {
            // trouver le parent (form-group) du bouton
            var formGroup = bouton.closest('.form-group');
            if (!formGroup) return;
            
            // trouver le span qui contient la valeur
            var spanValeur = formGroup.querySelector('span');
            if (!spanValeur) return;
            
            // si le champ est déjà en mode edition, rien ne change
            if (formGroup.classList.contains('en-edition')) return;
            
            // marquer le groupe comme etant en edition
            formGroup.classList.add('en-edition');
            
            // save la valeur actuelle
            var valeurActuelle = spanValeur.textContent;
            spanValeur.setAttribute('data-valeur-originale', valeurActuelle);
            
            // creer un champ d'entrée pour l'édition
            var input = document.createElement('input');
            input.type = 'text';
            input.value = valeurActuelle;
            input.className = 'edit-input';
            
            // remplacer le span par l'input
            spanValeur.style.display = 'none';
            formGroup.insertBefore(input, spanValeur);
            
            // remplacer le bouton d'edition par des boutons de validation et d'annulation
            bouton.style.display = 'none';
            
            // creer un bouton de validation
            var boutonValider = document.createElement('button');
            boutonValider.className = 'validate-button';
            boutonValider.innerHTML = '<i class="fas fa-check"></i>';
            boutonValider.title = 'Valider';
            formGroup.appendChild(boutonValider);
            
            // creer un bouton d'annulation
            var boutonAnnuler = document.createElement('button');
            boutonAnnuler.className = 'cancel-button';
            boutonAnnuler.innerHTML = '<i class="fas fa-times"></i>';
            boutonAnnuler.title = 'Annuler';
            formGroup.appendChild(boutonAnnuler);
            
            // mettre le focus sur le champ d'entrée
            input.focus();
            
            // gestionnaire pour le bouton de validation
            boutonValider.addEventListener('click', function() {
                validerModification(formGroup, spanValeur, input);
            });
            
            // gestionnaire pour le bouton d'annulation
            boutonAnnuler.addEventListener('click', function() {
                annulerModification(formGroup, spanValeur);
            });
            
            // gestionnaire pour la touche Entrée et Echap
            input.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    validerModification(formGroup, spanValeur, input);
                } else if (event.key === 'Escape') {
                    annulerModification(formGroup, spanValeur);
                }
            });
        });
    });
}

// valider une modification
function validerModification(formGroup, spanValeur, input) {
    // Récupérer la nouvelle valeur
    var nouvelleValeur = input.value.trim();
    
    // maj l'affichage
    spanValeur.textContent = nouvelleValeur;
    spanValeur.style.display = '';
    
    // supp l'input et les boutons de validation/annulation
    formGroup.removeChild(input);
    
    var boutonValider = formGroup.querySelector('.validate-button');
    var boutonAnnuler = formGroup.querySelector('.cancel-button');
    
    if (boutonValider) formGroup.removeChild(boutonValider);
    if (boutonAnnuler) formGroup.removeChild(boutonAnnuler);
    
    // afficher a nouveau le bouton d'édition
    var boutonEdition = formGroup.querySelector('.edit-button');
    if (boutonEdition) boutonEdition.style.display = '';
    
    // retirer la classe d'editions
    formGroup.classList.remove('en-edition');
    
    // verif si la valeur a été modifiée
    var valeurOriginale = spanValeur.getAttribute('data-valeur-originale');
    if (nouvelleValeur !== valeurOriginale) {
        // marquer ce champ comme modifié
        formGroup.classList.add('modifie');
        
        // afficher le bouton de soumission si ce n'est pas déjà fait
        afficherBoutonSoumission();
    }
}

// annuler une modification
function annulerModification(formGroup, spanValeur) {
    // remettre l'affichage normal
    spanValeur.style.display = '';
    
    // supp l'input et les boutons de validation/annulation
    var input = formGroup.querySelector('.edit-input');
    if (input) formGroup.removeChild(input);
    
    var boutonValider = formGroup.querySelector('.validate-button');
    var boutonAnnuler = formGroup.querySelector('.cancel-button');
    
    if (boutonValider) formGroup.removeChild(boutonValider);
    if (boutonAnnuler) formGroup.removeChild(boutonAnnuler);
    
    // afficher à nouveau le bouton d'édition
    var boutonEdition = formGroup.querySelector('.edit-button');
    if (boutonEdition) boutonEdition.style.display = '';
    
    // enlever la classe d'édition
    formGroup.classList.remove('en-edition');
}

// afficher le bouton de soumission
function afficherBoutonSoumission() {
    // eske le bouton existe deja?
    if (document.getElementById('soumettre-profil')) return;
    
    // creer le bouton de soumission
    var boutonSoumettre = document.createElement('button');
    boutonSoumettre.id = 'soumettre-profil';
    boutonSoumettre.innerHTML = '<i class="fas fa-save"></i> Enregistrer les modifications';
    boutonSoumettre.className = 'btn-submit';
    
    // creer un formulaire pour soumettre les modifications
    var form = document.createElement('form');
    form.method = 'post';
    form.action = 'profile.php';
    
    // add un champ caché pour indiquer que c'est une mise à jour de profil
    var champCache = document.createElement('input');
    champCache.type = 'hidden';
    champCache.name = 'update_profile';
    champCache.value = '1';
    form.appendChild(champCache);
    
    // ajt des champs cachés pour chaque valeur modifiée
    var champsModifies = document.querySelectorAll('.form-group.modifie');
    champsModifies.forEach(function(champ) {
        var label = champ.querySelector('label');
        var span = champ.querySelector('span');
        
        if (label && span) {
            // extraure le nom du champ (en supprimant les espaces et caractères spéciaux)
            var nomChamp = label.textContent.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
            
            // creer un champ caché avec la nouvelle valeur
            var champDonnee = document.createElement('input');
            champDonnee.type = 'hidden';
            champDonnee.name = 'profile_' + nomChamp;
            champDonnee.value = span.textContent;
            form.appendChild(champDonnee);
        }
    });
    
    // add le bouton au formulaire et le formulaire à la page
    form.appendChild(boutonSoumettre);
    
    // trvouer l'endroit où ajouter le formulaire
    var sectionProfil = document.querySelector('.profile-section');
    if (sectionProfil) {
        sectionProfil.appendChild(form);
    }
}

// init la fonctionnalité d'edition du profil
window.addEventListener('DOMContentLoaded', function() {
    initialiserEditionProfil();
});