document.addEventListener("DOMContentLoaded", function(){
    initialize();
});

function initialize(){
    // Vérification que des champs allow-new existent
    if(document.querySelectorAll(".allow-new-closed > div:first-child > label").length > 0){

        // Pour chaque champ allow-new, on va chercher son label
        document.querySelectorAll(".allow-new-closed > div:first-child > label").forEach((label) => {

            // Au click sur le label allow-new
            label.addEventListener("click", function(event){
                // Changement de la classe du container allow-new pour afficher (ou non) le champ d'ajout
                event.currentTarget.parentElement.parentElement.classList.toggle("allow-new-closed");
                event.currentTarget.parentElement.parentElement.classList.toggle("allow-new-open");
                // Valeur mise à null 
                event.currentTarget.parentElement.parentElement.querySelector(".input.text > input").value = null;
            });
        });
    }
}