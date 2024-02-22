<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Fandom $fandom Le fandom à modifier.
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire fandom. -->
<?= $this->Form->create($fandom) ?>

<!-- Groupe de champs -->
<fieldset>

    <!-- Légende du formulaire. -->
    <legend><?= __(ucfirst($action) . ' Fandom') ?></legend>
    <?php

    // Champ nom de l'auteur (l'utilisateur doit définir le nom de l'auteur => requis.)
    echo $this->element("fly/text", ["name" => "nom", "label" => "Nom", "value" => $fandom->nom, "required" => true, "placeholder" => "Nom du fandom", "maxlength" => 50]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ.
    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
    ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= $this->Form->end() ?>