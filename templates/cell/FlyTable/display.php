<?php

/**
 * @var array<Entity> $entities La liste des entités à afficher.
 * @var array<String> $columns Les colonnes du tableau.
 * @var array<String> $filters Les filtres du tableau.
 * @var array<String> $fields Les colonnes du tableau.
 * @var string $header L'entête du tableau.
 * @var string $type Type du tableau.
 * @var array $classes Les classes pouvant apparaîtres dans le tableau comme option de filtre.
 */
$classes = ["Auteurs", "Fandoms", "Fanfictions", "Langages", "Relations", "Series", "Tags"];
?>

<!-- Objet Fly Table. -->
<div class="flytable">

    <!-- Jeton CSRF pour les boutons de suppression et possibles modifications dans le futur. -->
    <input type="hidden" name='_csrf_<?= $header ?>' value="<?= $this->request->getAttribute("csrfToken") ?>">
    <input type="hidden" name='_object' value="<?= $url_object ?>">
    <input type="hidden" name='_action' value="g6">

    <!-- Partie haute de la table (Titre / Menu) -->
    <div class="upperBar">

        <!-- Titre du tableau avec décompte des entités présentes dans le tableau avec les filtres. -->
        <div class="header"><span id="count">0</span> <?= $header ?>s</div>

        <!-- Menu haut (New - Active/Inactive - Filters - Search - Sort) -->
        <div class="menu">

            <!-- New -->
            <div><a href="<?= $this->Url->build(["action" => "add"]) ?>">New</a></div>

            <!-- Active / Inactive -->
            <div>Inactives</div>


            <?php // Si le tableau est un tableau complex (avec beaucoup de données à présenter) 
            ?>
            <?php if ($type === "complex") : ?>

                <!-- Filtres (mène à un modal) -->
                <div>

                    <!-- Checkbox pour la modal de filtres. -->
                    <input type="checkbox" class="modalcheckbox" id="modalFilters">

                    <!-- Text à cliquer pour la modale de filtres. -->
                    <label for="modalFilters">Filters</label>

                    <!-- Fond noir de la modale -->
                    <div for="modalFilters" class="modalback"></div>

                    <!-- Fenêtre modal. -->
                    <div class="modalwindow">

                        <!-- Entête de la modale. -->
                        <div class="head">
                            <h3>Filters</h3>
                            <label for="modalFilters">x</label>
                        </div>

                        <!-- Contenu de la modale. -->
                        <div class="fieldset">

                            <?php // Parcours des options de filtre 
                            ?>
                            <?php foreach ($filtersOptions["property"] as $key => $property) : ?>

                                <?php if (in_array($property, ["Auteur", "Serie", "Fanfiction"])) : ?>

                                    <?php // Si sélecteur d'auteur, de série ou de fanfiction.  
                                    ?>
                                    <?= $this->element("fly/select", ["options" => $filtersOptions["options"][$key], "name" => str_replace("_", "", $key), "label" => "Filtrer par $property"]) ?>

                                <?php elseif (in_array($property, ["Fandom", "Personnage", "Relation", "Tag"])) : ?>

                                    <?php // Sinon si sélecteur de fandoms, de personnages, de relations ou de tags.  
                                    ?>
                                    <?= $this->element("fly/multiselect", ["options" => $filtersOptions["options"][$key], "name" => str_replace("_", "", $key), "label" => "Tri par $property"]) ?>
                                <?php endif; ?>

                                <?php // Fin parcours des options de filtre. 
                                ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Bouton de soumission du formulaire. -->
                        <?= $this->Form->button(__('Submit')) ?>
                    </div>
                </div>

                <!-- Recherche (mène à un modal) -->
                <div>
                    <!-- Checkbox pour la modal de de recherche. -->
                    <input type="checkbox" class="modalcheckbox" id="modalSearch">

                    <!-- Text à cliquer pour la modale de recherche. -->
                    <label for="modalSearch">Search</label>

                    <!-- Fond noir de la modale -->
                    <div for="modalSearch" class="modalback"></div>

                    <!-- Fenêtre modal. -->
                    <div class="modalwindow">

                        <!-- Entête de la modale. -->
                        <div class="head">
                            <h3>Search</h3>
                            <label for="modalSearch">x</label>
                        </div>

                        <!-- Contenu de la modale. -->
                        <div class="fieldset">

                            <?php // Champ valeur recherchée 
                            ?>
                            <?= $this->element("fly/text", ["name" => "searchvalue", "label" => "Valeur recherchée", "required" => true]) ?>

                            <?php // Liste du champ recherché pour la valeur. 
                            ?>
                            <?= $this->element("fly/select", ["options" => $searchOptions["property"], "name" => "searchfield", "label" => "Type de recherche", "required" => true]) ?>
                        </div>

                        <!-- Bouton de soumission du formulaire. -->
                        <?= $this->Form->button(__('Submit')) ?>
                    </div>
                </div>

                <!-- Tri (mène à un modal) -->
                <div>

                    <!-- Checkbox pour la modal de tri. -->
                    <input type="checkbox" class="modalcheckbox" id="modalSort">

                    <!-- Text à cliquer pour la modale de tri. -->
                    <label for="modalSort">Sort</label>

                    <!-- Fond noir de la modale -->
                    <div for="modalSort" class="modalback"></div>
                    <!-- Fenêtre modal. -->
                    <div class="modalwindow">

                        <!-- Entête de la modale. -->
                        <div class="head">
                            <h3>Sort</h3>
                            <label for="modalSort">x</label>
                        </div>

                        <!-- Contenu de la modale. -->
                        <div class="fieldset">

                            <?php foreach ($sortOptions["property"] as $key => $property) : ?>
                                <?php // Parcours des champs disponibles pour le tri et création du sélecteur pour chaque champ de tri. 
                                ?>
                                <?= $this->element("fly/select", ["options" => $sortOptions["order"], "name" => str_replace("_", "", $key), "label" => "Tri par $property"]) ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Bouton de soumission du formulaire. -->
                        <?= $this->Form->button(__('Submit')) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Bouton pour aller au pied de page (utile pour un tableau tres grand.) -->
            <div>▼</div>

            <!-- Fin Menu -->
        </div>

        <!-- Fin partie haute. -->
    </div>

    <?php // Si le tableau est simple (juste les données d'un objet à présenter) 
    ?>
    <?php if ($type === "simple") : ?>

        <!-- Table dans l'élément Fly Table. -->
        <div class="table simple">

            <!-- Entêtes du tableau. -->
            <div class="head">

                <!-- Colonnes de la table. -->
                <div class="columns">

                    <?php // Pour chaque colonne préparée de la table. 
                    ?>
                    <?php foreach ($columns as $key => $column) : ?>

                        <?php // Si la colonne ne concerne pas un booléen. 
                        ?>
                        <?php if ($filters[$key] !== "boolean") : ?>

                            <!-- Colonne -->
                            <div class="column <?= (in_array(ucfirst($columns[$key] . "s"), $classes)) ? "string" : $filters[$key] ?>" col="<?= $key ?>"><?= trim(ucfirst($column)) ?></div>

                            <?php // Fin de la condition. 
                            ?>
                        <?php endif; ?>

                        <?php // Fin des colonnes 
                        ?>
                    <?php endforeach; ?>

                    <!-- Colonne des boutons. -->
                    <div class="column action">Action</div>

                    <!-- Fin des colonnes -->
                </div>

                <!-- Filtres de la table. -->
                <div class="filters">

                    <?php // Pour chaque filtre préparé avec sa clé 
                    ?>
                    <?php foreach ($filters as $key => $filter) : ?>

                        <?php // Si le filtre n'est pas pour une colonne booléen. 
                        ?>
                        <?php if ($filters[$key] !== "boolean") : ?>

                            <!-- Filtre -->
                            <div class="filter <?= $filter ?>" col="<?= $key ?>">

                                <?php // Si le filtre concerne une classe (objet dans un objet). 
                                ?>
                                <?php if (in_array(ucfirst($columns[$key] . "s"), $classes)) : ?>

                                    <?php // Création de la variable pour l'appeler pour générer les options. 
                                    ?>
                                    <?php $variable = $columns[$key] . "s"; ?>

                                    <!-- Select de filtre -->
                                    <select>
                                        <!-- Option vide -->
                                        <option></option>

                                        <?php // Pour chaque entité, création de l'option correspondante. 
                                        ?>
                                        <?php foreach ($$variable as $option) : ?>
                                            <option value="<?= $option->value ?>"><?= $option->nom ?></option>

                                            <?php // Fin de l'option. 
                                            ?>
                                        <?php endforeach; ?>

                                        <!-- Fin du select -->
                                    </select>
                                    <?php // Si le filtre ne concerne pas une classe. 
                                    ?>
                                <?php else : ?>

                                    <!-- Input de filtre. -->
                                    <input type="<?= (in_array($filter, ["integer"]) ? "number" : "text") ?>" placeholder="<?= ($filter === "datetime" ? "dd/mm/yyyy" : (in_array($filter, ["integer"]) ? "00000" : "Enter your search")) ?>" col="<?= $key ?>" <?= (in_array($filter, ["integer"]) ? "min='1'" : "") ?> />

                                    <?php // Fin des inputs / select pour les filtres. 
                                    ?>
                                <?php endif; ?>

                                <!-- Fin du filtre -->
                            </div>

                            <?php // Fin du filtre. 
                            ?>
                        <?php endif; ?>

                        <?php // Fin des filtres 
                        ?>
                    <?php endforeach; ?>

                    <!-- Filtre de la colonne action -->
                    <div class="filter action"><input /></div>

                    <!-- Fin des filtres -->
                </div>

                <!-- Fin de l'entête du tableau. -->
            </div>

            <!-- Corps du tableau. -->
            <div class="body"></div>


            <div>
                <!-- Données du tableau. -->
                <input name="flytable_data" type="hidden">

                <!-- Champs du tableau. -->
                <input name="flytable_fields" type="hidden" value="<?= h(json_encode($fields)) ?>">

                <?php // Pour toutes les classes possibles dans le tableau. 
                ?>
                <?php foreach ($classes as $class) : ?>

                    <?php // Si la classe est set comme donnée manipulable. 
                    ?>
                    <?php if (isset(${strtolower($class)})) : ?>
                        <!-- Donnée <?php $class ?> -->
                        <input name="fly_<?= strtolower($class) ?>" type="hidden" value="<?= h(json_encode(${strtolower($class)})) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Fin tableau. -->
        </div>

        <?php // Si le tableau est complexe (avec beaucoup de données à présenter.) 
        ?>
    <?php elseif ($type === "complex") : ?>

        <!-- Table dans l'élément Fly Table. -->
        <div class="table complex">

            <!-- Corps du tableau (laisser vide car il est modifié par javascript.) -->
            <div class="body"></div>

            <div>
                <!-- Données du tableau. -->
                <input name="flytable_data" type="hidden">

                <!-- Données des filtres (tirées de la session). -->
                <input name="filters_data" type="hidden" value="<?= h(json_encode(array_key_exists("filters", $session) ? $session["filters"] : [])) ?>">

                <!-- Données de la recherche (tirées de la session). -->
                <input name="search_data" type="hidden" value="<?= h(json_encode(array_key_exists("search", $session) ? $session["search"] : [])) ?>">
                <input name="sort_data" type="hidden" value="<?= h(json_encode(array_key_exists("sort", $session) ? $session["sort"] : [])) ?>">
            </div>
        </div>
    <?php endif; ?>

    <!-- Partie basse de la table (Footer / Menu) -->
    <div class="lowerBar">

        <!-- Footer table (pour toujours avoir le décompte total des entités.) -->
        <div class="footer">
            <div title="<?= h(json_encode($session)) ?>"><?= empty($session) ? "Empty" : "Parameters in" ?> session</div>
        </div>

        <!-- Menu bas (Reinitialisation) -->
        <div class="menu">

            <?php if ($type === "complex") : ?>
                <?php // Si le tableau est un tableau complex (fanfictions, séries), alors bouton de réinitialisation des données
                ?>
                <div><?= $this->Html->link("Clear session", ["action" => "reinitialize"]) ?></div>
            <?php endif; ?>

            <!-- Bouton de réinitialisation du tableau. -->
            <div>Reinitialize</div>

            <!-- Bouton pour remonter la page. -->
            <div>▲</div>
        </div>
    </div>
</div>

<!-- Imports pour le fonctionnement de l'élément Fly Table. -->

<?php // Import css table pour les deux types de table 
?>
<?= $this->Html->css(["fly/table"]) ?>

<?php if ($type === "complex") : ?>
    <?php // Imports pour la table complex. 
    ?>
    <?= $this->Html->script(["fly/complextable"]) ?>
    <?= $this->Html->css(["fly/select"]) ?>
    <?= $this->Html->script(["fly/select"]) ?>
    <?= $this->Html->css(["fly/textarea"]) ?>
    <?= $this->Html->script(["fly/textarea"]) ?>

<?php elseif ($type === "simple") : ?>
    <?php // Imports pour la table simple. 
    ?>
    <?= $this->Html->script(["fly/simpletable"]) ?>
<?php endif; ?>