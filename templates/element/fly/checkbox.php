<?php

/**
 * @var \Cake\Collection\CollectionInterface|string[]|array[] $options
 * @var string $name
 * @var string $label
 * @var string $class
 * @var int $value
 */

// Setup des variables en fonction qu'elles ont été fournies ou non.
$label = isset($label) ? $label : "New Text";
$class = isset($class) ? $class : "";
$value = isset($value) ? $value : null;
?>
<!-- Objet Fly Date -->
<div class="flycheckbox">
    <?php // Si le nom de la date est fourni. 
    ?>
    <?php if (isset($name)) : ?>

        <!-- Partie password -->
        <div class="checkbox">
            <!-- Label -->
            <?php // Si la date est requise, un petit text rouge est affiché. 
            ?>
            <label><?= $label ?></label>
            <input type="hidden" name="<?= $name ?>" id="_<?= $name ?>2" value="0" />

            <div class="boxor">
                <div class="input">
                    <input id="<?= $name ?>" type="checkbox" name="<?= $name ?>" id="<?= $name ?>" value="1" <?= $value ? "checked" : "" ?> />
                    <label for="<?= $name ?>" class="slider">
                        <span class="yes">Yes</span>
                        <span class="no">No</span>
                    </label>
                </div>
            </div>
        </div>

    <?php else : ?>

        <!-- Partie erreur. -->
        <div class="error">

            <?php // Affichage du paramètre manquant dans le message d'erreur. 
            ?>
            Missing parameter(s) for FlyCheckbox[name]
        </div>

    <?php endif; ?>
</div>
<!-- Imports pour l'apparence et le fonctionnement du champ text. -->
<?= $this->Html->css(["fly/checkbox"]); ?>