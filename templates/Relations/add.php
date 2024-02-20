<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Relation $relation
 * @var \Cake\Collection\CollectionInterface|string[] $personnages
 */
?>
<div class="row">
    <?= $this->element("sidemenu/relation", ["relation" => $relation, "action" => "add"]) ?>
    <div class="column-responsive column-80">
        <div class="relations form content">
            <?= $this->Form->create($relation) ?>
            <fieldset>
                <legend><?= __('Add Relation') ?></legend>
                <?php
                echo $this->Form->control('personnages._ids', ['options' => $personnages]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>