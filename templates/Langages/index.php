<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Langage> $langages
 * @var array $params
 */
?>
<div class="langages index content">
    <?= $this->element("accessbar", ["type" => "langages", "params" => $params]) ?>
    <h3><?= __('Langages') ?> (<span><?= $langagesCount ?></span>)</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><a><?= __('Nom') ?></a></th>
                    <th><a><?= __('Abbréviation') ?></a></th>
                    <th><a><?= __('Date de création') ?></a></th>
                    <th><a><?= __('Date d\'update') ?></a></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <tr>
                    <th><?= $this->Form->text('search_nom') ?></td>
                    <th><?= $this->Form->text('search_abbreviation', ['class' => 'half']) ?></td>
                    <th><?= $this->Form->text('search_creation_date', ['class' => "half"]) ?></td>
                    <th><?= $this->Form->text('search_update_date', ['class' => "half"]) ?></td>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($langages as $langage) : ?>
                    <tr>
                        <td><?= h($langage->nom) ?></td>
                        <td><?= h($langage->abbreviation) ?></td>
                        <td><?= h($langage->creation_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td><?= h($langage->update_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('👀', ['action' => 'view', $langage->id]) ?>
                            <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
                                <?= $this->Html->link('✏️', ['action' => 'edit', $langage->id]) ?>
                                <?php if (is_null($params)  || !array_key_exists("inactive", $params)) : ?>
                                    <?= $this->Form->postLink('🗑️', ['action' => 'delete', $langage->id], ['confirm' => __('Are you sure you want to delete {0}?', $langage->nom)]) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>