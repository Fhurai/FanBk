<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Auteur $auteur L'auteur à modifier
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire auteur. -->
<?= $this->Form->create($auteur) ?>

<!-- Groupe de champs -->
<fieldset>
    <!-- Légende du formulaire. -->
    <legend><?= __(ucfirst($action) . ' Auteur') ?></legend>
    <?php

    // Champ nom de l'auteur (l'utilisateur doit définir le nom de l'auteur => requis.)
    echo $this->element("fly/text", ["name" => "nom", "label" => "Nom", "value" => $auteur->nom, "required" => true, "placeholder" => "Nom de l'auteur", "maxlength" => 50]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ.
    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
    ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= $this->Form->end() ?>