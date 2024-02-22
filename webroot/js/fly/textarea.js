/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Si la page contient au moins un champ texte FLY.
    if (hasFlyText())

        // Parcours de tous les champ texte FLY pour les activer.
        Array.from(document.querySelectorAll(".flytextarea"))
            .forEach(activateFlyTextarea);
});

/**
 * Retourne si la page contient au moins un champ texte FLY
 * @returns {boolean} true si un champ texte FLY existe, sinon false.
 */
const hasFlyTextarea = () => document.querySelectorAll(".flytextarea").length > 0;

/**
 * Méthode qui active toutes les gestions d'évènements pour l'élément container.
 * @param {Element} container 
 */
const activateFlyTextarea = (container) => {

    // Une touche du clavier est tapé dans l'input du champ texte.
    container.querySelector("textarea").addEventListener("input", function (event) {
        checkkTextarea(container);
    });

    // Vérification que le contenu par défaut de l'input correspond au pattern.
    checkkTextarea(container);
}

/**
 * Méthode qui vérifie si le contenu de l'input ou non, selon qu'il y a un pattern ou non.
 * @param {Element} container L'élément Flytextarea
 */
const checkkTextarea = (container) => {

    // Setup de la variable pour l'élément utilisé plusieurs fois.
    let textor = container.querySelector(".textor");

    // Si le champ n'est pas vide.
    if (container.querySelector("textarea").value !== "")
        // Directement indiquer que le champ est bon.
        textor.classList.add("valid");
    else
        //Champ vide, suppression de la classe de validation.
        textor.classList.remove("valid");
}