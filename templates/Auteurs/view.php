<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Auteur $auteur
 */
?>
<div class="row">
    <?= $this->element("sidemenu/auteur", ["auteur" => $auteur, "action" => "view"]) ?>
    <div class="column-responsive column-80">
        <div class="auteurs view content">
            <h3><?= h($auteur->nom) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nom') ?></th>
                    <td><?= h($auteur->nom) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($auteur->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Update Date') ?></th>
                    <td><?= h($auteur->update_date) ?></td>
                </tr>
                <?php if(!is_null($auteur->suppression_date)): ?>
                    <tr>
                        <th><?= __('Suppression Date') ?></th>
                        <td><?= h($auteur->suppression_date) ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
