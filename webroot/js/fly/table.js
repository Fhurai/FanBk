let sortTable = [];
let filterTable = [];
let classes = ["auteur", "fandom", "fanfiction", "langage", "relation", "serie", "tag"];

/**
 * Le contenu de la page est chargé.
 */
document.addEventListener("DOMContentLoaded", function () {

    if (hasFlyTable()) {
        Array.from(document.querySelectorAll(".flytable"))
            .forEach(activateFlyTable);
    }
});

const hasFlyTable = () => document.querySelectorAll(".flytable").length > 0;

const activateFlyTable = (container) => {
    /**
     * Evenements communs.
     */
    document.querySelector(".upperBar .menu >*:last-child").addEventListener("click", function () {
        window.scrollTo(0, document.body.scrollHeight);
    });
    document.querySelector(".lowerBar .menu >*:last-child").addEventListener("click", function () {
        window.scrollTo(0, 0);
    });
    container.querySelector(".lowerBar .menu div:nth-child(1)").addEventListener("click", function () {
        reinitialize(container);
        window.scrollTo(0, 0);
    });

    /**
     * Table simple.
     */
    populateSimpleTable(container);
    sortSimple(container);
    container.querySelector(".columns .column[col='1']").click();
    filterSimple(container);

    /**
     * Table complexe.
     */
}

const populateSimpleTable = (container) => {
    container.querySelector(".body").innerHTML = "";
    let data = JSON.parse(container.querySelector("input[type='hidden'][name='flytable_data']").value);
    let fields = Object.entries(JSON.parse(container.querySelector("input[type='hidden'][name='flytable_fields']").value));
    let type = window.location.pathname.substring(1);

    data.forEach((element) => {
        const row = document.createElement("div");
        const id = uniqid();
        row.classList.add("row");
        container.querySelector(".body").appendChild(row);

        fields.forEach(field => {

            const cell = document.createElement("div");
            cell.classList.add("cell");
            cell.setAttribute("col", field[0]);

            if (field[1].split("_").length === 1 && field[1] !== "birthday") {
                let options;
                if (classes.includes(field[1])) {
                    options = JSON.parse(container.querySelector("[name='fly_" + field[1] + "s']").value)[element[field[1]] - 1];
                }
                const data = (classes.includes(field[1]) && options !== undefined) ? JSON.parse(container.querySelector("[name='fly_" + field[1] + "s']").value)[element[field[1]] - 1].nom : element[field[1]];

                let tooltip = Object.hasOwn(element, "fanfictions") && element.fanfictions.length > 0 ? "data-tooltip='View " + element.fanfictions.length + " fanfiction" + (element.fanfictions.length > 1 ? "s" : "") + "'" : "";

                let icon = Object.hasOwn(element, "fanfictions") && element.fanfictions.length > 0 ? "<span class='material-symbols-outlined'>library_books</span></a>" : "";
                cell.innerHTML = (field[0] === '1' ? "<a target='_blank' " + tooltip + "  href='/" + type + "/filter-redirect/" + element.id + "'>" + data + icon : data);

                row.appendChild(cell);
            } else if (field[1].split("_").length === 2 && field[1].split("_")[0] !== "is" || field[1] === "birthday") {
                const data = new Date(element[field[1]]).toLocaleString('fr-FR', { timeZone: 'UTC' });
                cell.innerHTML = "<span data-tooltip='" + data + "'>" + data.split(" ")[0] + "</span>";

                row.appendChild(cell);
            }
        });

        const action = document.createElement("div");
        action.classList.add("cell");
        row.appendChild(action);

        action.innerHTML += "<a data-tooltip='View " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' href='/" + type + "/view/" + element.id + "'><span class='material-symbols-outlined'>Visibility</span></a>";
        action.innerHTML += "<a data-tooltip='Edit " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' href='/" + type + "/edit/" + element.id + "'><span class='material-symbols-outlined'>Edit</span></a>";
        action.innerHTML += "<form name='post_" + id + "' style='display:none;' method='post' action='/" + type + "/delete/" + element.id + "'><input type='hidden' name='_method' value='POST'><input type='hidden' name='_csrfToken' autocomplete='off' value='" + container.querySelector("[name]").value + "'></form>";
        action.innerHTML += "<a data-tooltip='Delete " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' data-confirm-message='Are your sure to delete " + (Object.hasOwn(element, "nom") ? element.nom : element.username) + "' href='#' onclick='if (confirm(this.dataset.confirmMessage)) { document.post_" + id + ".submit();} event.returnValue = false; return false;'><span class='material-symbols-outlined'>Delete</span></a>";

    });
}

const countVisibleRow = (container) => container.querySelector("#count").innerHTML = container.querySelectorAll(".table .body .row:not([style])").length;

const uniqid = (prefix = "", random = false) => {
    const sec = Date.now() * 1000 + Math.random() * 1000;
    const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");
    return `${prefix}${id}${random ? `.${Math.trunc(Math.random() * 100000000)}` : ""}`;
};

const setSimpleSortOrder = (column) => {

    let columnName = column.innerHTML.trim().toLowerCase();
    if (sortTable.length > 0) {
        let updated = false;
        sortTable.forEach((range, key) => {
            if (range[0] === columnName) {
                if (range[1] === "ASC")
                    range[1] = "DESC";
                else
                    sortTable.splice(key, 1);

                updated = true;
            }
        });
        if (!updated) sortTable.push([columnName, "ASC", column.getAttribute("col"), column.classList[1]]);

    } else
        sortTable.push([columnName, "ASC", column.getAttribute("col"), column.classList[1]]);
}

const sortSimpleTable = (container) => {
    Array.from(container.querySelectorAll(".body .row"))
        .sort(compareRow)
        .forEach(row => container.querySelector(".body").appendChild(row));
}

const compareRow = (a, b) => {
    let compareResult = 0;
    if (sortTable.length > 0) {
        for (const sortColumn of sortTable) {
            let asc = sortColumn[1] === "ASC";
            let idx = Number.parseInt(sortColumn[2]);
            let v1, v2;
            switch (sortColumn[3]) {
                case "datetime":
                    compareResult = getDateValue(asc ? a : b, idx) - getDateValue(asc ? b : a, idx);
                    break;
                case "string":
                    v1 = getStringValue(asc ? a : b, idx);
                    v2 = getStringValue(asc ? b : a, idx);
                    compareResult = v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
                    break;
                case "integer":
                    v1 = getNumberValue(asc ? a : b, idx);
                    v2 = getNumberValue(asc ? b : a, idx);
                    compareResult = v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
                    console.log(compareResult);
                    break;
                default:
                    break;
            }
            if (compareResult !== 0) return compareResult;
        }
    } else {
        compareResult = getNumberValue(a, 0) - getNumberValue(b, 0);
    }
    return compareResult;
}

const sortSimple = (container) => {
    let headers = Array.from(container.querySelectorAll(".columns .column:not(.action)"));
    headers.forEach((header) => {
        header.setAttribute("data-tooltip", "Sort by " + header.innerHTML.trim().toLowerCase());
        header.setAttribute("data-tooltip-direction", "bottom");
        header.addEventListener("click", function (event) {
            setSimpleSortOrder(event.currentTarget);

            let updated = false;
            headers.forEach((h, i) => {
                sortTable.forEach((sortC, sortI) => {
                    if (h.innerHTML.trim().toLowerCase() === sortC[0]) {
                        h.setAttribute("sort", sortC[1]);
                        h.setAttribute("sort-order", parseInt(sortI) + 1);
                        if (header.innerHTML.trim().toLowerCase() === h.innerHTML.trim().toLowerCase()) updated = true;
                    }
                });
            });

            if (!updated) {
                header.removeAttribute("sort-order");
                header.removeAttribute("sort");
            }

            sortSimpleTable(container);
        });
    });
}

const filterSimple = (container) => {
    Array.from(container.querySelectorAll(".filters .filter:not(.action) input"))
        .forEach((input) => {
            input.addEventListener("input", function (event) {
                let filterContent = input.value;
                let col = input.parentElement.getAttribute("col");
                if (filterTable.length > 0) {
                    let updated = false;
                    filterTable.forEach((search, key) => {
                        if (search[0] === col) {
                            search[1] = filterContent;
                            updated = true;
                        }
                    });
                    if (!updated) filterTable.push([col, filterContent]);
                } else
                    filterTable.push([col, filterContent]);

                filterSimpleTable(container);
            });
        });

    Array.from(container.querySelectorAll(".filters .filter:not(.action) select"))
        .forEach((select) => {
            select.addEventListener("change", function (event) {
                let col = select.parentElement.getAttribute("col");
                if (filterTable.length > 0) {
                    let updated = false;
                    filterTable.forEach((search, key) => {
                        if (search[0] === col) {
                            search[1] = event.currentTarget.selectedOptions[0].innerText;
                            updated = true;
                        }
                    });
                    if (!updated) filterTable.push([col, event.currentTarget.selectedOptions[0].innerText]);
                } else
                    filterTable.push([col, event.currentTarget.selectedOptions[0].innerText]);

                filterSimpleTable(container);
            });
        });
}

const filterSimpleTable = (container) => {
    Array.from(container.querySelectorAll(".body .row"))
        .forEach(row => filterByTable(row));
    countVisibleRow(container);
}

const filterByTable = (row) => {
    let visible = true;
    filterTable.forEach((filterColumn) => {
        let cellContent = row.querySelector(".cell:nth-child(" + (Number.parseInt(filterColumn[0]) + 1) + ")").textContent;
        // Construction de l'expression régulière à utiliser pour la comparaison.
        let regex = new RegExp("(?:.*\\b(" + filterColumn[1].toLowerCase().split(" ").join("))(?:.*\\b(") + "))", "gi");
        visible = visible && regex.test(cellContent.toLowerCase());
    });
    if (!visible)
        row.style.display = "none";
    else
        row.removeAttribute("style");
}

const reinitialize = (container) => {
    container.querySelectorAll(".filters .filter input").forEach((input) => { input.value = ""; });
    container.querySelectorAll(".filters .filter select").forEach((select) => { select.selectedIndex = ""; });
    container.querySelectorAll(".columns .column").forEach((column) => {
        column.removeAttribute("sort");
        column.removeAttribute("sort-order");
    });
    sortTable = [];
    filterTable = [];
    sortSimpleTable(container);
    filterSimpleTable(container);
}

/**
 * Méthodes de comparaison de données par type
 */

const getStringValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

const getDateValue = (tr, idx) => {
    let dateParts = tr.children[idx].innerText.split("/");
    return new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]).getTime();
};

const getNumberValue = (tr, idx) => Number.parseFloat(tr.children[idx].innerText) || Number.parseFloat(tr.children[idx].textContent);