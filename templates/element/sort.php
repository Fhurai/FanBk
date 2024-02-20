<?php

/**
 * @var array $tri La tableau des valeurs de tri.
 */
$options = ["" => "Aucun tri", "ASC" => "Ascendant", "DESC" => "Descendant"];
?>
<div class="modal sortPanel" aria-modal="modal" accesskey="#sortPanel">
    <div class="modal-content">
        <span aria-modal="close" accesskey="#sortPanel" class="close">&times;</span>
        <div class="header">
            <div class="highlighted title">Tri</div>
        </div>
        <?= $this->Form->create() ?>
        <div class="body">
            <div class="line">
                <div class="input filtre">
                    <?= $this->Form->control("sort.nom", ["type" => "select", "options" => $options, "value" => is_array($tri) ? (array_key_exists("nom", $tri) ? $tri["nom"] : 0) : 0]) ?>
                </div>
                <div class="input filtre">
                    <?= $this->Form->control("sort.creation_date", ["label" => "Date de création", "type" => "select", "options" => $options, "value" => is_array($tri) ? (array_key_exists("creation_date", $tri) ? $tri["creation_date"] : 0) : 0]) ?>
                </div>
                <div class="input filtre">
                    <?= $this->Form->control("sort.update_date", ["label" => "Date d'update", "type" => "select", "options" => $options, "value" => is_array($tri) ? (array_key_exists("update_date", $tri) ? $tri["update_date"] : 0) : 0]) ?>
                </div>

            </div>
        </div>
        <div class="footer">
            <?= $this->Form->button(__('Submit')) ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>