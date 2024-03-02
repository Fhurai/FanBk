<!-- Imports pour l'apparence et le fonctionnement des différents champs dans le formulaire. -->
<?= $this->Html->script(["ajax/ajaxform"]); ?>
<?= $this->Html->css(["ajax/ajaxform"]); ?>

<!-- Partie modal -->
<div class="ajax form">

    <!-- Checkbox pour afficher la modale ou non -->
    <input type="checkbox" id="modal <?= array_flip($objects)[$type] ?>" />

    <!-- Le bouton affiché pour activer la modale ou non -->
    <label for="modal <?= array_flip($objects)[$type] ?>" class="btn">+</label>

    <!-- Background foncé en arrière plan -->
    <label for="modal <?= array_flip($objects)[$type] ?>" class="back"></label>

    <!-- Fenêtre modal. -->
    <div class="modal <?= array_flip($objects)[$type] ?>">

        <!-- Entête modal -->
        <div class="head ">

            <!-- Titre modal -->
            <h3><?= ucfirst($type) ?></h3>

            <!-- Bouton de fermeture -->
            <label for="modal <?= array_flip($objects)[$type] ?>">x</label>
        </div>

        <!-- Corps modal -->
        <div class="fieldset" data-object="<?= array_flip($objects)[$type] ?>" data-action="<?= array_flip($actions)["add"] ?>"></div>
    </div>

    <!-- Fin partie modal -->
</div>