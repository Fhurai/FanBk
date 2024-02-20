<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Fandom> $fandoms
 * @var array $params
 */
?>
<div class="fandoms index content">
    <?= $this->element("accessbar", ["type" => "fandoms", "params" => $params]) ?>
    <h3><?= __('Fandoms') ?> (<span><?= $fandomsCount ?></span>)</h3>
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
                <?php foreach ($fandoms as $fandom) : ?>
                    <tr>
                        <td><a href="<?= $this->Url->build(["action" => "filterRedirect", $fandom->id]) ?>"><?= h($fandom->nom) ?></a></td>
                        <td><?= h($fandom->creation_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td><?= h($fandom->update_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('👀', ['action' => 'view', $fandom->id]) ?>
                            <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
                                <?= $this->Html->link('✏️', ['action' => 'edit', $fandom->id]) ?>
                                <?php if (is_null($params)  || !array_key_exists("inactive", $params)) : ?>
                                    <?= $this->Form->postLink('🗑️', ['action' => 'delete', $fandom->id], ['confirm' => __('Are you sure you want to delete {0}?', $fandom->nom)]) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>