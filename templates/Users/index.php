<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<div class="users index content">
    <?= $this->element("accessbar", ["type" => "users", "params" => $params]) ?>
    <h3><?= __('Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= __('Nom utilisateur') ?></th>
                    <th><?= __('Email') ?></th>
                    <th><?= __('Admin') ?></th>
                    <th><?= __('Date d\'anniversaire') ?></th>
                    <th><?= __('Date de création') ?></th>
                    <th><?= __('Date d\'update') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= h($user->username) ?></td>
                        <td><?= h($user->email) ?></td>
                        <td><?= h($user->is_admin ? "Oui" : "Non") ?></td>
                        <td><?= h($user->birthday->i18nFormat("dd/MM/Y")) ?></td>
                        <td><?= h($user->creation_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td><?= h($user->update_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td class="actions">
                        <?= $this->Html->link('👀', ['action' => 'view', $user->id]) ?>
                            <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
                                <?= $this->Html->link('✏️', ['action' => 'edit', $user->id]) ?>
                                <?php if (is_null($params)  || !array_key_exists("inactive", $params)) : ?>
                                    <?= $this->Form->postLink('🗑️', ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete {0}?', $user->username)]) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>