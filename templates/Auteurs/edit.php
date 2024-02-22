<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Auteur $auteur $auteur L'auteur à modifier
 */
?>
<!-- Partie Edit -->
<div class="row">

    <!-- Partie menu coté -->
    <?= $this->element("sidemenu/auteur", ["auteur" => $auteur, "action" => "edit"]) ?>

    <!-- Partie centrale -->
    <div class="column-responsive column-80">

        <!-- Partie formulaire -->
        <div class="auteurs form content">
            <?= $this->element("form/auteur", ["auteur" => $auteur, "action" => "edit"]) ?>
        </div>
    </div>
</div>