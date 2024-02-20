<?php
/**
 * @var \App\Model\Entity\Series $series
 * @var string $action
 * @var array $parametres
 */

use Cake\I18n\FrozenTime;

?>
<?= $this->Form->create($series) ?>
    <fieldset>
        <legend><?= __('Ajouter une série') ?></legend>
        <?php
            echo $this->Form->control('nom', ["required" => true]);

            echo $this->Form->control('description', ["required" => true]);
        ?>
        <table class="flyselectlist" aria-selected="<?= h(json_encode($fanfictions)) ?>">
            <thead>
                <tr>
                    <th colspan="3">
                        <label>Liens</label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($series->fanfictions)): ?>
                    <?php foreach($series->fanfictions as $keyDonnee => $fanfiction): ?>
                        <tr>
                            <th><?= $keyDonnee + 1 ?></th>
                            <td>
                                <select name="fanfictions[<?= $keyDonnee ?>]">
                                    <option></option>
                                    <?php foreach($fanfictions as $keyGroup => $optionGroups): ?>
                                        <optgroup label="<?= $keyGroup ?>">
                                            <?php foreach($optionGroups as $key => $option): ?>
                                                <option value="<?= $option ?>" <?= $fanfiction->id === $key ? "selected" : "" ?>><?= $option ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td colspan="3">
                        <div class="addButton" title="fanfictions">➕</div>
                    </td>
                </tr>
            </tbody>
        </table>
        <?= $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]); ?>
    </fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>