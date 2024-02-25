<?php

/**
 * @var string $name
 * @var string $label
 * @var string $class
 * @var int $value
 * @var boolean $required
 * @var string $pattern
 * @var string $placeholder
 */

// Setup des variables en fonction qu'elles ont été fournies ou non.
$label = isset($label) ? $label : "New Text";
$class = isset($class) ? $class : "";
$value = isset($value) ? $value : null;
$placeholder = isset($placeholder) ? $placeholder : "Your text here...";
$required = isset($required) ? $required : false;
$maxlength = isset($maxlength) ? $maxlength : null;
?>
<!-- Objet Fly Text -->
<div class="flytext">
    <?php // Si le nom du select sont fournis. 
    ?>
    <?php if (isset($name)) : ?>

        <!-- Partie text -->
        <div class="text">
            <!-- Label -->
            <?php // Si le select multiple est requis, un petit text rouge est affiché. 
            ?>
            <label for="<?= $name ?>"><?= $label ?><?= $required ? "<span class='required'></span>" : "" ?></label>

            <!-- Conteneur de l'input text -->
            <div class="textor">
                <div class="input"><input id="<?= $name ?>" name="<?= $name ?>" class="<?= $class ?>" placeholder="<?= $placeholder ?>" <?= $required ? "required" : "" ?> autocomplete="off" <?= isset($value) ? "value='" . $value . "'" : "" ?> <?= isset($pattern) ? "pattern='" . $pattern . "'" : "" ?> <?= isset($maxlength) ? "maxlength='" . $maxlength . "'" : "" ?> /></div>
            </div>
            <div class="alert"></div>
        </div>

    <?php else : ?>

        <!-- Partie erreur. -->
        <div class="error">

            <?php // Affichage du paramètre manquant dans le message d'erreur. 
            ?>
            Missing parameter(s) for FlyText[name]
        </div>

    <?php endif; ?>
</div>

<!-- Imports pour l'apparence et le fonctionnement du champ text. -->
<?= $this->Html->script(["fly/text"]); ?>
<?= $this->Html->css(["fly/text"]); ?>