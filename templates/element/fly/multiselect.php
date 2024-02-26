<?php

/**
 * @var \Cake\Collection\CollectionInterface|string[]|array[] $options
 * @var string $name
 * @var string $label
 * @var string $class
 * @var int $value
 * @var boolean $group
 * @var boolean $required
 */

// Setup des variables en fonction qu'elles ont été fournies ou non.
$label = isset($label) ? $label : "New Select";
$class = isset($class) ? $class : "";
$required = isset($required) ? $required : false;

//Setup de la variable de groupage en fonction des options fournies par le parent.
if (!is_array($options)) {
    $tempQuery = clone $options;
    $tempQuery = $tempQuery->first();
} else
    $tempQuery = array_shift($options);

if (is_array($tempQuery))
    $group = true;
else
    $group = false;
?>
<!-- Objet Fly Multi Select -->
<div class="flymultiselect">
    <?php // Si les options et le nom du select multiple sont fournis. 
    ?>
    <?php if (isset($options) && isset($name)) : ?>

        <!-- Partie select. -->
        <div class="select">

            <!-- Label -->
            <?php // Si le select multiple est requis, un petit text rouge est affiché. 
            ?>
            <label><?= $label ?><?= $required ? "<span class='required'></span>" : "" ?></label>

            <!-- Affichage des options sélectionnés par le selecteur multiple. -->
            <div class="choices">
                <div class="title">Your selection </div>
                <?php // Display : zone vide pour afficher les options sélectionnés. Input pour conserver le requis du css. 
                ?>
                <div class="display"></div>
                <input name="<?= $name ?>[_ids]" <?= $required ? "required" : "" ?> />
            </div>

            <!-- Select -->
            <div class="selector">
                
                <!-- Champ de recherche. -->
                <div class="input"><input placeholder="Your choice here..." /></div>

                <!-- Liste des options -->
                <ul>
                    <?php // Options non groupées 
                    ?>
                    <?php if (!$group) : ?>

                        <!-- Affichage de toutes les options. -->
                        <?php foreach ($options as $key => $option) : ?>
                            <li class="option" id="flyselect_option_<?= $name ?>_<?= $key ?>"><?= $option ?></li>
                        <?php endforeach; ?>

                        <?php // Options groupées 
                        ?>
                    <?php else : ?>

                        <!-- Affichage des groupes d'options. -->
                        <?php foreach ($options as $keygroup => $optgroup) : ?>
                            <li class="optgroup" optgroup="<?= $keygroup ?>"><?= $keygroup ?></li>

                            <!-- Affichage des options du groupe. -->
                            <?php foreach ($optgroup as $key => $option) : ?>
                                <li class="option" optgroup="<?= $keygroup ?>" id="flymultiselect_option_<?= $name ?>_<?= $key ?>"><?= $option ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <?php // Les options ou le nom manquant. 
        ?>
    <?php else : ?>

        <!-- Partie erreur. -->
        <div class="error">

            <?php // Affichage du paramètre manquant dans le message d'erreur. 
            ?>
            Missing parameter(s) for MultiFlySelect[<?= isset($options) ? "name" : "options" ?>]
        </div>

    <?php endif; ?>
</div>
<!-- Imports pour l'apparence et le fonctionnement du select multiple. -->
<?= $this->Html->script(["fly/multiselect"]); ?>
<?= $this->Html->css(["fly/multiselect"]); ?>

<!-- Initialisation des valeurs existantes. -->
<?php if (isset($value) && is_array($value) && !empty($value)) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php foreach (array_column($value, "id") as $id) : ?>
                setClickOptionMultiple("<?= $name ?>", "<?= $id ?>");
            <?php endforeach; ?>
        });
    </script>
<?php endif; ?>