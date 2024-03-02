/**
 * Au chargement de la page.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Pour toutes les checkbox ajax.
    document.querySelectorAll(".ajax.form input[type='checkbox']")
        .forEach((el) => {

            // Ajout de la gestion de l'évènement click.
            el.addEventListener("click", function () {
                // Récupération de la modale correspondante à la checkbox.
                let modal = document.querySelector("[class='" + el.id + "']");

                // Si la checkbox est cochée.
                if (el.checked)

                    // Chargement du contenu de la modale.
                    loadForm(modal);
                else if (!el.checked)

                    // La checkbox n'est pas colorée, on vide le contenu de la modale.
                    modal.querySelector("div.fieldset").innerHTML = "";
            });
        })
});

/**
 * Méthode pour relancer l'evenement de fin de chargement de la page.
 * @returns boolean L'évènement s'est bien déroulé.
 */
const restartDomContentLoaded = () => document.dispatchEvent(new Event("DOMContentLoaded"));

/**
 * Méthode de chargement du contenu de la modale.
 * @param {Element} modal L'élément modale.
 */
const loadForm = (modal) => {
    // Valorisation des arguments nécessaires au chargement de la modale.
    let payload = {
        "object": modal.querySelector("div.fieldset").dataset.object,
        "action": modal.querySelector("div.fieldset").dataset.action
    };

    // Remplacement du contenu de la modale par une icone de chargement.
    modal.querySelector("div.fieldset").innerHTML = "<span class='spinner'></span>";

    // Setup appel Ajax
    fetch("/ajax/getForm", {
        method: "POST",
        headers: {
            "X-CSRF-Token": document.querySelector('[name="_csrfToken"]').value,
            "Content-Type": "application/json"
        },
        credentials: "same-origin",
        body: JSON.stringify(payload)
    }).then(res => res.text())
        // Si l'appel s'est bien passé.
        .then(data => {
            // La réponse de l'appel Ajax est utilisée comme contenu de la modale.
            modal.querySelector("div.fieldset").innerHTML = data;

            // Pour chaque bouton de soumission de modal, le click est remplacée par un deuxieme appel Ajax.
            document.querySelectorAll(".ajax .modal button")
                .forEach(preventClickSubmit);

            // Relance de l'evement de chargement complet de la page.
            restartDomContentLoaded();
        });
}

/**
 * Méthode pour envoyer les données d'une nouvelle entité.
 * 
 * @param {Element} btn Le bouton de soumission cliqué
 */
const preventClickSubmit = (btn) => {

    // Au click sur le bouton.
    btn.addEventListener("click", function (event) {

        // On empêche le click naturel du bouton.
        event.preventDefault();

        // Récupération de la modale.
        let modal = event.currentTarget.parentElement.parentElement;

        // Verification que tous les champs requis de la modale sont remplis.
        if (checkRequiredFields(modal)) {
            // Tous les champs requis sont rempli.

            // Le contenu du message d'alerte est vidé.
            modal.querySelector(".form.alert").innerText = "";

            // Récupération des données de la modale.
            let payload = Object.fromEntries(getDataModal(modal));

            // Setup de l'appel Ajax.
            fetch("/ajax/call", {
                method: "POST",
                headers: {
                    "X-CSRF-Token": document.querySelector('[name="_csrfToken"]').value,
                    "Content-Type": "application/json"
                },
                credentials: "same-origin",
                body: JSON.stringify(payload)
            }).then(res => res.json())
                // Si l'appel s'est bien passé.
                .then(data => {
                    // Récupération du composant Fly suivant.
                    let component = document.querySelector("input[id='" + modal.classList + "']").parentElement.nextElementSibling;

                    // Si composant sélecteur multiple, remplissage spécifique de la liste d'options
                    if (component.classList.contains("flymultiselect"))
                        updateMultiOptionsList(component, data.list);
                    // Si composant sélecteur simple, remplissage spécifique de la liste d'options
                    else if (component.classList.contains("flyselect"))
                        updateOptionsList(component, data.list);

                    // Fermeture de la modale.
                    document.querySelector("input[id='" + modal.classList + "']").checked = false;

                    Array.from(document.querySelectorAll(".ajax.form .fieldset")).forEach((elt) => {
                        //Toutes les modales sont nettoyées de leurs contenus.
                        elt.innerHTML = "";
                    });

                    // Relance de l'évenement de fin de chargement de la page.
                    restartDomContentLoaded();
                });
        } else
            // Au moiins un champ requis n'est pas rempli, avertissement de l'utilisateur.
            alertEmptyRequiredField(modal);
    });
}

/**
 * Méthode de récupération des données de la modale.
 * 
 * @param {Element} modal L'élément modale.
 * @returns array Le tableau des données de la modale.
 */
const getDataModal = (modal) => {
    // Initialisation de la réponse.
    let ret = [];

    // Initialisation de la liste des noms des inputs de la modale.
    let names = [...new Set(Array.from(modal.querySelectorAll(".fieldset [name]")).map((elt) => { return elt.name }))];

    // Initialisation des valeurs de la modale.
    let values = names.map((elt) => { return Array.from(modal.querySelectorAll(`[name='` + elt + `']`)).map((value) => { return value.value }) });

    // Pour chaque input, on ajoute sa valeur dans le tableau de réponse avec le nom de l'input.
    names.forEach((nom, idx) => (ret[idx] = values[idx].length > 1 ? [nom, values[idx]] : [nom, values[idx][0]]));

    // Retourne le tableau de réponse.
    return ret;
}

/**
 * Méthode qui va vérifier que tous les champs requis sont remplis.
 * Retourne false si au moins un champ est vide.
 * 
 * @param {Element} modal L'élément modale.
 * @returns boolean Indication si tous les champs requis sont remplis.
 */
const checkRequiredFields = (modal) => {
    return Array.from(modal.querySelectorAll("[name][required]")).every((elt) => { return (elt.value !== null && elt.value !== undefined && elt.value !== "") });
}

/**
 * Méthode qui crée un message d'avertissement pour chaque champ requis non rempli et qui l'affiche.
 * 
 * @param {Element} modal L'élément modale.
 */
const alertEmptyRequiredField = (modal) => {

    // Initialisation du message d'alerte.
    let message = Array.from(modal.querySelectorAll("[name][required]")).filter((elt) => { return (elt.value !== null && elt.name !== "" && elt.name.indexOf("fly") === -1) }).map((elt) => {
        return document.querySelector("label[for='" + elt.name + "']").innerText;
    }).join(", ");

    // Valorisation du message d'alerte dans le champ d'alerte à coté du bouton de soumission.
    modal.querySelector(".form.alert").innerText = "[Error] Empty fields : " + message + ".";
}

/**
 * Méthode pour mettre à jour la liste d'option d'un sélecteur multiple.
 * 
 * @param {Element} container Element FlyMultiSelect
 * @param {Array<string>} list La liste des nouvelles valeurs d'options
 */
const updateMultiOptionsList = (container, list) => {
    // La liste d'options précédentes est vidée.
    let select = container.querySelector("ul");
    select.innerHTML = "";

    // La liste des valeurs des nouvelles options est triée par texte.
    let optionsFlyList = Object.entries(list);
    optionsFlyList.sort(sortByName);

    // Le nom du champ est celui du label dans les []
    let name = container.querySelector("label").getAttribute("for").replace("[]", "");

    // Les options sont groupées, il faut donc d'abord faire un optgroup avant de faire les options.
    if (typeof optionsFlyList[0][1] === "object") {

        // Pour chaque groupe dans les options
        optionsFlyList.forEach((group) => {

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
                option.id = container.classList + "_option_" + name + "_" + opt[0];
                option.innerHTML = opt[1];

                // Ajout à la liste.
                ul.appendChild(option)
            });
        })
    } else {
        // Les options ne sont pas groupées.

        // Pour chaque option
        optionsFlyList.forEach((opt) => {

            // Création du list-item.
            let option = document.createElement("li");

            // Valorisation des attributs du list-item.
            option.classList = "option";
            option.id = container.classList + "_option_" + name + "_" + opt[0];
            option.innerHTML = opt[1];

            // Ajout du list-item à la liste.
            select.appendChild(option)
        });
    }

    // Rechargement de l'évènement de chargement de la page.
    restartDomContentLoaded();
}

/**
 * Méthode pour mettre à jour la liste d'option d'un sélecteur simple.
 * 
 * @param {Element} container Element FlySelect
 * @param {Array<string>} list La liste des nouvelles valeurs d'options
 */
const updateOptionsList = (container, list) => {
    // La liste d'options précédentes est vidée.
    let select = container.querySelector("select");
    select.innerHTML = "";

    // / La liste des valeurs des nouvelles options est triée par texte.
    let optionsFlyList = Object.entries(list);
    optionsFlyList.sort(sortByName);

    // Si les options sont un objet (et donc un groupe d'options).
    if (typeof optionsFlyList[0][1] === "object") {

        // Parcoursde groupes doption
        optionsFlyList.forEach((group) => {

            // Initialisation du groupe option avec le text comme label.
            let optionGroup = document.createElement("optiongroup");
            optionGroup.label = group[0];

            // Pour chaque option dans le groupe.
            Object.entries(group[1]).forEach((opt) => {

                // Création de l'option.
                let option = document.createElement("option");

                // Valorisation des attributs de l'option.
                option.value = opt[0];
                option.innerHTML = opt[1];

                // Ajout au groupe d'options.
                optionGroup.appendChild(option)
            });

            // Ajout du groupe d'options à la liste.
            select.appendChild(optionGroup);
        });
    } else {
        //Si les options sont du text (ce sont donc bien des options simples).
        // Pour chaque option
        optionsFlyList.forEach((opt) => {

            // Création de l'option.
            let option = document.createElement("option");

            // Valorisation des attributs de l'option.
            option.value = opt[0];
            option.innerHTML = opt[1];

            // Ajout de l'option à la liste.
            select.appendChild(option);
        });
    }

}

/**
 * Méthode de comparaison de deux chaînes de caractères?
 * 
 * @param {string} a 
 * @param {string} b 
 * @returns integer La position de b en fonction de a. 
 */
const sortByName = (a, b) => {
    if (a[1].toLowerCase() < b[1].toLowerCase())
        return -1;
    if (a[1].toLowerCase() > b[1].toLowerCase())
        return 1;

    return 0;
}