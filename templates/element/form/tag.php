<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag Le tag à modifier.
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire auteur. -->
<?= $this->Form->create($tag) ?>

<!-- Groupe de champs -->
<fieldset>

    <!-- Légende du formulaire. -->
    <legend><?= __(ucfirst($action) . ' Tag') ?></legend>
    <?php

    // Champ nom du tag (l'utilisateur doit définir le nom du tag => requis.)
    echo $this->element("fly/text", ["name" => "nom", "label" => "Nom", "value" => $tag->nom, "required" => true, "placeholder" => "Nom du tag", "maxlength" => 50]);

    // Champ description (l'utilisateur doit définir la description du tag => requis.)
    echo $this->element("fly/textarea", ["name" => "description", "label" => "Description", "value" => $tag->description, "required" => true, "placeholder" => "Description du tag"]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ.
    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
    ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= $this->Form->end() ?>