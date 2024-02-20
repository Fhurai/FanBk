var preform = "";

/**
 * Ajout de l'évènement de chargement du DOM de la page.
 */
document.addEventListener("DOMContentLoaded", function () {

    if (window.location.pathname === "/fanfictions") preform = "filters-fields-";
    
    if(window.location.pathname.split("/").length === 4){
        personnagesByFandoms();
        relationsByFandoms();
    }

    // Ajout de l'évènement de changement des fandoms de la fanfiction.
    document.querySelector("#" + preform + "fandoms").addEventListener("change", function () {
        personnagesByFandoms();
        relationsByFandoms();
    });
    checkExists();
});

/**
 * Méthode pour définir les personnages par les fandoms sélectionnés
 */
function personnagesByFandoms() {
    // Nettoyage des options sélectionnées
    document.querySelector("#" + preform + "personnages").value = '';

    // Récupération du tableau des fandoms sélectionnés.
    let fandoms = Array.from(document.querySelector("#" + preform + "fandoms").selectedOptions).map((option) => option.value);

    if (fandoms.length > 0) {// Si au moins un fandom sélectionné

        // Parcours des personnages disponibles dans le sélecteur.
        Array.from(document.querySelector("#" + preform + "personnages").options).forEach((option) => {

            // Si l'option n'a pas le fandom désiré, elle est cachée. Sinon elle est affichée.
            if (!fandoms.includes(option.dataset.fandom)) option.style.display = "none";
            else option.style.display = "block";
        });
    } else {//Aucun fandom sélectionné.

        //Tous les personnages sont affichés.
        Array.from(document.querySelector("#" + preform + "personnages").options).forEach((option) => {
            option.style.display = "block";
        });
    }
}

/**
 * Méthode pour définir les relations par les fandoms sélectionnés
 */
function relationsByFandoms() {
    // Nettoyage des options sélectionnées
    document.querySelector("#" + preform + "relations").value = '';

    // Récupération du tableau des fandoms sélectionnés.
    let fandoms = Array.from(document.querySelector("#" + preform + "fandoms").selectedOptions).map((option) => option.value);

    if (fandoms.length > 0) {// Si au moins un fandom sélectionné
        // Parcours des relations disponibles dans le sélecteur.
        Array.from(document.querySelector("#" + preform + "relations").options).forEach((option) => {
            // Option cachée par défaut.
            option.style.display = "none";

            // Si l'option a au moins un fandom parmi ceux désirés, l'option est affichée.
            JSON.parse(option.dataset.fandoms).forEach((fandom) => {
                if (fandoms.includes(JSON.stringify(fandom))) option.style.display = "block";
            });
        });
    } else {//Aucun fandom sélectionné.

        //Toutes les relations sont affichées.
        Array.from(document.querySelector("#" + preform + "relations").options).forEach((option) => {
            option.style.display = "block";
        });
    }
}

/**
 * Méthode qui check si une valeur entrée dans un input de création n'est pas déjà dans un sélecteur associé.
 */
function checkExists() {
    document.querySelectorAll("input[placeholder]").forEach((input) => {
        input.addEventListener("focusout", function () {
            let newValue = input.value;
            let options = input.parentElement.previousElementSibling.querySelectorAll("select option");
            options.forEach((option) => {
                if (option.textContent === newValue) {
                    option.selected = "selected";
                    console.log(input.name);
                    if (input.name === "fandoms-new") {
                        personnagesByFandoms();
                        relationsByFandoms();
                    }
                    input.value = "";
                    input.parentElement.parentElement.classList.replace("allow-new-open", "allow-new-closed");
                }
            });
        });
    });
}