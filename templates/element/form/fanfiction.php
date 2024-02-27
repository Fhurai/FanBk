<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\Model\Entity\Fanfiction $fanfiction La fanfiction à modifier.
 * @var string $action L'action du formulaire (add/edit)
 * @var array $parametres Les paramètres nécessaires à la fanfiction.
 */

?>

<!-- Formulaire de la fanfiction -->
<?= $this->Form->create($fanfiction) ?>

<!-- Groupe de champs -->
<fieldset>

    <!-- Légende du formulaire -->
    <legend><?= __('Ajouter une fanfiction') ?></legend>

    <?php
    // Champ nom de la fanfiction (l'utilisateur doit définir le nom de la fanfiction => requis.)
    echo $this->element("fly/text", ["name" => "nom", "label" => "Nom", "value" => $fanfiction->nom, "required" => true, "placeholder" => "Nom de la fanfiction", "maxlength" => 50]);

    // Selecteur simple pour choisir l'auteur de la fanfiction.
    echo $this->element("fly/select", ["options" => $auteurs, "name" => "auteur", "label" => "Auteur", "value" => $fanfiction->auteur, "required" => true]);

    // Selecteur simple pour choisir le classement de la fanfiction.
    echo $this->element("fly/select", ["options" => $parametres["Classement"], "name" => "classement", "label" => "Classement", "value" => $fanfiction->classement, "required" => true]);

    // Champ description (l'utilisateur doit définir la description de la fanfiction => requis.)
    echo $this->element("fly/textarea", ["name" => "description", "label" => "Description", "value" => $fanfiction->description, "required" => true, "placeholder" => "Description de la fanfiction"]);

    // Selecteur multiple pour choisir le fandom de la fanfiction.
    echo $this->element("fly/multiselect", ["options" => $fandoms, "name" => "fandoms", "label" => "Fandom(s) de la fanfiction", "value" => $fanfiction->fandoms, "required" => true]);

    // Selecteur simple pour choisir le langage de la fanfiction.
    echo $this->element("fly/select", ["options" => $langages, "name" => "langage", "label" => "Langage", "value" => $fanfiction->langage, "required" => true]);

    // Liste à la volée des liens de la fanfiction (avec un lien obligatoire).
    echo $this->element("fly/list", ["name" => "liens", "label" => "Liens de la fanfiction", "value" => is_array($fanfiction->liens) ? array_column($fanfiction->liens, "lien") : $fanfiction->liens, "required" => true]);

    // Selecteur multiple pour choisir la relation de la fanfiction.
    echo $this->element("fly/multiselect", ["options" => $relations, "name" => "relations", "label" => "Relation(s) de la fanfiction", "value" => $fanfiction->relations]);

    // Selecteur multiple pour choisir le personnage de la fanfiction.
    echo $this->element("fly/multiselect", ["options" => $personnages, "name" => "personnages", "label" => "Personnage(s) de la fanfiction", "value" => $fanfiction->personnages]);

    // Selecteur multiple pour choisir la relation de la fanfiction.
    echo $this->element("fly/multiselect", ["options" => $tags, "name" => "tags", "label" => "Tag(s) de la fanfiction", "value" => $fanfiction->tags]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ.
    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
    ?>

    <!-- Fin du groupe de champs -->
</fieldset>

<!-- Bouton de soumission du formulaire -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= $this->Form->end() ?>