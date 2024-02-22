<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Langage $langage Le langage à modifier.
 */
?>
<!-- Partie Add -->
<div class="row">

    <!-- Partie menu coté -->
    <?= $this->element("sidemenu/langage", ["langage" => $langage, "action" => "add"]) ?>

    <!-- Partie centrale -->
    <div class="column-responsive column-80">

        <!-- Partie formulaire -->
        <div class="langages form content">
            <?= $this->element("form/langage", ["langage" => $langage, "action" => "add"]) ?>
        </div>
    </div>
</div>