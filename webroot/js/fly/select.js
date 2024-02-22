/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Si la page contient au moins un selecteur FLY.
    if (hasFlySelect()) {
        
        // Parcours de tous les sélecteurs FLY pour les activer.
        Array.from(document.querySelectorAll(".flyselect"))
            .forEach(activateFlySelect);
    }
});

/**
 * Retourne si la page contient au moins un sélecteur FLY
 * @returns {boolean} true si un selecteur FLY existe, sinon false.
 */
const hasFlySelect = () => document.querySelectorAll(".flyselect").length > 0;

/**
 * Méthode qui active toutes les gestions d'évènements pour l'élément container.
 * @param {Element} container 
 */
const activateFlySelect = (container) => {
    // Au click sur la fenêtre.
    window.addEventListener("click", function (event) {

        // Gestion du click en dehors du composant.
        clickOutside(event, container);
    });

    // Focus dans l'input du champ de recherche.
    container.querySelector("input").addEventListener("focusin", function (event) {

        // Affichage de la liste.
        showList(container);
    });

    // Une touche du clavier est tapé dans l'input du champ de recherche.
    container.querySelector("input").addEventListener("input", function (event) {
        
        // Désactivation de la précedente option.
        refuseOption(container);

        // Recherche des options disponibles avec le contenu de la barre de recherche.
        container.querySelectorAll("li")
            .forEach((li) => findOption(event, container, li));
    });

    //Pour l'ensemble des options de la liste de recherche.
    container.querySelectorAll("li.option").forEach((li) => {

        // Click sur une option.
        li.addEventListener("click", function (event) {

            // Choix de l'option relevé.
            clickOption(event, container)
        });
    });
}

/**
 * Méthode pour réagir au click en dehors du composant.
 * @param {Event} e L'évènement click
 * @param {Element} container Le composant.
 */
const clickOutside = (e, container) => { if (!container.contains(e.target)) hideList(container); };

/**
 * Méthode pour cacher la liste d'options.
 * @param {Element} container Le composant.
 */
const hideList = (container) => { container.querySelector("ul").classList.remove("visible") };

/**
 * Méthode pour afficher la liste d'options.
 * @param {Element} container Le composant.
 */
const showList = (container) => { container.querySelector("ul").classList.add("visible") };

/**
 * Méthode pour valider une option
 * @param {Element} container Le composant.
 * @param {array} option Tableau d'informations d'une option
 */
const validateOption = (container, option) => {
    // Si l'option est bien pour une select FLY.
    if (option[0] === "flyselect") {

        // Récupération de l'option choisi dans le champ de recherche.
        let selectedOption = container.querySelector("select[name='" + option[2] + "'] option[value='" + option[3] + "']");

        // L'option choisi est valorisé comme choisi dans le sélecteur.
        selectedOption.setAttribute("selected", "selected");

        // Le label de l'option choisi est placé dans le champ de recherche.
        container.querySelector(".input input")
            .value = selectedOption.innerText;

        // Ajout de la classe montrant que le choix est valide.
        container.querySelector(".input")
            .parentElement
            .classList.add("valid");
    }
};

/**
 * Méthode qui enlève les attributs de validation du champ de recherche.
 * @param {Element} container Le composant.
 */
const refuseOption = (container) => {
    // La classe de validation est enlevée.
    container.querySelector(".input")
        .parentElement
        .classList.remove("valid");

    // Toutes les options ont la valorisation de choix enlevée.
    container.querySelectorAll("select option")
        .forEach((option) => option.removeAttribute("selected"));
};

/**
 * Méthode pour gérer le click sur une option.
 * @param {Event} e Evenement click
 * @param {Element} container Le composant.
 */
const clickOption = (e, container) => {

    // Récupération des informations de l'option sous forme d'un tableau.
    let option = e.currentTarget.id.split("_");

    // Validation de l'option cliquée.
    validateOption(container, option);

    // Cache de la liste des options.
    hideList(container);
}

/**
 * Recherche si l'élément de la liste correspond au champ de recherche.
 * @param {Event} e Evenement d'écriture dans la barre de recherche.
 * @param {Element} container Le composant.
 * @param {Element} li Element de la liste.
 */
const findOption = (e, container, li) => {

    // Récupération des informations de l'option sous forme de tableau.
    let option = li.id.split("_");

    // Si l'option est un choix simple (sans groupe)
    if (!li.hasAttribute("optgroup") && li.classList.contains("option")) {

        // Vérification que l'option n'inclut pas le contenu du champ, auquel cas l'option est cachée.
        if (!li.innerText.toLowerCase().includes(e.currentTarget.value.toLowerCase()))
            li.style.display = "none";
        else
            li.style.display = "";
    }

    // Si l'option est un choix dans un groupe d'options.
    if (li.hasAttribute("optgroup")) {

        // Si l'option est un choix dans un groupe et qu'il n'inclut pas le contenu du champ, elle est cachée.
        if (!li.getAttribute("optgroup").toLowerCase().includes(e.currentTarget.value.toLowerCase()) && !li.innerText.toLowerCase().includes(e.currentTarget.value.toLowerCase()))
            li.style.display = "none";
        else {
            
            // l'option contient le contenu du champ, elle est affichée et son groupe aussi.
            li.style.display = "";
            container.querySelector(`ul li.optgroup[optgroup="` + li.getAttribute("optgroup") + `"]`).style.display = "";
        }

        // Si l'option est un groupe.
    } else if (li.classList.contains("optgroup")) {

        // Si le label du groupe ne contient pas le contenu du champ de recherche, il est caché.
        if (!li.innerText.toLowerCase().includes(e.currentTarget.value.toLowerCase()))
            li.style.display = "none";
        else
            li.style.display = "";
    }


    // Si le contenu du champ de recherche correspond parfaitement à une option
    if (li.innerText.toLowerCase() === e.currentTarget.value.toLowerCase()) {
        
        // Validation d'une option trouvée avec le libellé complet.
        validateOption(container, option);

        // Liste cachée.
        hideList(container);

        // Le focus sur le champ de recherche est perdu.
        if (li.classList.contains("option")) e.currentTarget.blur();
    }
}

/**
 * Méthode à utiliser pour set une valeur de base dans le sélecteur
 * @param {string} name 
 * @param {string} value 
 */
const setClickOption = (name, value) => {
    document.querySelector(".flyselect .select .selector ul li#flyselect_option_" + name + "_" + value).click();
}