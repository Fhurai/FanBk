/**
 * Evenement de chargement de la page.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Récupération des listes à la volée disponibles.
    let listes = getFlylists();


    if (listes.length > 0) {// Si au moins une liste existe.
        listes.forEach((list) => {
            //Récupéraiton du bouton d'ajout dans la liste.
            let addButton = list.querySelector("div.addButton");

            addButton.addEventListener("click", function () {// Au click sur le bouton d'ajout

                // Récupération de la ligne du bouton d'ajout
                let addTr = addButton.parentElement.parentElement;

                // Création de la nouvelle ligne.
                let ligne = createTr(addTr.parentElement);

                // Ajout de la nouvelle ligne dans la liste juste avant la ligne du bouton d'ajout.
                addTr.parentElement.insertBefore(ligne, addTr);
            });

            // Parcours des lignes de la liste
            Array.from(list.children[1].children).forEach((ligne) => {

                if (ligne.childElementCount !== 1) {// Si la ligne n'est pas celle du bouton d'ajout, ajout du bouton de suppression en fin de ligne.
                    addDeleteButton(ligne);
                }
            });
            
            if (window.location.pathname.split("/").length === 3 && window.location.pathname.split("/")[2] === "add") {
                addButton.click();
            }
        });
    }

    let selectListes = getFlySelectlists();

    if (selectListes.length > 0) {// Si au moins une liste existe.
        selectListes.forEach((list) => {
            //Récupéraiton du bouton d'ajout dans la liste.
            let addButton = list.querySelector("div.addButton");

            addButton.addEventListener("click", function () {// Au click sur le bouton d'ajout

                // Récupération de la ligne du bouton d'ajout
                let addTr = addButton.parentElement.parentElement;

                // Création de la nouvelle ligne.
                let ligne = createSelectTr(addTr.parentElement);

                // Ajout de la nouvelle ligne dans la liste juste avant la ligne du bouton d'ajout.
                addTr.parentElement.insertBefore(ligne, addTr);
            });

            // Parcours des lignes de la liste
            Array.from(list.children[1].children).forEach((ligne) => {

                if (ligne.childElementCount !== 1) {// Si la ligne n'est pas celle du bouton d'ajout, ajout du bouton de suppression en fin de ligne.
                    addDeleteButton(ligne);
                }
            });
            addButton.click();
        });
    }
});

/**
 * 
 * @returns {NodeList} Liste des listes à la volée
 */
function getFlylists() {
    return document.querySelectorAll("table.flylist");
}

/**
 * 
 * @returns {NodeList} Liste des select listes à la volée
 */
function getFlySelectlists() {
    return document.querySelectorAll("table.flyselectlist");
}

/**
 * Crée une ligne de liste
 * 
 * @param {HTMLTableSectionElement} tbody 
 * 
 * @returns {HTMLTableRowElement}
 */
function createTr(tbody) {
    // Récupération du type de la liste (aussi son titre)
    let title = tbody.lastElementChild.querySelector("div").title;

    // Libellé de la ligne
    let th = document.createElement("th");
    th.innerText = tbody.childElementCount;

    // Input de la ligne.
    let td = document.createElement("td");
    td.innerHTML = "<input name='" + title + "[" + tbody.childElementCount + "]' required='required' />";

    // Création de ligne, ajout des élements de la ligne pour retourner la ligne.
    let tr = document.createElement("tr");
    tr.appendChild(th);
    tr.appendChild(td);

    // Ajout du bouton de suppression si plus d'un élément dans la liste.
    if (tbody.childElementCount > 1) addDeleteButton(tr);
    else {
        // Ajout d'un élement de tableau avec émote pour l'apparence générale du tableau au click.
        td = document.createElement("td");
        td.innerHTML = "<div>👀</div>";
        tr.appendChild(td);
    }

    // Retourne la nouvelle ligne de liste
    return tr;
}

/**
 * Crée une ligne de liste
 * 
 * @param {HTMLTableSectionElement} tbody 
 * 
 * @returns {HTMLTableRowElement}
 */
function createSelectTr(tbody) {
    // Récupération du type de la liste (aussi son titre)
    let title = tbody.lastElementChild.querySelector("div").title;

    // Libellé de la ligne
    let th = document.createElement("th");
    th.innerText = tbody.childElementCount;

    // Select de la ligne.
    let td = document.createElement("td");
    let selectValues = JSON.parse(tbody.parentElement.ariaSelected);

    let select = document.createElement("select");
    let option, optGroup = null;
    select.name = title + "[" + tbody.childElementCount + "]";
    select.appendChild(document.createElement("option"));
    Object.entries(selectValues).forEach((auteur) => {
        optGroup = document.createElement("optGroup");
        optGroup.label = auteur[0];
        Object.entries(auteur[1]).forEach((fanfiction) => {
            option = document.createElement("option");
            option.value = fanfiction[0];
            option.innerText = fanfiction[1];
            optGroup.appendChild(option);
        });
        select.appendChild(optGroup);
    });
    td.appendChild(select);

    // Création de ligne, ajout des élements de la ligne pour retourner la ligne.
    let tr = document.createElement("tr");
    tr.appendChild(th);
    tr.appendChild(td);

    // Ajout du bouton de suppression si plus d'un élément dans la liste.
    if (tbody.childElementCount > 1) addDeleteButton(tr);
    else {
        // Ajout d'un élement de tableau avec émote pour l'apparence générale du tableau au click.
        td = document.createElement("td");
        td.innerHTML = "<div>👀</div>";
        tr.appendChild(td);
    }

    // Retourne la nouvelle ligne de liste
    return tr;
}

/**
 * Ajoute un bouton de suppression à l'élément de la liste.
 * 
 * @param {HTMLTableRowElement} trow 
 */
function addDeleteButton(trow) {

    if (trow.childElementCount !== 1) {//La ligne de la liste n'est pas la ligne d'ajout
        // Création du bouton de suppression.
        let td = document.createElement("td");
        td.innerHTML = "<div style='cursor: pointer'>🗑️</div>";

        // Ajout de l'event click avec la suppression de l'élément de la liste (avec un message de confirmation).
        td.children[0].addEventListener("click", function () {
            if (confirm("Voulez vous supprimer l'option n°" + trow.children[0].innerText))
                trow.parentElement.removeChild(trow);
        });

        // Ajout du bouton de suppression à la ligne donnée en paramètre.
        trow.appendChild(td);
    }
}