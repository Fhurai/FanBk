<!-- Partie Formulaire utilisateur -->
<div class="users form">

    <?php // Affichage du message Flash s'il existe. 
    ?>
    <?= $this->Flash->render() ?>

    <!-- Titre de la page. -->
    <h3>Login</h3>

    <!-- Formulaire. -->
    <?= $this->Form->create() ?>

    <!-- Groupe de champs -->
    <fieldset>

        <?php
        // Champ nom de l'utilisateur (l'utilisateur doit définir le nom de l'utilisateur => requis.)
        echo $this->element("fly/text", ["name" => "username", "label" => "Nom", "placeholder" => "Nom de l'utilisateur", "maxlength" => 50]);

        // Champ password de l'utilisateur (l'utilisateur doit définir le nom de l'utilisateur => requis.)
        echo $this->element("fly/password", ["name" => "password", "label" => "Mot de passe & Confirmation", "placeholder" => "Mot de passe de l'utilisateur", "maxlength" => 255]);
        ?>

    </fieldset>

    <!-- Bouton de soumission -->
    <?= $this->Form->submit(__('Login')); ?>

    <!-- Fin du formulaire. -->
    <?= $this->Form->end() ?>

    <!-- Lien pour ajouter un utilisateur. -->
    <?= $this->Html->link("Add User", ['action' => 'add']) ?>
    |
    <!-- Mot de passe perdu. -->
    <?= $this->Html->link("Lost Password", ['action' => 'lost']) ?>
</div>