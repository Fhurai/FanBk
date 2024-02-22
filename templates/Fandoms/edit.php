<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Fandom $fandom Le fandom à modifier.
 */
?>
<!-- Partie Edit -->
<div class="row">

    <!-- Partie menu coté -->
    <?= $this->element("sidemenu/fandom", ["fandom" => $fandom, "action" => "edit"]) ?>

    <!-- Partie centrale -->
    <div class="column-responsive column-80">

        <!-- Partie formulaire -->
        <div class="fandoms form content">
            <?= $this->element("form/fandom", ["fandom" => $fandom, "action" => "edit"]) ?>
        </div>
    </div>
</div>