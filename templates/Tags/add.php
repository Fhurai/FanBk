<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag Le tag à modifier.
 */
?>
<div class="row">
    <?= $this->element("sidemenu/tag", ["tag" => $tag, "action" => "add"]) ?>
    <div class="column-responsive column-80">
        <div class="tags form content">
            <?= $this->element("form/tag", ["tag" => $tag, "action" => "add"]) ?>
        </div>
    </div>
</div>