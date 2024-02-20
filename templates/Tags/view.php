<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Tag'), ['action' => 'edit', $tag->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Tag'), ['action' => 'delete', $tag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tag->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Tag'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tags view content">
            <h3><?= h($tag->nom) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nom') ?></th>
                    <td><?= h($tag->nom) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($tag->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($tag->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Update Date') ?></th>
                    <td><?= h($tag->update_date) ?></td>
                </tr>
                <?php if(!is_null($tag->suppression_date)): ?>
                    <tr>
                        <th><?= __('Suppression Date') ?></th>
                        <td><?= h($tag->suppression_date) ?></td>
                    </tr>
                <?php endif; ?>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($tag->description)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
