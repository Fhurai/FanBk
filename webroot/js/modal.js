document.addEventListener("DOMContentLoaded", function(){
    openModal();
    closeModal();
});

/**
 * Méthode d'ouverture de la modale
 */
function openModal(){
    // Pour tous les boutons d'ouverture de modal
    document.querySelectorAll("[aria-modal='open']").forEach((btn) => {
        
        // Le bouton a dorénavant un curseur en forme de pointeur.
        btn.style.cursor = "pointer";

        // Ajout de l'event du click sur le bouton.
        btn.addEventListener("click", function(){
            // Affichage de la modale.
            document.querySelector("div[aria-modal='modal'][accessKey='"+btn.accessKey+"']").style.display = "block";
        });
    });
}

/**
 * Méthode de fermeture de la modale.
 */
function closeModal(){
    // Pour tous les boutons de fermetures
    document.querySelectorAll("[aria-modal='close']").forEach((btn) => {

        // Ajout de l'event du click sur le bouton
        btn.addEventListener("click", function(){
            // Disparition de la modale.
            document.querySelector("div[aria-modal='modal'][accessKey='"+btn.accessKey+"']").style.display = "none";
        });
    });
    
    // Click sur la fenêtre.
    window.addEventListener("click", function(event){
        if (event.target == document.querySelector("div[aria-modal='modal'][style]")) {
            document.querySelector("div[aria-modal='modal'][style]").style.display = "none";
        }
    });
}