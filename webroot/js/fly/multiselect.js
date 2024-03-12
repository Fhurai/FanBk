/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {
    // Si la page contient au moins un selecteur multiple FLY.
    if (hasFlyMultiSelect()) {

        // Parcours de tous les sélecteurs multiple FLY pour les activer.
        Array.from(document.querySelectorAll(".flymultiselect"))
            .forEach(activateFlyMultiSelect);
    }
});

/**
 * Retourne si la page contient au moins un sélecteur FLY
 * @returns {boolean} true si un selecteur multiple FLY existe, sinon false.
 */
const hasFlyMultiSelect = () => document.querySelectorAll(".flymultiselect").length > 0;

/**
 * Méthode qui active toutes les gestions d'évènements pour l'élément container.
 * @param {Element} container 
 */
const activateFlyMultiSelect = (container) => {

    // Au click sur la fenêtre.
    window.addEventListener("click", function (event) {

        // Gestion du click en dehors du composant.
        clickOutsideMultiple(event, container);
    });

    // Focus dans l'input du champ de recherche.
    container.querySelector(".selector input").addEventListener("focusin", function (event) {

        // Affichage de la liste.
        showListMultiple(container);
    });

    // Une touche du clavier est tapé dans l'input du champ de recherche.
    container.querySelector(".selector input").addEventListener("input", function (event) {

        // Recherche des options disponibles avec le contenu de la barre de recherche.
        container.querySelectorAll("li")
            .forEach((li) => findOptionMultiple(event, container, li));
    });

    //Pour l'ensemble des options de la liste de recherche.
    container.querySelectorAll("li.option").forEach((li) => {

        // Click sur une option.
        li.addEventListener("click", function (event) {

            // Choix de l'option relevé.
            clickOptionMultiple(event, container);
        });
    });
}

/**
 * Méthode pour réagir au click en dehors du composant.
 * @param {Event} e L'évènement click
 * @param {Element} container Le composant.
 */
const clickOutsideMultiple = (e, container) => { if (!container.contains(e.target)) hideListMultiple(container); };

/**
 * Méthode pour cacher la liste d'options.
 * @param {Element} container Le composant.
 */
const hideListMultiple = (container) => { container.querySelector("ul").classList.remove("visible") };

/**
 * Méthode pour afficher la liste d'options.
 * @param {Element} container Le composant.
 */
const showListMultiple = (container) => { container.querySelector("ul").classList.add("visible") };

/**
 * Méthode qui enlève les attributs de validation du champ de recherche.
 * @param {Element} container Le composant.
 */
const clickOptionMultiple = (e, container) => {
    // Récupération des informations de l'option sous forme d'un tableau.
    let option = e.currentTarget.id.split("_");

    // Si aucune option n'a été selectionné pour le sélecteur multiple.
    if (container.querySelectorAll(".display input[value='" + option[3] + "']").length === 0) {

        // Ajout de l'option cliquée dans le container d'affichage.
        ajoutDisplay(container, e.currentTarget.innerText, option);

        // Ajout de la classe de validation sur le selecteur multiple.
        container.querySelector(".flymultiselect .choices").classList.add("valid");

        // L'option cliquée est cachée pour éviter la redondance.
        e.currentTarget.classList.add("chosen");
    }
}

/**
 * 
 * @param {Element} container Le composant.
 * @param {*} libelle 
 * @param {array} option Tableau d'informations d'une option
 */
const ajoutDisplay = (container, libelle, option) => {
    // Si l'input dummy est présent dans le container d'affichage, il est supprimé.
    if (container.querySelectorAll(".choices > input[name='" + option[2] + "[]']").length > 0)
        container.querySelector(".choices > input[name='" + option[2] + "[]']").remove();

    // Création de l'option à afficher dans le conteneur d'affichage.
    let div = document.createElement("div");
    div.classList = "option";
    div.dataset.tooltip = "Unselect \"" + libelle + "\"";
    div.innerHTML = libelle + `<input name="` + option[2] + `[]" value=` + option[3] + `  required/>`;

    // Le bouton de suppression de l'option est cliqué
    div.addEventListener("click", function (event) {

        // Suppression de l'option affichée.
        removeOption(event, container, option);
    });

    // Ajout de l'option au conteneur d'affichage.
    container.querySelector(".choices .display").appendChild(div);
}

/**
 * Recherche si l'élément de la liste correspond au champ de recherche.
 * @param {Event} e Evenement d'écriture dans la barre de recherche.
 * @param {Element} container Le composant.
 * @param {Element} li Element de la liste.
 */
const findOptionMultiple = (e, container, li) => {
    // Récupération des informations de l'option sous forme de tableau.
    let option = li.id.split("_");

    // Construction de l'expression régulière à utiliser pour la comparaison.
    let regex = new RegExp("(?:.*\\b(" + e.currentTarget.value.toLowerCase().split(" ").join("))(?:.*\\b(") + "))", "gi");

    // Si l'option est un choix simple (sans groupe)
    if (!li.hasAttribute("optgroup") && li.classList.contains("option")) {

        // Vérification que l'option n'inclut pas le contenu du champ, auquel cas l'option est cachée.
        if (!regex.test(li.innerText.toLowerCase()))
            li.style.display = "none";
        else
            li.style.display = "";
    }

    // Si l'option est un choix dans un groupe d'options.
    if (li.hasAttribute("optgroup")) {

        // Si l'option est un choix dans un groupe et qu'il n'inclut pas le contenu du champ, elle est cachée.
        if (!li.getAttribute("optgroup").toLowerCase().includes(e.currentTarget.value.toLowerCase()) && !regex.test(li.innerText.toLowerCase()))
            li.style.display = "none";
        else {

            // l'option contient le contenu du champ, elle est affichée et son groupe aussi.
            li.style.display = "";
            if (container.querySelectorAll(`ul li.optgroup`).length > 0) container.querySelector(`ul li.optgroup[optgroup="` + li.getAttribute("optgroup") + `"]`).style.display = "";
        }

        // Si l'option est un groupe.
    } else if (li.classList.contains("optgroup")) {

        // Si le label du groupe ne contient pas le contenu du champ de recherche, il est caché.
        if (!regex.test(li.innerText.toLowerCase()))
            li.style.display = "none";
        else
            li.style.display = "";
    }
}

/**
 * Méthode pour supprimer une option cliquée.
 * @param {Event} e L'évènement 
 * @param {Element} container Le composant.
 * @param {array} option Tableau d'informations d'une option
 */
const removeOption = (e, container, option) => {

    // Récupération de l'option à supprimer.
    let displayedItem = e.currentTarget;

    // Suppression de l'option.
    displayedItem.remove();

    // Réaffichage de l'option dans liste des options.
    container.querySelector(".selector ul li.option#" + option.join("_")).classList.remove("chosen");

    // Si le nombre d'options à afficher dans le conteneur est 0
    if (container.querySelector(".choices .display").childElementCount === 0) {

        // Plus d'option donc plus de validation.
        container.querySelector(".choices").classList.remove("valid")

        // Création d'un nouveau input dummy pour le requiert.
        let dummyInput = document.createElement("input");
        dummyInput.name = option[2] + "[]";
        dummyInput.required = "required";

        // Ajout de l'input dummy dans la partie choix multiple.
        container.querySelector(".choices").appendChild(dummyInput);
    };
}

/**
 * Méthode à utiliser pour set une valeur de base dans le sélecteur
 * @param {string} name 
 * @param {string} value 
 */
const setClickOptionMultiple = (name, value) => {
    document.querySelector(".flymultiselect .select .selector ul li#flymultiselect_option_" + name + "_" + value).click();
}