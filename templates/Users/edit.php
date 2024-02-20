<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

use Cake\I18n\FrozenTime;

?>
<div class="row">
    <?= $this->element("sidemenu/user", ["user" => $user, "action" => "edit"]) ?>
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Edit User') ?></legend>
                <?php
                echo $this->Form->control('username');
                echo $this->Form->control('password');
                echo $this->Form->control('email');
                echo $this->Form->control('is_admin');
                echo $this->Form->control('birthday', ['empty' => true]);
                echo $this->Form->control('update_date', ["type" => "hidden", "value" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s")]);
                echo $this->Form->control('suppression_date', ['empty' => true, "type" => "hidden"]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>