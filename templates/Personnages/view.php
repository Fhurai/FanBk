<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Personnage $personnage
 */
?>
<div class="row">
    <?= $this->element("sidemenu/personnage", ["personnage" => $personnage, "action" => "view"]) ?>
    <div class="column-responsive column-80">
        <div class="personnages view content">
            <h3><?= h($personnage->nom) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nom') ?></th>
                    <td><?= h($personnage->nom) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fandom') ?></th>
                    <td><?= $personnage->has('fandom_obj') ? $this->Html->link($personnage->fandom_obj->nom, ['controller' => 'Fandoms', 'action' => 'view', $personnage->fandom_obj->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($personnage->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Update Date') ?></th>
                    <td><?= h($personnage->update_date) ?></td>
                </tr>
                <?php if(!is_null($personnage->suppression_date)): ?>
                <tr>
                    <th><?= __('Suppression Date') ?></th>
                    <td><?= h($personnage->suppression_date) ?></td>
                </tr>
                <?php endif; ?>
            </table>
            <div class="related">
                <h4><?= __('Related Relations') ?></h4>
                <?php if (!empty($personnage->relations)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Nom') ?></th>
                            <th><?= __('Creation Date') ?></th>
                            <th><?= __('Update Date') ?></th>
                            <th><?= __('Suppression Date') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($personnage->relations as $relations) : ?>
                        <tr>
                            <td><?= h($relations->id) ?></td>
                            <td><?= h($relations->nom) ?></td>
                            <td><?= h($relations->creation_date) ?></td>
                            <td><?= h($relations->update_date) ?></td>
                            <td><?= h($relations->suppression_date) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('👀'), ['controller' => 'Relations', 'action' => 'view', $relations->id]) ?>
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
