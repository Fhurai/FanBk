/**
 * Variables du script JS.
 * Table de tri, Table de filtres, table des classes pouvant servir de filtre.
 */
let sortTable = [];
let filterTable = [];
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
     * Evenements Table simple.
     */
    if (container.querySelector(".table").classList[1] === "simple") {
        // Remplir le champ de data avec les données des entités.
        loadFullData(container, true);

        // Activer l'affichage actives/inactives
        document.querySelector(".upperBar .menu > *:nth-child(2)").addEventListener("click", function (event) {
            console.log(event.currentTarget.innerText);
            loadFullData(container, event.currentTarget.innerText === "Actives");
            event.currentTarget.innerHTML = "<a>" + (event.currentTarget.innerHTML === "Actives" ? "Inactives" : "Actives") + "</a>";
            reinitializeSimple(container);
        });

        // Activation tri et filtres.
        sortSimple(container);
        filterSimple(container);

        // Clique sur le bouton de réinitialisation.
        container.querySelector(".lowerBar .menu div:nth-child(1)").addEventListener("click", function () {
            reinitializeSimple(container);
            window.scrollTo(0, 0);
        });
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
        "_action": container.querySelector('[name="_action"]').value,
        "active": active
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
    }).then(res => res.json())
        // Si l'appel s'est bien passé.
        .then(data => {
            container.querySelector("[name='flytable_data']").value = JSON.stringify(data.list);
            populateSimpleTable(container);
            container.querySelector("#count").innerHTML = data.list.length;
            container.querySelector("#countTotal").innerHTML = data.list.length;
        });
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
 * Méthode table simple.
 */

/**
 * Méthode qui va remplir le tableau simple.
 * @param {Element} container 
 */
const populateSimpleTable = (container) => {

    // Effacement de tout le corps de la table.
    container.querySelector(".body").innerHTML = "";

    // Récupération des données & champs de la table depuis le json dans le html, ainsi que le type des objets manipulés dans l'élément Fly Table.
    let data = JSON.parse(container.querySelector("input[type='hidden'][name='flytable_data']").value);
    let fields = Object.entries(JSON.parse(container.querySelector("input[type='hidden'][name='flytable_fields']").value));
    let type = window.location.pathname.substring(1);

    // Pour chaque entité manipulée.
    data.forEach((element) => {

        // Création de la ligne avec l'ajout de sa classe et son ajout dans le corps de la table.
        const row = document.createElement("div");
        row.classList.add("row");
        container.querySelector(".body").appendChild(row);

        // Récupération de l'identifiant unique pour le formulaire de suppression.
        const id = uniqid();

        // Pour chaque champ de l'entité.
        fields.forEach(field => {

            // Création de la cellule du champ, avec sa classe et son attribut col.
            const cell = document.createElement("div");
            cell.classList.add("cell");
            cell.setAttribute("col", field[0]);

            // Le champ n'est pas une date.
            if (field[1].split("_").length === 1 && field[1] !== "birthday") {

                // Initialisation des options
                let options;
                if (classes.includes(field[1]))
                    options = JSON.parse(container.querySelector("[name='fly_" + field[1] + "s']").value)[element[field[1]] - 1];

                // Récupération du tooltip du nombre de fanfictions s'il existe.
                const tooltip = Object.hasOwn(element, "fanfictions") && element.fanfictions.length > 0 ? "data-tooltip='View " + element.fanfictions.length + " fanfiction" + (element.fanfictions.length > 1 ? "s" : "") + "'" : "";

                // Récupération de l'icone des fanfictions si elles existent.
                const icon = Object.hasOwn(element, "fanfictions") && element.fanfictions.length > 0 ? "<span class='material-symbols-outlined'>library_books</span>" : "";

                // Récupération de la donnée du champ en fonction si la donnée est une classe ou non.
                let data;
                if (classes.includes(field[1]) && options !== undefined)
                    data = "<span>" + JSON.parse(container.querySelector("[name='fly_" + field[1] + "s']").value)[element[field[1]] - 1].nom + "</span>"
                else
                    data = "<span>" + element[field[1]] + "</span>";

                // Si le champ rempli est le second ou le troisieme de la ligne, c'est un champ de nom et/ou d'informations pouvant être longues et il peut être coupé.
                if (field[0] === '1' || field[0] === '2')
                    data = data.replace("<span>", "<span class='cut'>");

                // Remplissage de la cellule.
                cell.innerHTML = (field[0] === '1' ? "<a target='_blank' " + tooltip + "  href='/" + type + "/filter-redirect/" + element.id + "'>" + data + icon + "</a>" : data);

                // Cellule ajoutée à la ligne.
                row.appendChild(cell);

            } else if (field[1].split("_").length === 2 && field[1].split("_")[0] !== "is" || field[1] === "birthday") {

                // Récupération de la donnée au format date (fr-FR)
                const data = new Date(element[field[1]]).toLocaleString('fr-FR', { timeZone: 'UTC' });

                // Remplissage de la cellule.
                cell.innerHTML = "<span data-tooltip='" + data + "'>" + data.split(" ")[0] + "</span>";

                // Cellule ajoutée à la ligne.
                row.appendChild(cell);
            }
        });

        // Création du champ action avec l'ajout de sa claque et son ajout dans la ligne de l'entité.
        const action = document.createElement("div");
        action.classList.add("cell");
        row.appendChild(action);

        // Ajout des boutons & formulaires dans le champ action.
        action.innerHTML += "<a data-tooltip='View " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' href='/" + type + "/view/" + element.id + "'><span class='material-symbols-outlined'>Visibility</span></a>";
        action.innerHTML += "<a data-tooltip='Edit " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' href='/" + type + "/edit/" + element.id + "'><span class='material-symbols-outlined'>Edit</span></a>";
        if (window.location.search === "") {
            action.innerHTML += "<form name='post_" + id + "' style='display:none;' method='post' action='/" + type + "/delete/" + element.id + "'><input type='hidden' name='_method' value='POST'><input type='hidden' name='_csrfToken' autocomplete='off' value='" + container.querySelector("[name]").value + "'></form>";
            action.innerHTML += "<a data-tooltip='Delete " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' data-confirm-message='Are your sure to delete " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' href='#' onclick='if (confirm(this.dataset.confirmMessage)) { document.post_" + id + ".submit();} event.returnValue = false; return false;'><span class='material-symbols-outlined'>Delete</span></a>";
        } else {
            action.innerHTML += "<form name='post_" + id + "' style='display:none;' method='post' action='/" + type + "/restore/" + element.id + "'><input type='hidden' name='_method' value='POST'><input type='hidden' name='_csrfToken' autocomplete='off' value='" + container.querySelector("[name]").value + "'></form>";
            action.innerHTML += "<a data-tooltip='Restore " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' data-confirm-message='Are your sure to restore " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' href='#' onclick='if (confirm(this.dataset.confirmMessage)) { document.post_" + id + ".submit();} event.returnValue = false; return false;'><span class='material-symbols-outlined'>restore_page</span></a>";
        }

    });
}

/**
 * Méthode qui active le tri pour chaque élément non action de la ligne des colonnes.
 * @param {Element} container 
 */
const sortSimple = (container) => {
    // Récupération des noms de colonnes
    let headers = Array.from(container.querySelectorAll(".columns .column:not(.action)"));

    // Parcours des noms de colonne.
    headers.forEach((header) => {

        // Ajout du tooltip pour le tri par colonne.
        header.setAttribute("data-tooltip", "Sort by " + header.innerHTML.trim().toLowerCase());

        // Ajout du paramètre pour que la bulle d'aide est en dessous.
        header.setAttribute("data-tooltip-direction", "bottom");

        // Ajout de la gestion du click sur le nom de colonne.
        header.addEventListener("click", function (event) {
            // Modification de la table de tri.
            setSimpleSortOrder(event.currentTarget);

            // Initialisation de l'indication si l'affichage a changé pour la colonne.
            let updated = false;

            // Parcours des noms de colonne.
            headers.forEach((colonne) => {

                // Parcours des valeurs de la table de tri.
                sortTable.forEach((sortColonne, sortIndex) => {

                    // Si le nom de la colonne est celle de table de tri.
                    if (colonne.innerHTML.trim().toLowerCase() === sortColonne[0]) {
                        // Ajout  du sens de tri.
                        colonne.setAttribute("sort", sortColonne[1]);

                        // Ajout de la colonne dans l'ordre de tri.
                        colonne.setAttribute("sort-order", parseInt(sortIndex) + 1);

                        // Si le header cliqué est la colonne triée, alors indication que l'affichage a changé.
                        if (header.innerHTML.trim().toLowerCase() === colonne.innerHTML.trim().toLowerCase()) updated = true;
                    }
                });
            });

            // Si l'affichage de clonne n'a pas changé, retirage de toutes les indications de tri sur la colonne cliquée.
            if (!updated) {
                header.removeAttribute("sort-order");
                header.removeAttribute("sort");
            }

            // Tri des lignes du corps de la table à partir de la table de tri.
            sortSimpleTable(container);
        });
    });
}

/**
 * Méthode pour créer les données du tableau de tri.
 * @param {Element} column 
 */
const setSimpleSortOrder = (column) => {

    // Récupération du nom de la colonne en minuscule.
    let columnName = column.innerHTML.trim().toLowerCase();

    // Si la table a au moins un tri.
    if (sortTable.length > 0) {

        // Initialisation de l'indication que la table a été modifiée.
        let updated = false;

        // Parcours de la table de tri.
        sortTable.forEach((range, key) => {
            // Si la colonne en cours de tri est déjà triée
            if (range[0] === columnName) {
                // Tri ascendant remplacé par tri descendant.
                if (range[1] === "ASC")
                    range[1] = "DESC";
                else
                    // Tri descendant supprimé de la table de tri.
                    sortTable.splice(key, 1);

                // Table de tri bien modifiée.
                updated = true;
            }
        });
        // La table de tri n'a pas été modifiée, il faut donc ajouter le tri courant.
        if (!updated) sortTable.push([columnName, "ASC", column.getAttribute("col"), column.classList[1]]);

    } else
        // Table de tri vide, ajout du tri courant.
        sortTable.push([columnName, "ASC", column.getAttribute("col"), column.classList[1]]);
}

/**
 * Méthode qui trie la table simple en fonction des données du tableau de tri.
 * @param {Element} container 
 */
const sortSimpleTable = (container) => {
    // Parcours de toutes les lignes du corps du tableau.
    Array.from(container.querySelectorAll(".body .row"))
        // Tri de ces lignes.
        .sort(compareRow)
        .forEach(row => container.querySelector(".body").appendChild(row));
}

/**
 * Méthode qui compare les lignes a & b en fonction du contenu du tableau de tri.
 * @param {Element} a Premiere ligne à comparer.
 * @param {Element} b Deuxieme ligne à comparer.
 * @returns Indication la ligne a et b sont équivalentes sous forme de nombre (a > 0 < b)
 */
const compareRow = (a, b) => {
    // Par défaut, comparaison de valeurs égales.
    let compareResult = 0;

    // Si la table de comparaison n'est pas vide.
    if (sortTable.length > 0) {

        // Parcours de la table de tri.
        for (const sortColumn of sortTable) {

            // Initialisation si le sens de tri est ascendant ou descendant.
            let asc = sortColumn[1] === "ASC";

            // Récupération de l'index de la colonne triée.
            let idx = Number.parseInt(sortColumn[2]);

            // Initialisation des valeurs à comparer.
            let v1, v2;

            // En fonction du type de donnée.
            switch (sortColumn[3]) {

                // Comparaison de date.
                case "datetime":
                    compareResult = getDateValue(asc ? a : b, idx) - getDateValue(asc ? b : a, idx);
                    break;

                // Comparaison nom, nom de classe, text
                case "string":
                case "text":
                    v1 = getStringValue(asc ? a : b, idx);
                    v2 = getStringValue(asc ? b : a, idx);
                    compareResult = v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
                    break;

                // Comparaison nombres.
                case "integer":
                    v1 = getNumberValue(asc ? a : b, idx);
                    v2 = getNumberValue(asc ? b : a, idx);
                    compareResult = v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
                    break;
                default:
                    break;
            }
            // Si la comparaison n'est pas similaire, on retourne la comparaison.
            if (compareResult !== 0) return compareResult;
        }
    } else
        // Table de comparaison vide, tri par l'idenfitiant.
        compareResult = getNumberValue(a, 0) - getNumberValue(b, 0);

    // Retourne la valeur de comparaison.
    return compareResult;
}

/**
 * Méthode qui active le filtre pour chaque élément non action de la ligne des filtres.
 * @param {Element} container 
 */
const filterSimple = (container) => {
    // Parcours des champs de filtrage.
    Array.from(container.querySelectorAll(".filters .filter:not(.action) input"))
        .forEach((input) => {
            // Ajout de la gestion de l'input d'une touche.
            input.addEventListener("input", function () {

                // Remplissage de la table de filtrage et activation du filtre.
                fillFilterTable(container, input);
            });
        });

    // Parcours des selecteurs de filtrage.
    Array.from(container.querySelectorAll(".filters .filter:not(.action) select"))
        .forEach((select) => {
            // Ajout de la gestion du changement de valeur du sélecteur.
            select.addEventListener("change", function (event) {

                // Remplissage de la table de filtrage et activation du filtre.
                fillFilterTable(container, null, select);
            });
        });
}

/**
 * Méthode pour remplir la table de filtrage.
 * @param {Element} container 
 * @param {Element} input 
 * @param {Element} select 
 */
const fillFilterTable = (container, input = null, select = null) => {
    // Récupération de la valeur du champ.
    let filterContent = input !== null ? input.value : select.selectedOptions[0].innerText;

    // Récupération de l'index de la colonne.
    let col = input !== null ? input.parentElement.getAttribute("col") : select.parentElement.getAttribute("col");

    // Si le tableau de filtrage n'est pas vide.
    if (filterTable.length > 0) {

        // Initialisation de l'indication si la table de filtrage a été update.
        let updated = false;

        // Parcours de la table de filtrage
        filterTable.forEach((search) => {

            // Si la colonne parcourue de la table de tri est celle modifiée actuellement
            if (search[0] === col) {

                // Modification du contenu filtré et modification de l'indication que la colonne a été update.
                search[1] = filterContent;
                updated = true;
            }
        });

        // Si la table de tri n'a pas été modifiée, la colonne et la valeur de filtre sont ajoutée à la table de filtre.
        if (!updated) filterTable.push([col, filterContent]);
    } else

        // La table de tri est vide, la colonne et la valeur de filtre sont ajoutée à la table de filtre.
        filterTable.push([col, filterContent]);

    // Les lignes du tableau sont modifiées en fonction de la table de filtrage.
    filterSimpleTable(container);
}

/**
 * Méthode pour filtrer une table simple.
 * @param {Element} container 
 */
const filterSimpleTable = (container) => {
    // Parcours des lignes du corps du tableau.
    Array.from(container.querySelectorAll(".body .row"))
        .forEach(row => filterByTable(row));

    // Décompte du nombre de lignes visible.
    countVisibleRow(container);
}

/**
 * Méthode qui affichage ou cache une ligne en fonction du contenu du tableau de filtrage.
 * @param {Element} row 
 */
const filterByTable = (row) => {
    // Initialisation de l'indication si la ligne est visible ou non.
    let visible = true;

    // Parcours de la table des filtres.
    filterTable.forEach((filterColumn) => {

        // Récupération du contenu de la cellule filtrée.
        let cellContent = row.querySelector(".cell:nth-child(" + (Number.parseInt(filterColumn[0]) + 1) + ")").textContent;

        // Construction de l'expression régulière à utiliser pour la comparaison.
        let regex = new RegExp("(?:.*\\b(" + filterColumn[1].toLowerCase().split(" ").join("))(?:.*\\b(") + "))", "gi");

        // Valorisation de l'indication si la cellule correspond au filtre ou non.
        visible = visible && regex.test(cellContent.toLowerCase());
    });

    if (!visible)
        // Si l'indication indique une ligne invisible, la ligne est changée en ligne invisible.
        row.style.display = "none";
    else

        // Sinon l'indication indique une ligne visible, la ligne est changée en ligne visible.
        row.removeAttribute("style");
}

/**
 * Méthode de réinitialisation de la table simple (filtres et tri enlevé).
 * @param {Element} container 
 */
const reinitializeSimple = (container) => {
    // Tous les champs filtres sont vidés.
    container.querySelectorAll(".filters .filter input").forEach((input) => { input.value = ""; });

    // Tous les sélecteurs sont mis à vide.
    container.querySelectorAll(".filters .filter select").forEach((select) => { select.selectedIndex = ""; });

    // Parcours des entêtes de colonnes.
    container.querySelectorAll(".columns .column").forEach((column) => {

        // Suppression de toutes les indications de tri, sens de tri & ordre de tri.
        column.removeAttribute("sort");
        column.removeAttribute("sort-order");
    });

    // Vidage des tables de tri et de filtre.
    sortTable = [];
    filterTable = [];

    // Nouveau tri de la table.
    sortSimpleTable(container);

    // Nouveau filtre de la table.
    filterSimpleTable(container);
}

/**
 * Méthodes de comparaison de données par type
 */

/**
 * Méthode qui récupère le contenu string d'une cellule.
 * @param {Element} tr 
 * @param {number} idx 
 * @returns Le contenu string de la cellule cherchée.
 */
const getStringValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

/**
 * Méthode qui récupère le contenu date d'une cellule.
 * @param {Element} tr 
 * @param {number} idx 
 * @returns Le contenu date de la cellule cherchée.
 */
const getDateValue = (tr, idx) => {
    let dateParts = tr.children[idx].innerText.split("/");
    return new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]).getTime();
};

/**
 * Méthode qui récupère le contenu number d'une cellule.
 * @param {Element} tr 
 * @param {number} idx 
 * @returns le contenu number de la cellule cherchée.
 */
const getNumberValue = (tr, idx) => Number.parseFloat(tr.children[idx].innerText) || Number.parseFloat(tr.children[idx].textContent);