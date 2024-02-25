/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Si la page contient au moins un champ date FLY.
    if (hasFlyDate())

        // Parcours de tous les champ texte FLY pour les activer.
        Array.from(document.querySelectorAll(".flydate"))
            .forEach(activateFlyDate);
});

/**
 * Retourne si la page contient au moins un champ date FLY
 * @returns {boolean} true si un champ date FLY existe, sinon false.
 */
const hasFlyDate = () => document.querySelectorAll(".flydate").length > 0;

/**
 * Méthode qui active toutes les gestions d'évènements pour l'élément container.
 * @param {Element} container L'élément Flydate
 */
const activateFlyDate = (container) => {

    // Si deux container .dator alors champs Date & Heure
    if (container.querySelectorAll(".dator").length === 2) {

        // Chaque fois que la valeur du cycle de temps (12h/24h), vérification & altération de la valeur du champ heure.
        document.querySelector("#time_cycle").addEventListener("change", function () {
            checkTimeCycle(container);
        });

        // Chaque fois que la valeur du cycle de temps (12h/24h), vérification & altération de la valeur du champ résultat de l'élément.
        document.querySelector("#time_period").addEventListener("change", function () {
            fillInputDatetime(container);
        });

        // Activation de la vérification du champ heure à l'arrivée sur la page.
        checkTimeCycle(container);
    }

    // Activation de tous les évènements pour les champs de l'élément.
    Array.from(container.querySelectorAll(".dator .input input"))
        .forEach(input => inputDatetime(container, input));
}

/**
 * Méthode pour changer la valeur et le pattern du champ heure.
 * @param {Element} container L'élément Flydate
 */
const checkTimeCycle = (container) => {
    // Setup des variables utilisées régulièrement.
    let selectCycle = container.querySelector("#time_cycle");
    let selectPeriod = container.querySelector("#time_period");
    let hourInput = container.querySelector("#flydate_input_hour_" + container.querySelector(".date label").getAttribute("for"));

    // Si cycle de 12heures
    if (selectCycle.value === "12H") {
        // Afficher le sélecteur de AM/PM
        selectPeriod.style.display = "block";

        // Changement du pattern du champ heure.
        hourInput.setAttribute("pattern", "[0][1-9]|1[012]");

        // Si le champ heure a une valeur supérieure à 12
        if (Number.parseInt(hourInput.value) > 12) {

            // 12 heures sont retirées à la valeur du champ heure.
            hourInput.value = Number.parseInt(hourInput.value) - 12;

            // La période est valorisée à l'après-midi.
            selectPeriod.value = "PM";
        }

        // Si cycle de 24heures.
    } else {
        // Cacher le sélecteur de AM/PM
        selectPeriod.style.display = "none";

        // Changement du pattern du champ heure.
        hourInput.setAttribute("pattern", "[0-1][0-9]|2[0-3]");

        // Si le sélecteur de période était en après-midi
        if (selectPeriod.value === "PM") {

            // Rajout de 12 heures à l'heure valorisée dans le champ.
            hourInput.value = Number.parseInt(hourInput.value) + 12;
        }
    }

    // Valorisation du champ réponse de l'élément.
    fillInputDatetime(container);
}

/**
 * Méthode qui gère l'évènement d'un caractère frappé dans un champ de l'élément.
 * @param {Element} container L'élément Flydate
 * @param {Element} input Le champ dans lequel l'utilisateur tape des caractères.
 */
const inputDatetime = (container, input) => {

    // A chaque caractère frappé.
    input.addEventListener("input", function () {
        // Vérification & altération de la valeur du champ résultat de l'élément.
        fillInputDatetime(container);

        // Si la valeur du champ match le pattern du champ.
        if (matchPatternFlydate(input)) {

            // Changement de champ
            focusNextInput(container, input);

            // Validation du conteneur dator correspondant.
            validateDator(input);
        }
    });
}

/**
 * Méthode qui rempli le champ résultat de l'élément Flydate.
 * @param {Element} container L'élément Flydate
 */
const fillInputDatetime = (container) => {
    // Récupération des valeurs de la date (jour, mois & année.)
    let name = container.querySelector(".date label").getAttribute("for");
    let year = container.querySelector("#flydate_input_year_" + name).value;
    let month = container.querySelector("#flydate_input_month_" + name).value;
    let day = container.querySelector("#flydate_input_day_" + name).value;

    // Setup des valeurs vides pour l'heure (heure, minutes & secondes.)
    let hour = "";
    let minute = "";
    let seconds = "";

    // Si deux container .dator alors champs Date & Heure
    if (container.querySelectorAll(".dator").length === 2) {

        // Récupération de la valeur du champ heure.
        hour = container.querySelector("#flydate_input_hour_" + name).value;

        // Si la valeur heure est une valeur d'apres dans le cycle 12h, ajout de 12h pour une valeur en cycle 24h.
        if (container.querySelector("#time_cycle").value === "12H" && container.querySelector("#time_period").value === "PM" && Number.parseInt(hour) < 12)
            hour = Number.parseInt(hour) + 12;

        // Récupération des valeurs minutes et secondes.
        minute = container.querySelector("#flydate_input_minute_" + name).value;
        seconds = container.querySelector("#flydate_input_seconds_" + name).value;
    }

    // Valorisation du champ résultat avec les valeurs des champs date & heures des parties dator.
    container.querySelector("input#" + name).value = (year !== "" ? year : "1970") + "-" + (month !== "" ? month : "01") + "-" + (day !== "" ? day : "01") + " " + (hour !== "" ? hour : "00") + ":" + (minute !== "" ? minute : "00") + ":" + (seconds !== "" ? seconds : "00");
}

/**
 * Méthode qui retourne si le contenu de l'input match le pattern demandé par celui-ci.
 * @param {Element} input 
 * @returns true si le pattern est matché, sinon false.
 */
const matchPatternFlydate = (input) => input.value.match(new RegExp(input.pattern));

/**
 * 
 * @param {Element} container L'élément Flydate
 * @param {Element} input Le champ dans lequel l'utilisateur tape des caractères.
 */
const focusNextInput = (container, input) => {
    // Setup de l'ensemble des inputs de l'élément et de l'index de l'input actuel.
    let inputs = Array.from(container.querySelectorAll(".dator .input input"));
    let index = inputs.indexOf(input);

    // Si un input existe après l'actuel, il est focus.
    if (inputs[index + 1] !== undefined) inputs[index + 1].focus();
}

/**
 * Méthode qui va vérifier que tous les champs de la partie dator sont valides.
 * @param {Element} input Le champ dans lequel l'utilisateur tape des caractères.
 */
const validateDator = (input) => {
    // Setup partie dator parent & validation par défaut à vrai.
    let dator = input.parentElement.parentElement;
    let isValid = true;

    // Parcours de tous les inputs de la partie dator et rajout de l'état de l'input à la validation.
    dator.querySelectorAll("input").forEach(input => {
        isValid = isValid && matchPatternFlydate(input);
    });

    // Si tous les champs sont, la partie dator est valide.
    if (isValid)
        dator.classList.add("valid");

    // Sinon la partie dator n'est pas valide.
    else
        dator.classList.remove("valid");
}

/**
 * Méthode pour initialiser les champs dans la page d'édition.
 * @param {String} name Le nom de la variable.
 * @param {String} value La valeur de la variable.
 */
const setTypeDate = (name, value) => {
    let date = value.split(" ")[0];
    let time = value.split(" ")[1];
    let inputEvent = new Event("input");

    document.querySelector("#flydate_input_year_" + name).value = date.split("-")[0];
    document.querySelector("#flydate_input_month_" + name).value = date.split("-")[1];
    document.querySelector("#flydate_input_day_" + name).value = date.split("-")[2];
    document.querySelector("#flydate_input_day_" + name).dispatchEvent(inputEvent);

    document.querySelector("#flydate_input_hour_" + name).value = time.split(":")[0];
    document.querySelector("#flydate_input_minute_" + name).value = time.split(":")[1];
    document.querySelector("#flydate_input_seconds_" + name).value = time.split(":")[2];
    document.querySelector("#flydate_input_seconds_" + name).dispatchEvent(inputEvent);
}