<?php

/**
 * @var string $type Le type de formulaire à produire.
 */
$singular = substr($type, 0, -1);
?>
<!-- Formulaire pour l'objet manipulé -->
<?= $this->Form->create(null) ?>

<!-- Groupe de champs -->
<fieldset>

    <!-- La donnée de l'action "add" traduite pour l'URL. -->
    <input type="hidden" name="_action" value="<?= array_flip($actions)["note"] ?>" />

    <!-- La donnée de l'objet manipulé traduit pour l'URL. -->
    <input type="hidden" name="_object" value="<?= array_flip($objects)[$type] ?>" />

    <!-- La donnée de l'identifiant manipulé. -->
    <input type="hidden" name="id" value="<?= $id ?>" />

    <?= $this->element("fly/select", ["options" => $parametres["Note"], "name" => "note", "label" => "Note", "required" => true]) ?>
    <?= $this->element("fly/textarea", ["name" => "evaluation", "label" => "Evaluation", "required" => true, "placeholder" => "Evaluation de " . $singular]) ?>

<!-- Fin groupe de champs. -->
</fieldset>

<!-- Bouton de soumission du formulaire -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Bouton de fermeture (car la croix n'est pas possible) -->
<span>Cancel</span>

<!-- Champ d'alerte pour la modale de l'appel Ajax. -->
<div class="form alert"></div>

<!-- Fin formulaire. -->
<?= $this->Form->end() ?>