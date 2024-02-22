/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Si la page contient au moins un champ texte FLY.
    if (hasFlyText())

        // Parcours de tous les champ texte FLY pour les activer.
        Array.from(document.querySelectorAll(".flytext"))
            .forEach(activateFlyText);
});

/**
 * Retourne si la page contient au moins un champ texte FLY
 * @returns {boolean} true si un champ texte FLY existe, sinon false.
 */
const hasFlyText = () => document.querySelectorAll(".flytext").length > 0;

/**
 * Méthode qui active toutes les gestions d'évènements pour l'élément container.
 * @param {Element} container L'élément Flytext
 */
const activateFlyText = (container) => {

    // Une touche du clavier est tapé dans l'input du champ texte.
    container.querySelector("input").addEventListener("input", function (event) {
        checkInput(container);
    });

    // Vérification que le contenu par défaut de l'input correspond au pattern.
    checkInput(container);
}

/**
 * Méthode qui retourne si le contenu de l'input match le pattern demandé par celui-ci.
 * @param {Element} input 
 * @returns true si le pattern est matché, sinon false.
 */
const matchPattern = (input) => input.value.match(new RegExp(input.pattern));

/**
 * Méthode qui vérifie si le contenu de l'input ou non, selon qu'il y a un pattern ou non.
 * @param {Element} container L'élément Flytext
 */
const checkInput = (container) => {

    // Setup des variables pour les éléments utilisés plusieurs fois.
    let input = container.querySelector("input");
    let textor = container.querySelector(".textor");
    let alert = container.querySelector(".alert");

    // Si le champ n'est pas vide.
    if (input.value !== "") {
        // Directement indiquer que le champ est bon.
        textor.classList.add("valid");

        // Le champ a un pattern.
        if (input.hasAttribute("pattern")) {
            
            // Est ce que le contenu correspond au pattern ?
            if (matchPattern(input))
                // Oui, on alerte l'utilisateur.
                alert.dataset.content = "Matching pattern";
            else {
                //Non, on alerte l'utilisateur et la classe de validation est retirée.
                alert.dataset.content = "Pattern not matching";
                textor.classList.remove("valid");
            }
        }
    } else {
        //Champ vide, suppression de l'alerte sur le contenu et suppression de la classe de validation.
        alert.dataset.content = "";
        textor.classList.remove("valid");
    }
}