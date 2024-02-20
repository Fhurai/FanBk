<?php
/**
 * @var \App\View\AppView $this
 * @var array $parametres Le tableau
 */
?>
<div class="parametres index content">
    <?= $this->Html->link(__('Éditer les paramètres'), ['action' => 'edit'], ['class' => 'button float-right']) ?>
    <h3>Paramètres</h3>
    <div class="column-responsive column-80">
        <div class="parametres view content">
            <?php foreach($parametres as $key => $data): ?>
                <h4><?= $key ?></h3>
                <table>
                    <?php foreach($data as $keyDonnee => $donnee): ?>
                        <tr>
                            <th><?= $keyDonnee ?></th>
                            <td><?= $donnee ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
                <hr>
            <?php endforeach; ?>

            <div>
                <h4>Link check</h4>
                <p>Teste si un lien de fanfiction est déjà enregistré.</p>
                <?= $this->Form->create(null, ['url' => ["controller" => "fanfictions", "action" => "checkLien"]]) ?>
                <?= $this->Form->control("lien") ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>