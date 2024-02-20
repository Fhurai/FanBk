<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Auteur> $auteurs
 * @var array $params
 */
?>
<div class="auteurs index content">
    <?= $this->element("accessbar", ["type" => "auteurs", "params" => $params]) ?>
    <h3><?= __('Auteurs') ?> (<span><?= $auteursCount ?></span>)</h3>
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
                <?php foreach ($auteurs as $auteur) : ?>
                    <tr>
                        <td><a href="<?= $this->Url->build(["action" => "filterRedirect", $auteur->id]) ?>"><?= h($auteur->nom) ?></a></td>
                        <td><?= h($auteur->creation_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td><?= h($auteur->update_date->i18nFormat("dd/MM/Y")) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('👀', ['action' => 'view', $auteur->id]) ?>
                            <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
                                <?= $this->Html->link('✏️', ['action' => 'edit', $auteur->id]) ?>
                                <?php if (is_null($params)  || !array_key_exists("inactive", $params)) : ?>
                                    <?= $this->Form->postLink('🗑️', ['action' => 'delete', $auteur->id], ['confirm' => __('Are you sure you want to delete {0}?', $auteur->nom)]) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>