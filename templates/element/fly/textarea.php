<?php

/**
 * @var \Cake\Collection\CollectionInterface|string[]|array[] $options
 * @var string $name
 * @var string $label
 * @var string $class
 * @var int $value
 * @var boolean $required
 * @var string $pattern
 * @var string $placeholder
 */

// Setup des variables en fonction qu'elles ont été fournies ou non.
$label = isset($label) ? $label : "New Select";
$class = isset($class) ? $class : "";
$value = isset($value) ? $value : null;
$placeholder = isset($placeholder) ? $placeholder : "Your text here...";
$required = isset($required) ? $required : false;
?>
<!-- Objet Fly Textarea -->
<div class="flytextarea">
    <?php // Si le nom du select sont fournis. 
    ?>
    <?php if (isset($name)) : ?>
        <!-- Partie textarea -->
        <div class="text">
            <!-- Label -->
            <?php // Si le select multiple est requis, un petit text rouge est affiché. 
            ?>
            <label><?= $label ?><?= $required ? "<span class='required'></span>" : "" ?></label>

            <!-- Conteneur du textarea -->
            <div class="textor">
                <div class="textarea">
                    <textarea name="<?= $name ?>" class="<?= $class ?>" placeholder="<?= $placeholder ?>" <?= $required ? "required" : "" ?> autocomplete="off" <?= isset($pattern) ? "pattern='" . $pattern . "'" : "" ?>><?= isset($value) ? $value : "" ?></textarea>
                </div>
            </div>
        </div>
    <?php else : ?>

        <!-- Partie erreur. -->
        <div class="error">

            <?php // Affichage du paramètre manquant dans le message d'erreur. 
            ?>
            Missing parameter(s) for FlyTextarea[name]
        </div>

    <?php endif; ?>
</div>

<!-- Imports pour l'apparence et le fonctionnement du textarea. -->
<?= $this->Html->script(["fly/textarea"]); ?>
<?= $this->Html->css(["fly/textarea"]); ?>