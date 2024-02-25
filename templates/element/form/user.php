<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user L'utilisateur à modifier.
 * @var boolean $loggedAdmin Indication si l'utilisateur connecté peut modifier toutes les données de l'utilisateur ou non.
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire auteur. -->
<?= $this->Form->create($user) ?>

<fieldset>

    <!-- Groupe de champs -->
    <legend><?= __(ucfirst($action) . ' User') ?></legend>
    <?php

    // Champ nom de l'utilisateur (l'utilisateur doit définir le nom de l'utilisateur => requis.)
    echo $this->element("fly/text", ["name" => "username", "label" => "Nom", "value" => $user->username, "required" => true, "placeholder" => "Nom de l'utilisateur", "maxlength" => 50]);

    // Champ password de l'utilisateur (l'utilisateur doit définir le nom de l'utilisateur => requis.)
    if ($action === "add") echo $this->element("fly/password", ["name" => "password", "label" => "Mot de passe & Confirmation", "value" => $user->password, "required" => true, "placeholder" => "Mot de passe de l'utilisateur", "maxlength" => 255, "confirmation" => true]);

    // Champ nom de l'utilisateur (l'utilisateur doit définir le nom de l'utilisateur => requis.)
    echo $this->element("fly/text", ["name" => "email", "label" => "Email", "value" => $user->email, "required" => true, "placeholder" => "Email de l'utilisateur", "pattern" => "[a-zA-Z0-9 -♪]+@[a-zA-Z0-9]+[.][a-zA-Z0-9]+"]);


    // Champ nom de l'utilisateur (l'utilisateur doit définir le nom de l'utilisateur => requis.)
    if($loggedAdmin) echo $this->element("fly/checkbox", ["name" => "is_admin", "label" => "Admin ?", "value" => $user->is_admin]);

    // Champ date de l'utilisateur (l'utilisateur doit définir la date de l'utilisateur => requis.)
    echo $this->element("fly/date", ["name" => "birthday", "label" => "Date de naissance", "value" => $user->birthday, "required" => true, "time" => true]);

    // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ.
    echo $this->Form->control('update_date', ["type" => "hidden", "value" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s")]);
    ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= $this->Form->end() ?>