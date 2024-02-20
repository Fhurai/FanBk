<?php
use Cake\I18n\FrozenTime;
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Fandom $fandom
 */
?>
<div class="row">
    <?= $this->element("sidemenu/fandom", ["fandom" => $fandom, "action" => "edit"]) ?>
    <div class="column-responsive column-80">
        <div class="fandoms form content">
            <?= $this->Form->create($fandom) ?>
            <fieldset>
                <legend><?= __('Edit Fandom') ?></legend>
                <?php
                    echo $this->Form->control('nom');
                    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
