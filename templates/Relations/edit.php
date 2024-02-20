<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Relation $relation
 * @var string[]|\Cake\Collection\CollectionInterface $personnages
 */

use Cake\I18n\FrozenTime;

?>
<div class="row">
    <?= $this->element("sidemenu/relation", ["relation" => $relation, "action" => "edit"]) ?>
    <div class="column-responsive column-80">
        <div class="relations form content">
            <?= $this->Form->create($relation) ?>
            <fieldset>
                <legend><?= __('Edit Relation') ?></legend>
                <?php
                    echo $this->Form->control('nom');
                    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
                    echo $this->Form->control('personnages._ids', ['options' => $personnages]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
