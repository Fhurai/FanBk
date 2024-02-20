<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Relation $relation
 */
?>
<div class="row">
    <?= $this->element("sidemenu/relation", ["relation" => $relation, "action" => "view"]) ?>
    <div class="column-responsive column-80">
        <div class="relations view content">
            <h3><?= h($relation->nom) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nom') ?></th>
                    <td><?= h($relation->nom) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($relation->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Update Date') ?></th>
                    <td><?= h($relation->update_date) ?></td>
                </tr>
                <?php if(!is_null($relation->suppression_date)): ?>
                <tr>
                    <th><?= __('Suppression Date') ?></th>
                    <td><?= h($relation->suppression_date) ?></td>
                </tr>
                <?php endif; ?>
            </table>
            <div class="related">
                <h4><?= __('Related Personnages') ?></h4>
                <?php if (!empty($relation->personnages)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Nom') ?></th>
                            <th><?= __('Fandom') ?></th>
                            <th><?= __('Creation Date') ?></th>
                            <th><?= __('Update Date') ?></th>
                            <th><?= __('Suppression Date') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($relation->personnages as $personnages) : ?>
                        <tr>
                            <td><?= h($personnages->id) ?></td>
                            <td><?= h($personnages->nom) ?></td>
                            <td><?= h($personnages->fandom) ?></td>
                            <td><?= h($personnages->creation_date) ?></td>
                            <td><?= h($personnages->update_date) ?></td>
                            <td><?= h($personnages->suppression_date) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('👀'), ['controller' => 'Personnages', 'action' => 'view', $personnages->id]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
