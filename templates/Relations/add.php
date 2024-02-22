<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Relation $relation La relation à modifier.
 * @var string[]|\Cake\Collection\CollectionInterface|string[] $personnages La liste des personnages disponibles pour la relation.
 */
?>
<!-- Partie Add -->
<div class="row">

    <!-- Partie menu coté -->
    <?= $this->element("sidemenu/relation", ["relation" => $relation, "action" => "add"]) ?>

    <!-- Partie centrale -->
    <div class="column-responsive column-80">

        <!-- Partie formulaire -->
        <div class="relations form content">
            <?= $this->element("form/relation", ["relation" => $relation, "action" => "add"]) ?>
        </div>
    </div>
</div>