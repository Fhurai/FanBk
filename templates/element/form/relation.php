<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Relation $relation La relation à modifier.
 * @var string[]|\Cake\Collection\CollectionInterface $personnages La liste des personnages disponibles.
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire relation. -->
<?= $this->Form->create($relation) ?>

<!-- Groupe de champs -->
<fieldset>
    <!-- Légende du formulaire. -->
    <legend><?= __(ucfirst($action) . ' Relation') ?></legend>
    <?php

    // Champ nom du personnage (l'utilisateur peut définir le nom de la relation => requis.)
    if ($action === "edit")
        echo $this->element("fly/text", ["name" => "nom", "label" => "Nom", "value" => $relation->nom, "required" => true, "placeholder" => "Nom du personnage", "maxlength" => 50]);

    // Selecteur multiple pour choisir les personnages de la relation.
    echo $this->element("fly/multiselect", ["options" => $personnages, "name" => "personnages", "label" => "Personnages", "value" => $relation->personnages, "required" => true]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ.
    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
    ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= $this->Form->end() ?>