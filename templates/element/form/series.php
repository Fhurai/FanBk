<?php

use Cake\I18n\FrozenTime;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Series $series La série à modifier.
 * @var string $action La page sur laquelle l'utilisateur est (add/edit).
 */
?>
<!-- Formulaire auteur. -->
<?= $this->Form->create($series) ?>

<!-- Groupe de champs -->
<fieldset>

    <!-- Légende du formulaire. -->
    <legend><?= __(ucfirst($action) . 'Ajouter une série') ?></legend>
    <?php

    // Champ nom de la série (l'utilisateur doit définir le nom de la série => requis.)
    echo $this->Form->control('nom', ["required" => true]);

    // Champ description de la série (l'utilisateur doit définir la description de la série => requis.)
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
            <?php if (!empty($series->fanfictions)) : ?>
                <?php foreach ($series->fanfictions as $keyDonnee => $fanfiction) : ?>
                    <tr>
                        <th><?= $keyDonnee + 1 ?></th>
                        <td>
                            <select name="fanfictions[<?= $keyDonnee ?>]">
                                <option></option>
                                <?php foreach ($fanfictions as $keyGroup => $optionGroups) : ?>
                                    <optgroup label="<?= $keyGroup ?>">
                                        <?php foreach ($optionGroups as $key => $option) : ?>
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

    <?php // Date de modification changé par le système pour le système. L'utilisateur n'a pas a voir ce champ. 
    ?>
    <?= $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]); ?>
</fieldset>

<!-- Bouton de soumission des données du formulaire. -->
<?= $this->Form->button(__('Submit')) ?>

<!-- Fin du formulaire. -->
<?= $this->Form->end() ?>