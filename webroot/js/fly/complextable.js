/**
 * Variables du script JS.
 * Table de tri, Table de filtres, table des classes pouvant servir de filtre.
 */
let sortTable = [];
let filterTable = [];
let searchTable = [];
let classes = ["auteur", "fandom", "fanfiction", "langage", "relation", "serie", "tag"];

/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Si la page a au moins un élément Fly Table.
    if (hasFlyTable()) {

        // Parcours des éléments Fly Table pour activer les évènements.
        Array.from(document.querySelectorAll(".flytable"))
            .forEach(activateFlyTable);
    }
});

/**
 * Méthode qui retourne si un élément Fly Table existe.
 * @returns Indication si ou non un élément Fly Table.
 */
const hasFlyTable = () => document.querySelectorAll(".flytable").length > 0;

/**
 * Méthode qui active tous les évènements pour un élément Fly Table.
 * @param {Element} container 
 */
const activateFlyTable = (container) => {
    /**
     * Evenements communs.
     */

    // Clique sur le bouton de descente.
    document.querySelector(".upperBar .menu >*:last-child").addEventListener("click", function () {
        window.scrollTo(0, document.body.scrollHeight);
    });

    // Clique sur le bouton de montée.
    document.querySelector(".lowerBar .menu >*:last-child").addEventListener("click", function () {
        window.scrollTo(0, 0);
    });

    /**
     * Evenements Table complexe.
     */
    if (container.querySelector(".table").classList[1] === "complex") {

        // Activer l'affichage actives/inactives
        document.querySelector(".upperBar .menu > *:nth-child(2)").addEventListener("click", function (event) {
            loadFullData(container, event.currentTarget.innerText === "Actives");
            event.currentTarget.innerHTML = (event.currentTarget.innerHTML === "Actives" ? "Inactives" : "Actives");
        });

        // Setup des boutons pour les modales.
        setupBtnFilters(container);
        setupBtnSearch(container);
        setupBtnSort(container);

        // Chargement des données et création de la table complexe.
        loadFullData(container, true);

        // Setup du bouton des réinitialisations.
        reinitialize(container);
    }
}

/**
 * Méthode de chargement des données et création contenu tableau complexe.
 * @param {Element} container L'elément FlyTable 
 * @param {boolean} active Indication si chargement d'entités actives ou inactives.
 */
const loadFullData = (container, active) => {
    // Valorisation des arguments nécessaires au chargement de la modale.
    let payload = {
        "_object": container.querySelector('[name="_object"]').value,
        "_action": "g11",
        "active": active,
        "filters": filterTable,
        "search": searchTable,
        "sort": sortTable
    };

    // Remplacement du contenu de la modale par une icone de chargement.
    container.querySelector(".table .body").innerHTML = "<span class='spinner'></span>";

    // Setup appel Ajax
    fetch("/ajax/call", {
        method: "POST",
        headers: {
            "X-CSRF-Token": container.querySelector("[name]").value,
            "Content-Type": "application/json"
        },
        credentials: "same-origin",
        body: JSON.stringify(payload)
    }).then(res => {

        if (res.ok)
            // Si HTTP code 200 sur l'appel, la response est tournée en JSON.
            return res.json();
        // Si HTTP code autre que 200 sur l'appel, envoi d'une erreur.
        throw new Error(res.body);
    })
        // Si l'appel s'est bien passé.
        .then(data => {
            // Les données de l'appel ajax sont tournées en une chaine de caractères qui est placé dans l'input des données de la table.
            container.querySelector("[name='flytable_data']").value = JSON.stringify(data.list);

            // Population de la table à partir des données de l'input.
            populateComplexTable(container);

            // Valorisation du nombre d'entités à partir de la taille du table de données récupérées par l'appel ajax.
            container.querySelector("#count").innerHTML = data.list.length;
        })
        // Si erreur, message d'erreur dans la console.
        .catch((error) => console.error(error));
}

/**
 * Méthodes communes aux tables simple et complexe.
 */

/**
 * Méthode qui compte le nombre de lignes visibles.
 * @param {Element} container 
 * @returns Le nombre de lignes visibles.
 */
const countVisibleRow = (container) => container.querySelector("#count").innerHTML = container.querySelectorAll(".table .body .row:not([style])").length;

/**
 * Méthode qui va créer un identifiant unique.
 * @param {string} prefix 
 * @param {boolean} random 
 * @returns L'identifiant unique.
 */
const uniqid = (prefix = "", random = false) => {
    // Récupération de la somme des millisecondes et d'un nombre random.
    const sec = Date.now() * 1000 + Math.random() * 1000;

    // Récupération de l'identifiant créé en prenant 16 caractères de la somme précédente à laquelle est rajoutée 14 fois le 0.
    const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");

    // Retourne l'identifiant avec le préfix avant et le random apres.
    return `${prefix}${id}${random ? `.${Math.trunc(Math.random() * 100000000)}` : ""}`;
};

/**
 * Méthode qui échappe HTML d'une chaîne de caractères.
 * @param {string} htmlStr La chaîne de caractères avant échappement.
 * @returns string chaîne de caractères après échappement.
 */
const escapeHTML = (htmlStr) => htmlStr.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;");

/**
 * Table complexe
 */

/**
 * Méthode pour remplir une table complexe.
 * @param {Element} container L'élément FlyTable
 */
const populateComplexTable = (container) => {
    // Effacement de tout le corps de la table.

    // Récupération des données.
    let data = JSON.parse(container.querySelector("input[type='hidden'][name='flytable_data']").value);
    let type = window.location.pathname.substring(1);

    // Pour chaque entité manipulée.
    data.forEach((element) => {

        // Identifiant pour la suppression d'entité.
        let id = uniqid();

        // Création de la ligne pour l'entité, avec l'ajout au corps de la table et l'ajout de sa classe.
        let row = document.createElement("div");
        row.classList = "rowblock";
        container.querySelector(".body").appendChild(row);

        //  Création du titre.
        let title = document.createElement("div");
        title.classList = "title";

        // Si l'entité à des liens.
        if (Object.hasOwn(element, 'liens')) {

            // Nom de l'entité, de son auteur, sa note, de la flèche pour les liens et la flèche pour ouvrir/fermer la ligne.
            title.innerHTML += "<div class='nom'>" + element.nom + "</div>" + (element.liens.length > 1 ? "<span>►</span>" : "<a href='" + element.liens[0].lien + "' target='_blank'>►</a>");
            title.innerHTML += " by " + element.auteur_obj.nom + (element.evaluation !== null ? "<span data-tooltip='" + escapeHTML(element.evaluation) + "'>" + element.note_obj + "</span>" : "");
            title.innerHTML += " <label class='end'><input name='hide_check' type='checkbox' value='1'></label>";

            if (element.liens.length > 1) {
                // Si l'entité a plus d'un lien, setup de l'evenement click pour afficher les liens.
                title.querySelector("span").addEventListener("click", function () { title.classList.toggle("multi") });

                // Création de la liste de liens qui est affichée au click.
                let ul = document.createElement("ul");
                ul.classList = "liens";

                element.liens.forEach((url) => {
                    // Création d'un élément pour chaque lien de l'entitié.
                    let li = document.createElement("li");
                    li.innerHTML = url.lien.split("/")[2] + "<a href='" + url.lien + "' target='_blank'>►</a>";

                    // Ajout de l'élément à la liste.
                    ul.appendChild(li);
                });

                // Ajout de la liste au titre.
                title.appendChild(ul);
            }
        } else {
            // L'entité n'a pas de lien, création du lien pour visionner l'entité avec son nomn, son/ses auteur(s), sa note et la flèche pour ouvrir fermer la ligne.
            title.innerHTML += "<a href='/view/" + element.id + "'><div class='nom'>" + element.nom + "</div></a>";
            title.innerHTML += " by " + element.auteurs.map((auteur) => { return auteur.nom }).join(" & ") + (element.evaluation !== null ? "<span data-tooltip='" + escapeHTML(element.evaluation) + "'>" + element.note_obj + "</span>" : "");
            title.innerHTML += " <label class='end'><input name='hide_check' type='checkbox' value='1'></label>";
        }

        // Ajout du titre à la ligne.
        row.appendChild(title);

        // Création de la première ligne de détails avec fandom(s), classement, langage, date de création & date d'update.
        let details1 = document.createElement("div");
        details1.classList = "details";
        details1.innerHTML += "<span class='material-symbols-outlined'>globe</span> : <span class='fandoms'>" + element.fandoms.map((fandom) => { return fandom.nom }).join(" & ") + "</span>";
        details1.innerHTML += "<span class='material-symbols-outlined'>readiness_score</span> : <span class='classement'>" + element.classement_obj + "</span>";
        details1.innerHTML += "<span class='material-symbols-outlined'>language</span> : <span class='langages'>" + element.langage_obj + "</span>";
        details1.innerHTML += "<span class='material-symbols-outlined'>first_page</span> : <span class='creation_date'>" + new Date(element.creation_date).toLocaleString("fr-FR", { timeZone: 'UTC' }) + "</span>";
        details1.innerHTML += "<span class='material-symbols-outlined'>sync_alt</span> : <span class='update_date'>" + new Date(element.update_date).toLocaleString("fr-FR", { timeZone: 'UTC' }) + "</span>";

        // Ajout de la première ligne de détails à la ligne.
        row.appendChild(details1);

        // Création de la description et ajout àa la ligne.
        let description = document.createElement("div");
        description.classList = "description";
        description.innerText = element.description;
        row.appendChild(description);

        // Création de la deuxième ligne de détails avec relations, personnages & tags, puis ajout à la ligne.
        let details2 = document.createElement("div");
        details2.classList = "details";
        details2.innerHTML += (element.relations.length > 0 ? "<p><span class='material-symbols-outlined'>diversity_1</span> : <span class='descriptif relations'>" + element.relations.map((relation) => { return relation.nom }).join(", ") + "</span></p>" : "");
        details2.innerHTML += (element.personnages.length > 0 ? "<p><span class='material-symbols-outlined'>group</span> : <span class='descriptif personnages'>" + element.personnages.map((personnage) => { return personnage.nom }).join(", ") + "</span></p>" : "");
        details2.innerHTML += (element.tags.length > 0 ? "<p><span class='material-symbols-outlined'>label</span> : <span class='descriptif tags'>" + element.tags.map((tag) => { return tag.nom }).join(", ") + "</span></p>" : "");
        row.appendChild(details2);

        // Panneau d'actions (edit & review & delete / restore)
        let actionPanel = document.createElement("div");
        actionPanel.classList = "action-panel";
        actionPanel.innerHTML += "<a data-tooltip='Edit " + escapeHTML('"' + element.nom + '"') + "' href='" + type + "/edit/" + element.id + "'><span class='material-symbols-outlined'>edit</span></a>";

        // Bouton de notation ou non.
        actionPanel.innerHTML += (element.note === null ? "<a data-tooltip='Review " + escapeHTML('"' + element.nom + '"') + "'><span class='material-symbols-outlined'>rate_review</span></a>" : "");

        // Bouton de suppression ou de restoration.
        if (window.location.search === "") {
            actionPanel.innerHTML += "<form name='post_" + id + "' style='display:none;' method='post' action='/" + type + "/delete/" + element.id + "'><input type='hidden' name='_method' value='POST'><input type='hidden' name='_csrfToken' autocomplete='off' value='" + container.querySelector("[name]").value + "'></form>";
            actionPanel.innerHTML += "<a data-tooltip='Delete " + escapeHTML('"' + element.nom + '"') + "' data-confirm-message='Are your sure to delete " + escapeHTML('"' + element.nom + '"') + "' href='#' onclick='if (confirm(this.dataset.confirmMessage)) { document.post_" + id + ".submit();} event.returnValue = false; return false;'><span class='material-symbols-outlined'>delete</span></a>";
        } else {
            actionPanel.innerHTML += "<form name='post_" + id + "' style='display:none;' method='post' action='/" + type + "/restore/" + element.id + "'><input type='hidden' name='_method' value='POST'><input type='hidden' name='_csrfToken' autocomplete='off' value='" + container.querySelector("[name]").value + "'></form>";
            actionPanel.innerHTML += "<a data-tooltip='Restore " + escapeHTML('"' + element.nom + '"') + "' data-confirm-message='Are your sure to restore " + escapeHTML('"' + element.nom + '"') + "' href='#' onclick='if (confirm(this.dataset.confirmMessage)) { document.post_" + id + ".submit();} event.returnValue = false; return false;'><span class='material-symbols-outlined'>restore_page</span></a>";
        }


        if (element.note !== null) {
            // Si une note existe, nouvel ID pour le formulaire de dénotation.
            id = uniqid();

            // Formulaire et bouton pour dénoter l'entité.
            actionPanel.innerHTML += "<form name='post_" + id + "' style='display:none;' method='post' action='/" + type + "/denote/" + element.id + "'><input type='hidden' name='_method' value='POST'><input type='hidden' name='_csrfToken' autocomplete='off' value='" + container.querySelector("[name]").value + "'></form>";
            actionPanel.innerHTML += "<a data-tooltip='Denote " + escapeHTML('"' + element.nom + '"') + "' data-confirm-message='Are your sure to denote " + escapeHTML('"' + element.nom + '"') + "' href='#' onclick='if (confirm(this.dataset.confirmMessage)) { document.post_" + id + ".submit();} event.returnValue = false; return false;'><span class='material-symbols-outlined'>contract_delete</span></a>";
        }

        // Ajout du panel d'action
        row.appendChild(actionPanel);

        // La modale pour la note.
        if (element.note === null && row.contains(actionPanel)) {

            // Pointer sur le bouton d'appel de la modale.
            row.querySelector(".action-panel > *:nth-child(2)").style.cursor = "pointer";

            // Setup evenement click sur le bouton de la note.
            row.querySelector(".action-panel > *:nth-child(2)").addEventListener("click", function (event) {

                // Création du fond noir de la modale.
                const background = document.createElement("label");
                background.classList = "modal back";
                background.setAttribute("for", "modal" + element.id);
                row.appendChild(background);

                // Création de la fenêtre de la modale.
                const modalWindow = document.createElement("div");
                modalWindow.classList = "modal window";
                modalWindow.id = "modal" + element.id;
                modalWindow.innerHTML += "<div class='head'><h3>Review \"" + element.nom + "\"</h3></div>";
                modalWindow.innerHTML += "<div class='fieldset' data-object='" + (type === "fanfictions" ? "f11" : "s6") + "' data-action='n4'></div>";
                row.appendChild(modalWindow);

                loadNoteForm(row.querySelector("#modal" + element.id), element.id);
            });
        }
    });

    // Suppression de l'icone de chargement.
    container.querySelector(".table .body .spinner").remove();
}

/**
 * Methode qui charge le formulaire de notation de la fanfiction.
 * @param {Element} modal La modale dans laquelle charger le formulaire.
 * @param {Integer} id L'identifiant de la fanfiction à noter.
 */
const loadNoteForm = (modal, id) => {

    // Valorisation des arguments nécessaires au chargement de la modale.
    let payload = {
        "object": modal.querySelector("div.fieldset").dataset.object,
        "action": modal.querySelector("div.fieldset").dataset.action,
        "id": id
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
            document.querySelectorAll(".flytable .modal.window button")
                .forEach(preventClickSubmit);

            modal.querySelector("button").nextElementSibling.addEventListener("click", function () {
                modal.previousElementSibling.remove();
                modal.remove();
            });

            // Parcours de tous les sélecteurs FLY pour les activer.
            Array.from(document.querySelectorAll(".flyselect"))
                .forEach(element => activateFlySelect(element));

            // Parcours de tous les champ texte FLY pour les activer.
            Array.from(document.querySelectorAll(".flytextarea"))
                .forEach((element) => activateFlyTextarea(element));
        });
}

/**
 * Méthode pour noter une entité
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
                    const container = modal.closest(".flytable");
                    container.querySelector("[name='flytable_data']").value = JSON.stringify(data.list);
                    populateComplexTable(container);
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
const checkRequiredFields = (modal) => Array.from(modal.querySelectorAll("[name][required]")).every((elt) => { return (elt.value !== null && elt.value !== undefined && elt.value !== "") });

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
 * Méthode qui valoriser la table de filtres avec les données de la modale.
 * @param {Element} container L'elément FlyTable 
 */
const setFiltersTable = (container) => {
    filterTable = [].concat(
        Array.from(container.querySelector(".modalwindow:has([for='modalFilters'])").querySelectorAll("input")).filter((input) => { return !isNaN(input.value) && !isNaN(parseFloat(input.value)) }).map((input) => { return [input.name, input.value] }),
        Array.from(container.querySelector(".modalwindow:has([for='modalFilters'])").querySelectorAll("select")).filter((select) => { return !isNaN(select.value) && !isNaN(parseFloat(select.value)) }).map((select) => { return [select.name, select.value] })
    );
}

/**
 * * Méthode qui valoriser la table de recherche avec les données de la modale.
 * @param {Element} container L'elément FlyTable 
 */
const setSearchTable = (container) => {
    searchTable = [];

    const field = Array.from(container.querySelector(".modalwindow:has([for='modalSearch'])").querySelectorAll("select")).map((select) => { return [select.name, select.value] })[0][1];
    const value = Array.from(container.querySelector(".modalwindow:has([for='modalSearch'])").querySelectorAll("input")).map((input) => { return [input.name, input.value] })[0][1];

    if (field !== "" && value !== "") searchTable.push([field, value]);
}

/**
 * * Méthode qui valoriser la table de tri avec les données de la modale.
 * @param {Element} container L'elément FlyTable 
 */
const setSortTable = (container) => {
    sortTable = [].concat(
        Array.from(container.querySelector(".modalwindow:has([for='modalSort'])").querySelectorAll("select")).filter((select) => { return select.value !== "" }).map((select) => { return [select.name, select.value] })
    );
}

/**
 * Méthode de setup du click sur le bouton de soumission de la modale des filtres.
 * @param {Element} container L'elément FlyTable 
 */
const setupBtnFilters = (container) => {

    // Récupération des données de la session et du bouton de soumission de la modale.
    const session = Object.entries(JSON.parse(container.querySelector("[name='filters_data']").value));
    const btnFilters = container.querySelector(".modalwindow:has([for='modalFilters']) button");

    if (session.length > 0) {
        // Si la session n'est pas vide.

        session.forEach((filtre) => {
            // Parcours des données de la session pour les filtres.

            if (["fandoms", "personnages", "relations", "tags"].includes(filtre[0]))
                // Si la donnée est un fandom, un personnage, une relation ou un tag, click sur l'option multiselect correspondante.
                document.querySelector("[id='flymultiselect_option_" + filtre[0] + "_" + filtre[1] + "']").click();
            else
                // Si la donnée est autre, click sur l'option select correspondante.
                document.querySelector("[id='flyselect_option_" + filtre[0] + "s_" + filtre[1] + "']").click();
        });

        // Valorisation de la table des filtres.
        setFiltersTable(container);
    }

    // Setup du click sur le bouton de soumission.
    btnFilters.addEventListener("click", function () {

        // Valorisation des tables de filtres, de recherche et de tri.
        setFiltersTable(container);
        setSearchTable(container);
        setSortTable(container);

        // Chargement de la liste des entités avec les données des tables.
        loadFullData(container, container.querySelector(".upperBar .menu > *:nth-child(2)").innerText === "Inactives");

        // Fermeture de la modale.
        container.querySelector(".modalwindow:has([for='modalFilters']) .head label").click();
    });
}

/**
 * Méthode de setup du click sur le bouton de soumission de la modale de la recherche.
 * @param {Element} container L'elément FlyTable 
 */
const setupBtnSearch = (container) => {

    // Récupération du bouton de soumission de la modale.
    const btnSearch = container.querySelector(".modalwindow:has([for='modalSearch']) button");

    // Setup du click sur le bouton de soumission.
    btnSearch.addEventListener("click", function () {

        // Valorisation des tables de filtres, de recherche et de tri.
        setFiltersTable(container);
        setSearchTable(container);
        setSortTable(container);

        // Chargement de la liste des entités avec les données des tables.
        loadFullData(container, container.querySelector(".upperBar .menu > *:nth-child(2)").innerText === "Inactives");

        // Fermeture de la modale.
        container.querySelector(".modalwindow:has([for='modalSearch']) .head label").click();
    });
}

/**
 * Méthode de setup du click sur le bouton de soumission de la modale du tri.
 * @param {Element} container L'elément FlyTable 
 */
const setupBtnSort = (container) => {
    // Récupération du bouton de soumission de la modale.
    const btnSort = container.querySelector(".modalwindow:has([for='modalSort']) button");

    // Setup du click sur le bouton de soumission.
    btnSort.addEventListener("click", function () {

        // Valorisation des tables de filtres, de recherche et de tri.
        setFiltersTable(container);
        setSearchTable(container);
        setSortTable(container);

        // Chargement de la liste des entités avec les données des tables.
        loadFullData(container, container.querySelector(".upperBar .menu > *:nth-child(2)").innerText === "Inactives");

        // Fermeture de la modale.
        container.querySelector(".modalwindow:has([for='modalSort']) .head label").click();
    });
}

/**
 * 
 * @param {Element} container L'elément FlyTable 
 */
const reinitialize = (container) => {

    // Setup du click sur le deuxieme bouton du menu bas (celui de réinitialisation).
    container.querySelector(".lowerBar .menu > *:nth-child(2)").addEventListener("click", function () {

        // Les tableaux sont vidés.
        filterTable = sortTable = searchTable = [];

        // Tous les inputs des modales sont vidés.
        container.querySelectorAll(".modalwindow input").forEach((input) => { input.value = "" });

        // Toutes les options des modales sont désélectionnées.
        container.querySelectorAll(".modalwindow select option").forEach((option) => { option.selected = false });

        // Tous les éléments Fly sont dévalidés.
        container.querySelectorAll(".valid").forEach((valid) => { valid.classList.remove("valid") });

        // Toutes les options des objets Fly Multi Select sont désélectionnées.
        container.querySelectorAll(".modalwindow .display .option").forEach((option) => { option.click() });

        // Chargement des entités à partir des données des tables.
        loadFullData(container, container.querySelector(".upperBar .menu > *:nth-child(2)").innerText === "Inactives");
    });
}