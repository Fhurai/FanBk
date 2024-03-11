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

    <!-- Partie haute de la table (Titre / Menu) -->
    <div class="upperBar">

        <!-- Titre du tableau avec décompte des entités présentes dans le tableau avec les filtres. -->
        <div class="header"><span id="count"><?= count($entities) ?></span> <?= $header ?></div>

        <!-- Menu haut (New - Active/Inactive - Filters - Search - Sort) -->
        <div class="menu">

            <!-- New -->
            <div><a href="<?= $this->Url->build(["action" => "add"]) ?>">New</a></div>

            <!-- Active / Inactive -->
            <div>
                <?php if (!array_key_exists("inactive", $params) || $params["inactive"] === '0') : ?>
                    <?= $this->Html->link(__('Inactives'), ['action' => 'index', '?' => ['inactive' => true]]) ?>
                <?php else : ?>
                    <?= $this->Html->link(__('Actives'), ['action' => 'index']) ?>
                <?php endif; ?>
            </div>

            <?php // Si le tableau est un tableau complex (avec beaucoup de données à présenter) 
            ?>
            <?php if ($type === "complex") : ?>

                <!-- Filtres (mène à un modal) -->
                <div>Filters</div>

                <!-- Recherche (mène à un modal) -->
                <div>Search</div>

                <!-- Tri (mène à un modal) -->
                <div>Sort</div>
            <?php endif; ?>

            <!-- Bouton pour aller au pied de page (utile pour un tableau tres grand.) -->
            <div>↓</div>

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
                <input name="flytable_data" type="hidden" value="<?= h(json_encode($entities)) ?>">

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
        <div class="table complex"></div>
    <?php endif; ?>

    <!-- Partie basse de la table (Footer / Menu) -->
    <div class="lowerBar">

        <!-- Footer table (pour toujours avoir le décompte total des entités.) -->
        <div class="footer">
            Maximum available : <?= count($entities) ?>
        </div>

        <!-- Menu bas (Reinitialisation) -->
        <div class="menu">
            <div>Reinitialize</div>
            <div>↑</div>
        </div>
    </div>
</div>

<!-- Imports pour le fonctionnement de l'élément Fly Table. -->
<?= $this->Html->css(["fly/table"]) ?>
<?= $this->Html->script(["fly/table"]) ?>