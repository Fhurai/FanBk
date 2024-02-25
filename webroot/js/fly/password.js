/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {
    // Si la page contient au moins un champ password FLY.
    if (hasFlyPassword())

        // Parcours de tous les champ texte FLY pour les activer.
        Array.from(document.querySelectorAll(".flypassword"))
            .forEach(activateFlyPassword);
});

/**
 * Retourne si la page contient au moins un champ password FLY
 * @returns {boolean} true si un champ password FLY existe, sinon false.
 */
const hasFlyPassword = () => document.querySelectorAll(".flypassword").length > 0;

/**
 * Méthode qui active toutes les gestions d'évènements pour l'élément container.
 * @param {Element} container L'élément Flytext
 */
const activateFlyPassword = (container) => {

    // Si seulement un champ mot de passe, c'est pas de la création/édition mais du login.
    if (container.querySelectorAll(".password .textor .input input[type='password']").length === 2) {
        // Variables nécessaires pour élaguer le code.
        let input1 = container.querySelectorAll("input[type='password']")[0];
        let input2 = container.querySelectorAll("input[type='password']")[1];

        // Focus dans le 1° input
        input1.addEventListener("focusin", function (event) {
            showPasswordConstraints(event, container);
        });

        // Entrée d'un caractère dans le 1° input
        input1.addEventListener("input", function (event) {
            checkPassword(event, container);
        });

        // Focus dans le 2° input
        input2.addEventListener("focusin", function (event) {
            showPasswordConstraints(event, container);
        });

        // Entrée d'un caractère dans le 2° input
        input2.addEventListener("input", function (event) {
            checkPassword(event, container);
        });
    }
}

/**
 * 
 * @param {Event} e L'evenement focusin
 * @param {Element} container container L'élément Flypassword
 */
const showPasswordConstraints = (e, container) => {
    let inputAlpha = e.currentTarget;
    let alert = inputAlpha.parentElement.parentElement.nextElementSibling;
    let inputBeta = getSecondInput(container, inputAlpha);

    if (!alert.hasAttribute("data-content")) {
        alert.innerHTML = "<span id='alertLower'> Lower character</span><span id='alertUpper'> Upper character</span><span id='alertNumber'> Number character</span><span id='alertSpecial'> Special character (@$!%*?&)</span>";
        if (!inputBeta.parentElement.parentElement.nextElementSibling.hasAttribute("data-content")) inputBeta.placeholder = "Confirm " + inputBeta.placeholder;
        inputBeta.parentElement.parentElement.nextElementSibling.dataset.content = " ";
    }
}

/**
 * Méthode qui récupère le second input password à partir du premier focus.
 * @param {Element} container L'élément Flypassword
 * @param {Element} input Champ password focused
 * @returns {Element} Le champ password non focused
 */
const getSecondInput = (container, input) => {
    let elementArray = Array.from(container.querySelectorAll("input[type='password']"));
    let index = elementArray.indexOf(input);
    elementArray.splice(index, 1);
    return elementArray[0];
}

/**
 * Méthode qui cherche si le mot de passe tapé match les quatre patterns dans le pattern principal.
 * @param {Element} input Le champ dans lequel le mot de passe est tapé.
 * @param {Element} display Le conteneur avec tous les messages d'alerte.
 */
const matchPatterns = (input, display) => {
    if (/(?=.*[a-z])/.test(input.value))
        display.querySelectorAll("span")[0].classList.add("valid");
    else
        display.querySelectorAll("span")[0].classList.remove("valid");

    if (/(?=.*[A-Z])/.test(input.value))
        display.querySelectorAll("span")[1].classList.add("valid");
    else
        display.querySelectorAll("span")[1].classList.remove("valid");

    if (/(?=.*\d)/.test(input.value))
        display.querySelectorAll("span")[2].classList.add("valid");
    else
        display.querySelectorAll("span")[2].classList.remove("valid");

    if (/(?=.*[@$!%*?&])/.test(input.value))
        display.querySelectorAll("span")[3].classList.add("valid");
    else
        display.querySelectorAll("span")[3].classList.remove("valid");
}

/**
 * 
 * @param {*} input1 
 * @param {*} input2 
 * @param {*} display 
 */
const matchPasswords = (input1, input2, display) => {
    if (input1.value !== input2.value) {
        display.innerHTML = "❌ Passwords mismatch";
        display.classList.remove("valid");

        input1.parentElement.parentElement.classList.remove("valid");
        input2.parentElement.parentElement.classList.remove("valid");
    } else {
        display.innerHTML = "✅ Matching Passwords";
        display.classList.add("valid");

        input1.parentElement.parentElement.classList.add("valid");
        input2.parentElement.parentElement.classList.add("valid");
    }
}

/**
 * 
 * @param {*} e 
 * @param {*} container 
 */
const checkPassword = (e, container) => {
    let inputAlpha = e.currentTarget;
    let alert = inputAlpha.parentElement.parentElement.nextElementSibling;
    if (!alert.hasAttribute("data-content")) {
        matchPatterns(inputAlpha, alert);
    } else {
        inputBeta = getSecondInput(container, inputAlpha);
        matchPasswords(inputAlpha, inputBeta, alert);
    }
}