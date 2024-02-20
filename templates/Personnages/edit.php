<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Personnage $personnage
 * @var string[]|\Cake\Collection\CollectionInterface $fandoms
 */

use Cake\I18n\FrozenTime;

?>
<div class="row">
    <?= $this->element("sidemenu/personnage", ["personnage" => $personnage, "action" => "edit"]) ?>
    <div class="column-responsive column-80">
        <div class="personnages form content">
            <?= $this->Form->create($personnage) ?>
            <fieldset>
                <legend><?= __('Edit Personnage') ?></legend>
                <?php
                    echo $this->Form->control('nom');
                    echo $this->Form->control('fandom');
                    echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
