/**
 * Variable du fichier
 */
let optionsFlylist = null;

/**
 * Méthode pour set la variable optionsFlylist
 * @param {array} array 
 */
const setOptionsFlylist = (array) => {
    optionsFlylist = array;
}

/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Si la page contient au moins une liste FLY.
    if (hasFlyList())

        // Parcours de toutes les listes FLY pour les activer.
        Array.from(document.querySelectorAll(".flylist"))
            .forEach(activateFlyList);
});

/**
 * Retourne si la page contient au moins une liste FLY
 * @returns {boolean} true si une liste FLY existe, sinon false.
 */
const hasFlyList = () => document.querySelectorAll(".flylist").length > 0;

/**
 * Méthode qui active toutes les gestions d'évènements pour l'élément container.
 * @param {Element} container L'élément Flylist
 */
const activateFlyList = (container) => {

    // Au click sur le bouton d'ajout de ligne
    container.querySelector(".drop.add .field span").addEventListener("click", function () {

        // Création de la ligne.
        addRow(container);
    });

    // Pour tous les boutons en fin de ligne, ajout de l'evenement de suppression.
    container.querySelectorAll(".drop .drag > div:last-child")
        .forEach((div, key) => {
            if (key !== 0) deleteRow(container, div)
        });

    // Pour toutes les lignes de drop.
    container.querySelectorAll(".drop")
        .forEach((drop) => {

            // Gestion de l'evenement dragover.
            drop.addEventListener("dragover", allowDrop);

            // Gestion de l'évenement drop.
            drop.addEventListener("drop", function (event) {
                dropEvent(container, event);
            });
        });

    // Pour tous les lignes draggable
    container.querySelectorAll(".drag")
        .forEach(drag => {
            // Gestion de l'evement de début du drag.
            drag.addEventListener("dragstart", dragEvent);
        })

    // Si liste vide, ajout d'une ligne obligatoire.
    if (container.querySelectorAll(".listor .drop").length === 1)
        setTimeout(() => {
            addRow(container);
        }, 1);

    /**
     * PARTIE TEXT
     */

    // Si la liste est une liste de champs libres.
    if (container.querySelector("#type").innerHTML === "text") {

        // Pour tous les conteneur qui peuvent être déplacés.
        container.querySelectorAll(".drag")
            .forEach(drag => {

                // Si le champ n'est pas, il est valide.
                if (drag.querySelector("input").value !== "")
                    drag.classList.add("valid");
                else
                    drag.classList.remove("valid");


            });
    }

    /**
     * PARTIE SELECT
     */

    // SI la liste est une liste de selecteurs.
    if (container.querySelector("#type").innerHTML === "select") {

        // POur chaque input dans la liste.
        container.querySelectorAll(".listor .drop .drag .input input").forEach((input) => {

            // Focus dans l'input du champ de recherche.
            input.addEventListener("focusin", function (event) {
                // Affichage de la liste.
                showListFlylist(input);
            });

            // Au click sur la fenêtre.
            window.addEventListener("click", function (event) {

                // Gestion du click en dehors du composant.
                clickOutsideFlylist(event, input);
            });

            //Pour l'ensemble des options de la liste de recherche.
            input.parentElement.nextElementSibling.querySelectorAll("li.option").forEach((li) => {

                // Click sur une option.
                li.addEventListener("click", function (event) {
                    // Choix de l'option relevé.
                    clickOptionFlylist(event, input);
                });
            });

            // Une touche du clavier est tapé dans l'input du champ de recherche.
            input.addEventListener("input", function (event) {

                // La classe de validation est enlevée.
                input
                    .parentElement
                    .parentElement
                    .parentElement
                    .classList.remove("valid");

                // Recherche des options disponibles avec le contenu de la barre de recherche.
                input.parentElement.nextElementSibling.querySelectorAll("li")
                    .forEach((li) => findOptionFlylist(event, input, li));
            });
        });
    }
}

/**
 * Méthode d'ajout d'une ligne dans la liste.
 * @param {Element} container L'élément Flylist.
 */
const addRow = (container) => {
    // Setup des variables à utiliser plusieurs fois ou qui simplifie la compréhension.
    let id = container.querySelector("label").getAttribute("for").replace("1", container.querySelectorAll(".drop").length);
    let name = container.querySelector("label").getAttribute("for").replace("1", "").replace("_", "[]");

    // Création de la ligne de drop.
    let row = document.createElement("div");
    row.classList = "drop";

    // Création de la ligne déplacable.
    let drag = document.createElement("div");
    drag.classList = "drag";
    drag.draggable = true;
    drag.innerHTML = "<div>" + container.querySelectorAll(".drop").length + "</div><div class='field'><div class='input'></div></div>" + (container.querySelectorAll(".listor .drop").length === 1 ? "<div></div></div>" : "<div>🗑️</div>");
    drag.querySelector(".input").innerHTML = "<input id='" + id + "' name='" + name + "' required placeholder='New value here...' autocomplete='off' maxlength='255' />";
    row.appendChild(drag);

    // Ajout des evenements à gérer.
    drag.addEventListener("dragstart", dragEvent);
    deleteRow(container, drag.querySelector("div:last-child:not(div.input)"));
    row.addEventListener("dragover", allowDrop);
    row.addEventListener("drop", function (event) {
        dropEvent(container, event)
    });

    // Insertion de la ligne de drop dans la liste.
    container.querySelector(".listor").insertBefore(row, container.querySelector(".listor").lastElementChild);

    if (container.querySelector("#type").innerHTML === "select") {

        // Création de l'élément liste + Ajout dans le conteneur central à l'élément déplaçable.
        let ul = document.createElement("ul");
        drag.querySelector(".field").appendChild(ul);

        // Les options sont groupées, il faut donc d'abord faire un optgroup avant de faire les options.
        if (typeof optionsFlylist[0][1] === "object") {

            // Pour chaque groupe dans les options
            optionsFlylist.forEach((group) => {

                // Création du list-item pour le groupe
                let optgroup = document.createElement("li");

                // Valorisation des attributs du list-item
                optgroup.classList = "optgroup";
                optgroup.setAttribute("optgroup", group[0]);
                optgroup.innerHTML = group[0];

                // Ajout à la liste.
                ul.appendChild(optgroup);

                // Pour chaque élément du groupe courant.
                Object.entries(group[1]).forEach((opt) => {

                    // Création du list-item pour l'élément.
                    let option = document.createElement("li");

                    // Valorisation des attributs du list-item.
                    option.classList = "option";
                    option.setAttribute("optgroup", group[0]);
                    option.id = "flylist_option_" + name.replace("[]", "") + "_" + opt[0];
                    option.innerHTML = opt[1];

                    // Ajout à la liste.
                    ul.appendChild(option)
                });
            })
        } else {
            // Les options ne sont pas groupées.

            // Pour chaque option
            optionsFlylist.forEach((opt) => {

                // Création du list-item.
                let option = document.createElement("li");

                // Valorisation des attributs du list-item.
                option.classList = "option";
                option.setAttribute("optgroup", group[0]);
                option.id = "flylist_option_" + name.replace("[]", "") + "_" + opt[0];
                option.innerHTML = opt[1];

                // Ajout du list-item à la liste.
                ul.appendChild(option)
            });
        }

        // Récupération de l'input de l'élément déplaçable pour faciliter l'ajout d'évènement.
        let input = drag.querySelector("input");

        // Focus dans l'input du champ de recherche.
        input.addEventListener("focusin", function (event) {
            // Affichage de la liste.
            showListFlylist(input);
        });

        // Au click sur la fenêtre.
        window.addEventListener("click", function (event) {

            // Gestion du click en dehors du composant.
            clickOutsideFlylist(event, input);
        });

        //Pour l'ensemble des options de la liste de recherche.
        input.parentElement.nextElementSibling.querySelectorAll("li.option").forEach((li) => {

            // Click sur une option.
            li.addEventListener("click", function (event) {
                // Choix de l'option relevé.
                clickOptionFlylist(event, input);
            });
        });

        // Une touche du clavier est tapé dans l'input du champ de recherche.
        input.addEventListener("input", function (event) {

            // La classe de validation est enlevée.
            input
                .parentElement
                .parentElement
                .parentElement
                .classList.remove("valid");

            // Recherche des options disponibles avec le contenu de la barre de recherche.
            input.parentElement.nextElementSibling.querySelectorAll("li")
                .forEach((li) => findOptionFlylist(event, input, li));
        });
    }
}

/**
 * Méthode de suppression de ligne dans la liste.
 * @param {Element} container L'élément Flylist.
 * @param {Element} div Bouton de suppression.
 */
const deleteRow = (container, div) => {
    // Click sur le bouton de suppression.
    div.addEventListener("click", function (event) {

        // Demande de confirmation de la suppression.
        if (confirm("Delete \"" + event.currentTarget.parentElement.querySelector("input").value + "\" ?")) {

            //Setup des variables de l'élément à supprimer et du numéro à supprimer.
            let toDelete = event.currentTarget.parentElement.parentElement;
            let number = Number.parseInt(event.currentTarget.parentElement.firstElementChild.innerHTML);

            // Pour toutes les lignes déplacables.
            container.querySelectorAll(".drag")
                .forEach((row) => {
                    // Vérification que la ligne est apres celle à supprimer.
                    if (Number.parseInt(row.firstElementChild.innerHTML) > number) {
                        // Si c'est le cas, on remonte la ligne dans la liste.
                        lowerRow(container, row);
                    }
                });

            // Suppression de la ligne dont le bouton suppression a été cliqué.
            toDelete.remove();

            // Si la liste est une liste de sélecteurs, alors validation des valeurs de l'input central de l'élément pour chaque ligne restante dans la liste.
            if (container.querySelector("#type").innerHTML === "select")
                ValidateInputValue();
        }
    })
}

/**
 * Méthode de prévisualisation du drop
 * @param {Event} event 
 * @returns 
 */
const allowDrop = (event) => event.preventDefault();

/**
 * Méthode de déplacement au dessus
 * @param {Event} event 
 * @returns 
 */
const dragover = (event) => event.preventDefault();

/**
 * Méthode de déplacement
 * @param {Event} event 
 * @returns 
 */
const dragEvent = (event) => event.dataTransfer.setData("dragged", event.target.firstElementChild.innerHTML);

/**
 * Méthode de drop d'une ligne en cours de déplacement.
 * @param {Element} container L'élément Flylist
 * @param {Event} event Evenement de drop d'une ligne en cours de déplacement.
 */
const dropEvent = (container, event) => {
    // Empechement de toute execution native de code pour le drop.
    event.preventDefault();

    // Setup des variables utilisées plusieurs fois.
    let data = event.dataTransfer.getData("dragged");
    let id = "input#" + container.querySelector("label").getAttribute("for").replace("1", Number.parseInt(data));
    let moved = container.querySelector(id);
    let drag = moved.parentElement.parentElement.parentElement;
    let dropId = event.currentTarget.querySelector(".drag div:first-child").innerHTML;
    let rows = container.querySelectorAll(".drop:not(.add) .drag");
    let possibles = container.querySelectorAll(".drop:not(.add)");
    let row, i = null;


    // La ligne déplacée est apres la zone de drop.
    if (dropId < data) {

        // De la zone de drop à la ligne déplacée.
        for (i = dropId; i < data; i++) {

            // Récupération de ligne en déplacement.
            row = rows[Number.parseInt(i) - 1];

            // Changement de numéro pour la ligne en cours de déplacement.
            upperRow(container, row);

            // Dépot de la ligne cours de déplacement.
            possibles[Number.parseInt(i)].appendChild(row);
        }
        // La ligne déplacée est avant la zone de drop.
    } else if (dropId > data) {

        // De la ligne déplacée à la zone de drop.
        for (i = dropId; i > data; i--) {

            // Récupération de ligne en déplacement.
            row = rows[Number.parseInt(i) - 1];

            // Changement de numéro pour la ligne en cours de déplacement.
            lowerRow(container, row);

            // Dépot de la ligne cours de déplacement.
            possibles[Number.parseInt(i) - 2].appendChild(row);
        }
    }

    drag.firstElementChild.innerHTML = Number.parseInt(dropId);
    moved.id = container.querySelector("label").getAttribute("for").replace("1", Number.parseInt(dropId));
    event.currentTarget.appendChild(drag);
}

/**
 * Méthode pour réduire le rang de la ligne dans la liste
 * @param {Element} container L'élément Flylist
 * @param {Element} row La ligne en cours de déplacement.
 */
const lowerRow = (container, row) => {
    // Changement numéro entete ligne
    row.firstElementChild.innerHTML = Number.parseInt(row.firstElementChild.innerHTML) - 1;

    // Changement identifiant input.
    row.querySelector("input").id = container.querySelector("label").getAttribute("for").replace("1", row.firstElementChild.innerHTML);
}

/**
 * Méthode pour augmenter le rang de la ligne dans la liste
 * @param {Element} container L'élément Flylist
 * @param {Element} row La ligne en cours de déplacement.
 */
const upperRow = (container, row) => {
    // Changement numéro entete ligne
    row.firstElementChild.innerHTML = Number.parseInt(row.firstElementChild.innerHTML) + 1;

    // Changement identifiant input.
    row.querySelector("input").id = container.querySelector("label").getAttribute("for").replace("1", row.firstElementChild.innerHTML);
}

/**
 * Méthode pour réagir au click en dehors du composant.
 * @param {Event} e L'évènement click
 * @param {Element} input Le composant.
 */
const clickOutsideFlylist = (e, input) => { if (!input.parentElement.parentElement.contains(e.target)) hideListFlylist(input); };

/**
 * Méthode pour cacher la liste d'options.
 * @param {Element} input Le champ de la liste.
 */
const hideListFlylist = (input) => { input.parentElement.nextElementSibling.classList.remove("visible") };

/**
 * Méthode pour afficher la liste d'options.
 * @param {Element} input Le champ de la liste.
 */
const showListFlylist = (input) => { input.parentElement.nextElementSibling.classList.add("visible") };

/**
 * Méthode pour gérer le click sur une option.
 * @param {Event} e Evenement click
 * @param {Element} input Le champ de la liste.
 */
const clickOptionFlylist = (e, input) => {

    // Validation de l'option cliquée.
    validateOptionFlylist(input, e.currentTarget);

    // Cache de la liste des options.
    hideListFlylist(input);
}

/**
 * Méthode pour valider une option
 * @param {Element} input Le champ de la liste.
 * @param {Element} option L'option cliqué
 */
const validateOptionFlylist = (input, option) => {

    // Le label de l'option choisi est placé dans le champ de recherche.
    input.value = option.innerText;
    input.setAttribute("option", option.id.split("_")[3]);

    // Ajout de la classe montrant que le choix est valide.
    input
        .parentElement
        .parentElement
        .parentElement
        .classList.add("valid");

    // Validation des valeurs de l'input central de l'élément à partir des valeurs de la liste.
    ValidateInputValue();
};

/**
 * Recherche si l'élément de la liste correspond au champ de recherche.
 * @param {Event} e Evenement d'écriture dans la barre de recherche.
 * @param {Element} input Le champ de la liste.
 * @param {Element} li Element de la liste.
 */
const findOptionFlylist = (e, input, li) => {

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
            console.log();
            input.parentElement.nextElementSibling.querySelector(`ul li.optgroup[optgroup="` + li.getAttribute("optgroup") + `"]`).style.display = "";
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
        validateOptionFlylist(input, option);

        // Liste cachée.
        hideListFlylist(input);

        // Le focus sur le champ de recherche est perdu.
        if (li.classList.contains("option")) e.currentTarget.blur();
    }
}

/**
 * Méthode à utiliser pour set une valeur de base dans le sélecteur
 * @param {string} name 
 * @param {string} value 
 */
const setClickOptionFlylist = (name, value) => {
    let input = document.querySelector("#" + name);
    let option = input.parentElement.nextElementSibling.querySelector("#flylist_option_fanfictions_" + value);
    option.click();
}

/**
 * Méthode de validation des valeurs de l'input central à partir des éléments de la liste.
 */
const ValidateInputValue = () => {
    // Parcours des listes FLY avec un input caché (les listes de sélecteur.)
    document.querySelectorAll(".flylist:has(input[hidden])").forEach((flylist) => {

        // Suppression de tous les inputs supprimables.
        flylist.querySelectorAll(".toDelete").forEach((element) => element.remove());

        // Pour tous les inputs de la liste FLY.
        flylist.querySelectorAll(".listor .drag .input input").forEach((input) => {

            // Clonage de l'input central pour créer un nouvel input.
            let inputValue = flylist.querySelector("input[hidden]").cloneNode(false);

            // Valorisation des attributs de l'input.
            inputValue.classList = "toDelete";
            inputValue.value = input.getAttribute("option");

            // AJout de l'input à l'élément.
            flylist.appendChild(inputValue);
        });
    });
}