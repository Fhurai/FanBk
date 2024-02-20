<?php
use Cake\I18n\FrozenTime;
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Auteur $auteur
 */

?>
<div class="row">
    <?= $this->element("sidemenu/auteur", ["auteur" => $auteur, "action" => "edit"]) ?>
    <div class="column-responsive column-80">
        <div class="auteurs form content">
            <?= $this->Form->create($auteur) ?>
            <fieldset>
                <legend><?= __('Edit Auteur') ?></legend>
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
