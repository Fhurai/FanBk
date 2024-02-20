/**
 * Chargement de tous les evements liés au descriptif.
 */
document.addEventListener("DOMContentLoaded", function(){
    // Events liés au descriptif de fanfiction.
    menu();
});

/**
 * Events liés au menu de descriptif.
 */
function menu(){
    let openContextualMenu = null;
    /**
     * Ouverture du menu contextuel.
     */
    document.querySelectorAll(".descriptif .clickable").forEach((button) => {
        button.addEventListener("click", function(event){
            event.currentTarget.parentElement.nextElementSibling.querySelector(".contextual-menu").classList.add("open");
        });
    });

    /**
     * Au click sur la page.
     */
    document.body.addEventListener("click", function(event){
        // Récupération du menu contextuel ouvert s'il ne l'est pas déjà.
        if(openContextualMenu === null) openContextualMenu = document.querySelector(".contextual-menu.open");

        if(openContextualMenu !== null)
        {
            // Si le click est en dehors du menu contextuel ouvert.
            if(event.target !== openContextualMenu && !openContextualMenu.contains(event.target)){
                // Si le click n'est pas dans le parent OU si le click n'est pas un bouton clickable
                if(!openContextualMenu.parentElement.parentElement.contains(event.target) || !event.target.classList.contains("clickable")){

                    // Fermeture du menu contextuel et vidage du menu à comparer.
                    openContextualMenu.classList.remove("open");
                    openContextualMenu = null;
                }
            }
        }
    });
}