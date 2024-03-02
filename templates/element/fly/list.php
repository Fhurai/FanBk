<?php

/**
 * @var string $name Le nom de l'élément.
 * @var string $label Le libellé de l'élément.
 * @var string $class classe CSS à rajouter
 * @var string[]|integer[] $value Tableau de données
 * @var boolean $required Champ répose est requis.
 * @var string $type Type de l'élément de la liste (text/select)
 */

// Setup des variables en fonction qu'elles ont été fournies ou non.
$label = isset($label) ? $label : "New Text";
$class = isset($class) ? $class : "";
$value = isset($value) ? $value : null;
$required = isset($required) ? $required : false;
$type = isset($type) ? $type : "text";
$maxlength = isset($maxlength) ? $maxlength : 255;

if ($type === "select") {
    //Setup de la variable de groupage en fonction des options fournies par le parent.
    if (!is_array($options)) {
        $tempQuery = clone $options;
        $tempQuery = $tempQuery->first();
    } else
        $tempQuery = $options[0];

    if (is_array($tempQuery))
        $group = true;
    else
        $group = false;
}
?>
<div class="flylist">
    <?php // Si le nom de la date est fourni. 
    ?>
    <?php if (isset($name)) : ?>
        <span id="type" hidden><?= $type ?></span>
        <!-- Partie list -->
        <div class="list">
            <!-- Label -->
            <?php // Si la date est requise, un petit text rouge est affiché. 
            ?>
            <label for="<?= $name ?>_1"><?= $label ?><?= $required ? "<span class='required'></span>" : "" ?></label>

            <!-- Liste -->
            <div class="listor">

                <?php // Si liste de sélecteurs, ajout d'un champ dummy qui sera utilisé comme champ vide. 
                ?>
                <?php if ($type === "select") : ?>
                    <input name="<?= $name . "[]" ?>" value="" hidden />
                <?php endif; ?>

                <?php // Si la valeur est un tableau 
                ?>
                <?php if (is_array($value)) : ?>

                    <?php // Parcours des valeurs du tableau. 
                    ?>

                    <?php foreach ($value as $key => $data) : ?>
                        <!-- Ligne de drop -->
                        <div class="drop">

                            <!-- Ligne de drag -->
                            <div class="drag" draggable="true">
                                <!-- Numéro dans la liste -->
                                <div><?= $key + 1 ?></div>

                                <!-- Champ de la liste. -->
                                <div class="field">
                                    <div class="input">
                                        <input id="<?= $name . "_" . $key + 1 ?>" class="<?= $class ?>" placeholder="New value here..." autocomplete="off" <?= $required ? "required" : "" ?> <?= ($type === "text" ? "name='" . $name . "[]'" : "") ?> <?= isset($value) && is_string($data) ? "value='" . $data . "'" : "" ?> <?= isset($pattern) ? "pattern='" . $pattern . "'" : "" ?> <?= isset($maxlength) ? "maxlength='" . $maxlength . "'" : "" ?> />
                                    </div>

                                    <?php // Si la liste est pour des selecteurs 
                                    ?>
                                    <?php if ($type === "select") : ?>
                                        <!-- Liste des options -->
                                        <ul>
                                            <?php // Options non groupées 
                                            ?>
                                            <?php if (!$group) : ?>

                                                <!-- Affichage de toutes les options. -->
                                                <?php foreach ($options as $keyOption => $option) : ?>
                                                    <li class="option" id="flylist_option_<?= $name ?>_<?= $keyOption ?>"><?= $option ?></li>
                                                <?php endforeach; ?>

                                                <?php // Options groupées 
                                                ?>
                                            <?php else : ?>

                                                <!-- Affichage des groupes d'options. -->
                                                <?php foreach ($options as $keygroup => $optgroup) : ?>
                                                    <!-- Affichage des options du groupe. -->
                                                    <li class="optgroup" optgroup="<?= $keygroup ?>"><?= $keygroup ?></li>

                                                    <?php foreach ($optgroup as $keyOption => $option) : ?>
                                                        <li class="option" optgroup="<?= $keygroup ?>" id="flylist_option_<?= $name ?>_<?= $keyOption ?>"><?= $option ?></li>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>

                                <!-- Bouton de suppression -->
                                <?php if ($key !== '') : ?>
                                    <div>🗑️</div>
                                <?php else : ?>
                                    <div></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>


                <div class="drop add">
                    <!-- Ligne pour le bouton d'ajout -->

                    <!-- Case vide -->
                    <div></div>

                    <!-- Bouton d'ajout -->
                    <div class="field"><span>Add row</span></div>

                    <!-- Case vide -->
                    <div></div>
                </div>
            </div>
        </div>

    <?php else : ?>

        <!-- Partie erreur. -->
        <div class="error">

            <?php // Affichage du paramètre manquant dans le message d'erreur. 
            ?>
            Missing parameter(s) for FlyList[name]
        </div>

    <?php endif; ?>
</div>
<!-- Imports pour l'apparence et le fonctionnement du champ text. -->
<?= $this->Html->script(["fly/list"]); ?>
<?= $this->Html->css(["fly/list"]); ?>

<?php // Si la liste est une liste de sélecteurs 
?>
<?php if ($type === "select") : ?>
    <script>
        /**
         * Au chargement terminé de la page.
         */
        document.addEventListener("DOMContentLoaded", function() {
            // Récupération des options de sélecteurs depuis le PHP.
            let options = Object.entries(<?= json_encode($options->toArray()) ?>);

            // Valorisation de ces options dans le fonctionnement de la liste FLY.
            setOptionsFlylist(options);

            <?php // Si la liste est déjà valorisée. 
            ?>
            <?php if (is_array($value) && !is_string($value[0])) : ?>

                <?php // Pour chaque valeur déjà connue, on appelle la méthode qui va cliquer sur les options prévues à cette effet. 
                ?>
                <?php foreach ($value as $key => $data) : ?>
                    // Flylist a un élément cliqué pour être défini par défaut.
                    setClickOptionFlylist("<?= $name . "_" . ($key + 1) ?>", "<?= ((object)$data)->id ?>");
                <?php endforeach; ?>
            <?php endif; ?>
        });
    </script>
<?php endif; ?>