<?php
use Cake\I18n\FrozenTime;
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Langage $langage
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $langage->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $langage->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Langages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="langages form content">
            <?= $this->Form->create($langage) ?>
            <fieldset>
                <legend><?= __('Edit Langage') ?></legend>
                <?php
                    echo $this->Form->control('nom');
                    echo $this->Form->control('abbreviation');
                    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
