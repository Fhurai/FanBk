<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Relation> $relations 
 * @var array $params
 */
?>
<div class="relations index content">
    <?= $this->element("accessbar", ["type" => "relations", "params" => $params]) ?>
    <h3><?= __('Relations') ?> (<span><?= $relationsCount ?></span>)</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><a><?= __('Nom') ?></a></th>
                    <th><a><?= __('Date de création') ?></a></th>
                    <th><a><?= __('Date d\'update') ?></a></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <tr>
                    <th><?= $this->Form->text('search_nom') ?></td>
                    <th><?= $this->Form->text('search_creation_date', ['class' => "half"]) ?></td>
                    <th><?= $this->Form->text('search_update_date', ['class' => "half"]) ?></td>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($relations as $relation) : ?>
                    <tr>
                        <td><a href="<?= $this->Url->build(["action" => "filterRedirect", $relation->id]) ?>"><?= h($relation->nom) ?></a></td>
                        <td><?= h($relation->creation_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td><?= h($relation->update_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('👀', ['action' => 'view', $relation->id]) ?>
                            <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
                                <?= $this->Html->link('✏️', ['action' => 'edit', $relation->id]) ?>
                                <?php if (is_null($params)  || !array_key_exists("inactive", $params)) : ?>
                                    <?= $this->Form->postLink('🗑️', ['action' => 'delete', $relation->id], ['confirm' => __('Are you sure you want to delete {0}?', $relation->nom)]) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>