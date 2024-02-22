<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Langage $langage Le langage à modifier.
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire Langage. -->
<?= $this->Form->create($langage) ?>

<!-- Groupe de champs -->
<fieldset>

    <!-- Légende du formulaire. -->
    <legend><?= __(ucfirst($action) . ' Langage') ?></legend>
    <?php

    // Champ nom de l'auteur (l'utilisateur doit définir le nom de l'auteur => requis.)
    echo $this->element("fly/text", ["name" => "nom", "label" => "Nom", "value" => $langage->nom, "required" => true, "placeholder" => "Nom du langage", "maxlength" => 50]);

    // Champ nom de l'auteur (l'utilisateur doit définir le nom de l'auteur => requis.)
    echo $this->element("fly/text", ["name" => "abbreviation", "label" => "Abbréviation", "value" => $langage->abbreviation, "required" => true, "placeholder" => "Abbréviation du langage", "pattern" => "[A-Z]{2}", "maxlength" => 2]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ.
    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
    ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= $this->Form->end() ?>