<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Langage $langage
 */
?>
<div class="row">
    <?= $this->element("sidemenu/langage", ["langage" => $langage, "action" => "view"]) ?>
    <div class="column-responsive column-80">
        <div class="langages view content">
            <h3><?= h($langage->nom) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nom') ?></th>
                    <td><?= h($langage->nom) ?></td>
                </tr>
                <tr>
                    <th><?= __('Abbreviation') ?></th>
                    <td><?= h($langage->abbreviation) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($langage->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Update Date') ?></th>
                    <td><?= h($langage->update_date) ?></td>
                </tr>
                <?php if (!is_null($langage->suppression_date)) : ?>
                    <tr>
                        <th><?= __('Suppression Date') ?></th>
                        <td><?= h($langage->suppression_date) ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>