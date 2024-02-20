<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Personnage> $personnages 
 * @var string[]|\Cake\Collection\CollectionInterface $fandoms
 * @var array $params
 */
?>
<div class="personnages index content">
    <?= $this->element("accessbar", ["type" => "personnages", "params" => $params]) ?>
    <h3><?= __('Personnages') ?> (<span><?= $personnagesCount ?></span>)</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><a><?= __('Nom') ?></a></th>
                    <th><a><?= __('Fandom') ?></a></th>
                    <th><a><?= __('Date de création') ?></a></th>
                    <th><a><?= __('Date d\'update') ?></a></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <tr>
                    <th><?= $this->Form->text('search_nom') ?></td>
                    <th><?= $this->Form->text('search_fandom') ?></td>
                    <th><?= $this->Form->text('search_creation_date', ['class' => "half"]) ?></td>
                    <th><?= $this->Form->text('search_update_date', ['class' => "half"]) ?></td>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personnages as $personnage) : ?>
                    <tr>
                        <td><a href="<?= $this->Url->build(["action" => "filterRedirect", $personnage->id]) ?>"><?= h($personnage->nom) ?></a></td>
                        <td><?= h($fandoms->toArray()[$personnage->fandom]) ?></td>
                        <td><?= h($personnage->creation_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td><?= h($personnage->update_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('👀', ['action' => 'view', $personnage->id]) ?>
                            <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
                                <?= $this->Html->link('✏️', ['action' => 'edit', $personnage->id]) ?>
                                <?php if (is_null($params)  || !array_key_exists("inactive", $params)) : ?>
                                    <?= $this->Form->postLink('🗑️', ['action' => 'delete', $personnage->id], ['confirm' => __('Are you sure you want to delete {0}?', $personnage->nom)]) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>