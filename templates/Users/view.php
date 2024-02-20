<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <?= $this->element("sidemenu/user", ["user" => $user, "action" => "view"]) ?>
    <div class="column-responsive column-80">
        <div class="users view content">
            <h3><?= h($user->username) ?></h3>
            <table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($user->username) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Birthday') ?></th>
                    <td><?= h($user->birthday) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($user->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Update Date') ?></th>
                    <td><?= h($user->update_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Suppression Date') ?></th>
                    <td><?= h($user->suppression_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Admin') ?></th>
                    <td><?= $user->is_admin ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>