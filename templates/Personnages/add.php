<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Personnage $personnage Le personnage à modifier.
 * @var string[]|\Cake\Collection\CollectionInterface|string[] $fandoms La listes fandoms disponibles pour le personnage.
 */
?>
<!-- Partie Add -->
<div class="row">

    <!-- Partie menu coté -->
    <?= $this->element("sidemenu/personnage", ["personnage" => $personnage, "action" => "add"]) ?>

    <!-- Partie centrale -->
    <div class="column-responsive column-80">

    <!-- Partie formulaire -->
        <div class="personnages form content">
            <?= $this->element("form/personnage", ["personnage" => $personnage, "action" => "add"]) ?>
        </div>
    </div>
</div>