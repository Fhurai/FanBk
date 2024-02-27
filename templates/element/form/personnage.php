<?php

use App\Model\Entity\Personnage;
use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var Personnage $personnage Le personnage à modifier.
 * @var \Cake\Collection\CollectionInterface|string[] $fandoms La liste des fandoms disponibles.
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire personnage. -->
<?= ($this->getRequest()->getParam("controller") === "Personnages") ? $this->Form->create($personnage) : "" ?>

<!-- Groupe de champs -->
<fieldset>
    <!-- Légende du formulaire. -->
    <legend><?= __(ucfirst($action) . ' Personnage') ?></legend>
    <?php

    // Champ nom du personnage (l'utilisateur doit définir le nom du personnage => requis.)
    echo $this->element("fly/text", ["name" => "nom", "label" => "Nom", "value" => $personnage->nom, "required" => true, "placeholder" => "Nom du personnage", "maxlength" => 50]);

    // Selecteur simple pour choisir le fandom auquel appartient le personnage.
    echo $this->element("fly/select", ["options" => $fandoms, "name" => "fandom", "label" => "Fandom", "value" => $personnage->fandom, "required" => true]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ.
    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
    ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= ($this->getRequest()->getParam("controller") === "Personnages") ? $this->Form->end() : "" ?>