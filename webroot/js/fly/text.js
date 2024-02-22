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
 * @param {Element} container 
 */
const activateFlyText = (container) => {

    // Une touche du clavier est tapé dans l'input du champ texte.
    container.querySelector("input").addEventListener("input", function (event) {
        checkInput(container);
    });

    checkInput(container);
}

/**
 * 
 * @param {*} input 
 * @returns 
 */
const matchPattern = (input) => {
    return input.value.match(new RegExp(input.pattern));
}

/**
 * 
 * @param {*} container 
 */
const checkInput = (container) => {

    let input = container.querySelector("input");
    let textor = container.querySelector(".textor");
    let alert = container.querySelector(".alert");

    if (input.value !== "") {
        textor.classList.add("valid");

        if (input.hasAttribute("pattern")) {
            if (matchPattern(input)) {
                alert.dataset.content = "Matching pattern";
            } else {
                alert.dataset.content = "Pattern not matching";
                textor.classList.remove("valid");
            }
        }
    } else {
        alert.dataset.content = "";
        textor.classList.remove("valid");
    }
}