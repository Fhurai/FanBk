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

//Setup de la variable de groupage en fonction des options fournies par le parent.=
if (!is_array($options)) {
    $tempQuery = clone $options;
    $tempQuery = $tempQuery->first();
} else{
    $key = array_key_first($options);
    $tempQuery = $options[$key];
}

if (is_array($tempQuery))
    $group = true;
else
    $group = false;
?>
<!-- Objet Fly Select -->
<div class="flyselect">
    <?php // Si les options et le nom du select sont fournis. 
    ?>
    <?php if (isset($options) && isset($name)) : ?>

        <!-- Partie select. -->
        <div class="select">
            <!-- Label -->
            <?php // Si le select multiple est requis, un petit text rouge est affiché. 
            ?>
            <label for="<?= $name ?>"><?= $label ?><?= $required ? "<span class='required'></span>" : "" ?></label>

            <!-- Selector -->
            <div class="selector">

                <!-- Champ de recherche. -->
                <div class="input"><input id="flyselect_input_<?= $name ?>" name="flyselect_input_<?= $name ?>" placeholder="Your choice here..." <?= $required ? "required" : "" ?> /></div>

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
                                <li class="option" optgroup="<?= $keygroup ?>" id="flyselect_option_<?= $name ?>_<?= $key ?>"><?= $option ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Select -->
            <label for="<?= $name ?>" hidden><?= $label ?></label>
            <select name="<?= $name ?>" <?= $class ?> <?= $required ? "required" : "" ?>>

                <!-- Option vide par défaut -->
                <option></option>

                <?php // Options non groupées 
                ?>
                <?php if (!$group) : ?>

                    <!-- Affichage de toutes les options. -->
                    <?php foreach ($options as $key => $option) : ?>
                        <option value="<?= $key ?>"><?= $option ?></option>
                    <?php endforeach; ?>

                    <?php // Options groupées 
                    ?>
                <?php else : ?>

                    <!-- Affichage des groupes d'options. -->
                    <?php foreach ($options as $keygroup => $optgroup) : ?>
                        <optgroup label="<?= $keygroup ?>">

                            <!-- Affichage des options du groupe. -->
                            <?php foreach ($optgroup as $key => $option) : ?>
                                <option value="<?= $key ?>"><?= $option ?></option>
                            <?php endforeach; ?>

                        </optgroup>

                    <?php endforeach; ?>

                <?php endif; ?>

            </select>
        </div>

        <?php // Les options ou le nom manquant. 
        ?>
    <?php else : ?>

        <!-- Partie erreur. -->
        <div class="error">

            <?php // Affichage du paramètre manquant dans le message d'erreur. 
            ?>
            Missing parameter(s) for FlySelect[<?= isset($options) ? "name" : "options" ?>]
        </div>

    <?php endif; ?>
</div>

<!-- Imports pour l'apparence et le fonctionnement du select. -->
<?= $this->Html->script(["fly/select"]); ?>
<?= $this->Html->css(["fly/select"]); ?>

<!-- Initialisation des valeurs existantes. -->
<?php if (isset($value) && $value !== 0) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setClickOption("<?= $name ?>", "<?= $value ?>");
        });
    </script>
<?php endif; ?>