<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Tag> $tags
 * @var array $params
 */
?>
<div class="tags index content">
    <?= $this->element("accessbar", ["type" => "tags", "params" => $params]) ?>
    <h3><?= __('Tags') ?> (<span><?= $tagsCount ?></span>)</h3>
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
                <?php foreach ($tags as $tag) : ?>
                    <tr>
                        <td title="<?= h($tag->description) ?>"><a href="<?= $this->Url->build(["action" => "filterRedirect", $tag->id]) ?>"><?= h($tag->nom) ?></a></td>
                        <td><?= h($tag->creation_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td><?= h($tag->update_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('👀', ['action' => 'view', $tag->id]) ?>
                            <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
                                <?= $this->Html->link('✏️', ['action' => 'edit', $tag->id]) ?>
                                <?php if (is_null($params)  || !array_key_exists("inactive", $params)) : ?>
                                    <?= $this->Form->postLink('🗑️', ['action' => 'delete', $tag->id], ['confirm' => __('Are you sure you want to delete {0}?', $tag->nom)]) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>