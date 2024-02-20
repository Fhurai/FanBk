<?php
/**
 * @var \App\View\AppView $this
 * @var array $parametres Le tableau
 */
?>
<div class="parametres index content">
    <h3>Paramètres</h3>
    <div class="column-responsive column-80">
        <div class="parametres form content">
            <?= $this->Form->create(null) ?>
            <fieldset>
                <?php foreach($parametres as $key => $data): ?>
                    <table class="flylist">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <label><?= $key ?></label>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data as $keyDonnee => $donnee): ?>
                                <tr>
                                    <th><?= $keyDonnee ?></th>
                                    <td><?= $this->Form->text($key . "[" . $keyDonnee . "]", [
                                        'value' => $donnee
                                        ]) ?></td>
                                </tr>
                            <?php endforeach ?>
                            <tr>
                                <td colspan="3">
                                    <div class="addButton" title="<?= $key ?>">➕</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>