<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Fandom $fandom
 */
?>
<div class="row">
    <?= $this->element("sidemenu/fandom", ["fandom" => $fandom, "action" => "view"]) ?>
    <div class="column-responsive column-80">
        <div class="fandoms view content">
            <h3><?= h($fandom->nom) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nom') ?></th>
                    <td><?= h($fandom->nom) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($fandom->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Update Date') ?></th>
                    <td><?= h($fandom->update_date) ?></td>
                </tr>
                <?php if(!is_null($fandom->suppression_date)): ?>
                    <tr>
                        <th><?= __('Suppression Date') ?></th>
                        <td><?= h($fandom->suppression_date) ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
