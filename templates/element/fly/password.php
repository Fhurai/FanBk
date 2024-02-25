<?php

/**
 * @var string $name
 * @var string $label
 * @var string $class
 * @var int $value
 * @var boolean $required
 * @var string $placeholder
 */

// Setup des variables en fonction qu'elles ont été fournies ou non.
$label = isset($label) ? $label : "New Password";
$class = isset($class) ? $class : "";
$value = isset($value) ? $value : null;
$placeholder = isset($placeholder) ? $placeholder : "Your password here...";
$required = isset($required) ? $required : false;
$maxlength = isset($maxlength) ? $maxlength : null;
$confirmation = isset($confirmation) ?  $confirmation : false;
?>
<!-- Objet Fly Password -->
<div class="flypassword">
    <?php // Si le nom du mot de passe est fourni. 
    ?>
    <?php if (isset($name)) : ?>

        <!-- Partie password -->
        <div class="password">
            <!-- Label -->
            <?php // Si le mot de passe est requis, un petit text rouge est affiché. 
            ?>
            <label for="<?= $name ?>"><?= $label ?><?= $required ? "<span class='required'></span>" : "" ?></label>

            <!-- Conteneur de l'input text -->
            <div class="textor">
                <div class="input"><input id="<?= $name ?>" type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" name="<?= $name ?>" class="<?= $class ?>" placeholder="<?= $placeholder ?>" <?= $required ? "required" : "" ?> autocomplete="off" <?= isset($value) ? "value='" . $value . "'" : "" ?> <?= isset($maxlength) ? "maxlength='" . $maxlength . "'" : "" ?> /></div>
            </div>
            <?php if ($confirmation) : ?>
                <div class="alert"></div>
            <?php endif; ?>
        </div>
        <br />
        <?php if ($confirmation) : ?>
            <!-- Partie 2° password -->
            <div class="password">
                <div class="textor">
                    <div class="input"><input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" name="_<?= $name ?>" class="<?= $class ?>" placeholder="<?= $placeholder ?>" <?= $required ? "required" : "" ?> autocomplete="off" <?= isset($value) ? "value='" . $value . "'" : "" ?> <?= isset($maxlength) ? "maxlength='" . $maxlength . "'" : "" ?> /></div>
                </div>
                <div class="alert"></div>
            </div>
        <?php endif; ?>

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
<?= $this->Html->script(["fly/password"]); ?>
<?= $this->Html->css(["fly/password"]); ?>