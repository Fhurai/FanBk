/**
 * Variables globales
 */
var count = 0;

/**
 * Retourne le tableau dans la page.
 * @returns {HTMLTableElement}
 */
const getTable = () => document.querySelector(".table-responsive table");

/**
 * Retourne si la table a des champs de recherche ou non.
 * 
 * @param {HTMLTableElement} table 
 * 
 * @returns {boolean}
 */
const isSearchable = (table) => table !== null && table.children.length > 0 && table.children[0].children.length == 2;

/**
 * 
 * @param {HTMLTableRowElement} tr 
 * @param {Number} idx 
 * @param {String} value 
 * @returns {void}
 */
const hideNotFittingTr = (tr, idx, value) => tr.style.visibility = !getCellValue(tr, idx).toLowerCase().includes(value.toLowerCase()) ? "collapse" : "visible";

/**
 * 
 * @param {HTMLTableRowElement} tr 
 * @returns {Number}
 */
const countVisibleTr = (tr) => tr.style.visibility === "visible" ? count++ : count;

/**
 * 
 * @param {HTMLTableRowElement} tr 
 * @param {Number} idx 
 * @returns {String}
 */
const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

/**
 * 
 * @param {Number} idx 
 * @param {boolean} asc 
 * @returns {boolean}
 */
const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
    v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
)(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

/**
 * Ajoute l'event listener pour l'input dans un champ de recherche dans un paginator.
 */
function addInputEventListener() {
    document.querySelectorAll('table tr:nth-child(2) th input').forEach(input => input.addEventListener('input', (() => {
        // Décompte d'éléments dans le tableau à 0.
        count = 0;

        // Récupération de la table.
        const table = input.closest('table');
        
        // Tous les éléments ne correspondant pas à l'entrée dans l'input sont cachés.
        Array.from(table.querySelectorAll('tbody tr'))
            .forEach(tr => hideNotFittingTr(tr, Array.from(input.parentElement.parentElement.children).indexOf(input.parentElement), input.value));

        // Décompte d'éléments visibles dans le tableau
        Array.from(table.querySelectorAll('tbody tr'))
            .forEach(tr => countVisibleTr(tr));

        // Le nombre d'éléments visibles est affiché dans la page.
        document.querySelector("h3 span").innerHTML = count;
    })));
}

/**
 * Ajoute l'event listener pour le click sur l'entete dans un paginator.
 * 
 * @param {HTMLInputElement} input 
 */
function addSortEventListener() {
    document.querySelectorAll('table tr:first-child th').forEach(th => th.addEventListener('click', (() => {
        // Récupération de la table.
        const table = th.closest('table');

        // Tri de la table à partir de l'entête utilisé pour le tri.
        Array.from(table.querySelectorAll('tbody tr'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
            .forEach(tr => table.querySelector("tbody").appendChild(tr));
    })));
}


/**
 * Evenement de chargement de la page.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Récupération de la table sur la page.
    let table = getTable();

    if (isSearchable(table)) {// La table a des champs de recherche

        // Ajout des évènements d'entrées dans les champs de recherche.
        addInputEventListener();

        // Ajout des évènements de tri des colonnes.
        addSortEventListener();

        // Click sur l'entête de la première colonne.
        document.querySelector("th").click();
    }
});