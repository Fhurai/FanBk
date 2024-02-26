<?php

/**
 * @var \App\View\AppView $this
 * @var array $parametres Le tableau
 */
?>
<!-- Partie edition des paramètres -->
<div class="parametres index content">

    <!-- Titre de la page -->
    <h3>Paramètres</h3>

    <!-- Partie centrale de la page -->
    <div class="column-responsive column-80">

        <!-- Formulaire des paramètres -->
        <div class="parametres form content">

            <!-- Formulaire des paramètres -->
            <?= $this->Form->create(null) ?>

            <!-- Groupe de champs -->
            <fieldset>

                <?php // Pour chaque paramètres, création de sa liste modifiable à la vole. 
                ?>
                <?php foreach ($parametres as $key => $data) : ?>
                    <?= $this->element("fly/list", ["name" => "$key", "label" => ucfirst($key), "value" => $data, "required" => true]) ?>
                <?php endforeach; ?>

                <!-- Fin du groupe de champs -->
            </fieldset>

            <!-- Bouton de soumission des données -->
            <?= $this->Form->button(__('Submit')) ?>

            <!-- Fin du formulaire -->
            <?= $this->Form->end() ?>

        </div>

    </div>

</div>