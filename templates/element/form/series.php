<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Series $series La série à modifier.
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire auteur. -->
<?= ($this->getRequest()->getParam("controller") === "Series") ? $this->Form->create($series) : "" ?>

<!-- Groupe de champs -->
<fieldset>

    <!-- Légende du formulaire. -->
    <legend><?= __(ucfirst($action) . ' series') ?></legend>
    <?php

    // Champ nom de la série (l'utilisateur doit définir le nom de la série => requis.)
    echo $this->element("fly/text", ["name" => "nom", "label" => "Nom", "value" => $series->nom, "required" => true, "placeholder" => "Nom de la série", "maxlength" => 100]);


    // Champ description de la série (l'utilisateur doit définir la description de la série => requis.)
    echo $this->element("fly/textarea", ["name" => "description", "label" => "Description", "value" => $series->description, "required" => true, "placeholder" => "Description de la série"]);

    // Liste à la volée des liens de la fanfiction (avec un lien obligatoire).
    echo $this->element("fly/list", ["name" => "fanfictions", "label" => "Fanfictions de la série", "value" => $series->fanfictions, "required" => true, "type" => "select", "options" => $fanfictions]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ. 
    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
    ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= ($this->getRequest()->getParam("controller") === "Series") ? $this->Form->end() : "" ?>