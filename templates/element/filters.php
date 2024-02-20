<?php
$options = [
    "AND" => "Toutes les valeurs doivent exister",
    "OR" => "Au moins une valeur doit exister",
    "XOR" => "Une et une seule des valeurs doit exister en même temps"
];
array_unshift($parametres["Classement"], "Aucun classement sélectionné");
array_unshift($parametres["Note"], "Aucune note sélectionné");
?>
<div class="modal filtersPanel" aria-modal="modal" accesskey="#filtersPanel">
    <div class="modal-content">
        <span aria-modal="close" accesskey="#filtersPanel" class="close">&times;</span>
        <div class="header">
            <span class="highlighted title">Filtres</span>
        </div>
        <?= $this->Form->create() ?>
        <div class="body">
            <div class="line small">
                <div class="input filtre">
                    <?= $this->Form->control("filters.fields.classement", ["type" => "select", "options" => $parametres["Classement"], "value" => is_array($filtres["fields"]) ? (array_key_exists("classement", $filtres["fields"]) ? $filtres["fields"]["classement"] : "") : ""]) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox('filters.not.classement', [
                            'hiddenField' => true,
                            "checked" => is_array($filtres["fields"]) ? (!array_key_exists("classement", $filtres["not"]) || boolval($filtres["not"]["classement"])) : true,
                        ]); ?>
                        <span></label>
                    </label>
                </div>
                <div class="input filtre">
                    <?= $this->Form->control("filters.fields.note", ["type" => "select", "options" => $parametres["Note"], "value" => is_array($filtres["fields"]) ? (array_key_exists("note", $filtres["fields"]) ? $filtres["fields"]["note"] : "") : ""]) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox('filters.not.note', [
                            'hiddenField' => true,
                            "checked" => is_array($filtres["fields"]) ? (!array_key_exists("note", $filtres["not"]) || boolval($filtres["not"]["note"])) : true,
                        ]); ?>
                        <span></label>
                    </label>
                </div>
            </div>
            <div class="line">
                <div class="input filtre">
                    <?= $this->Form->control("filters.fields.fandoms", ["type" => "select", "multiple" => true, "options" => $fandoms, "value" => is_array($filtres["fields"]) ? (array_key_exists("fandoms", $filtres["fields"]) ? $filtres["fields"]["fandoms"] : "") : ""]) ?>
                    <?= $this->Form->control("filters.operator.fandoms", ["label" => false, "class" => "operator", "options" => $options, "value" => is_array($filtres["operator"]) ? (array_key_exists("fandoms", $filtres["operator"]) ? $filtres["operator"]["fandoms"] : "AND") : "AND"]) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox('filters.not.fandoms', [
                            'hiddenField' => true,
                            "checked" => is_array($filtres["fields"]) ? (!array_key_exists("fandoms", $filtres["not"]) || boolval($filtres["not"]["fandoms"])) : true,
                        ]); ?>
                        <span></label>
                    </label>
                </div>
                <div class="input filtre">
                    <?= $this->Form->control("filters.fields.relations", ["type" => "select", "multiple" => true, "options" => $optionsRelations, "value" => is_array($filtres) ? (array_key_exists("relations", $filtres["fields"]) ? $filtres["fields"]["relations"] : "") : ""]) ?>
                    <?= $this->Form->control("filters.operator.relations", ["label" => false, "class" => "operator", "options" => $options, "value" => is_array($filtres) ? (array_key_exists("relations", $filtres["operator"]) ? $filtres["operator"]["relations"] : "AND") : "AND"]) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox('filters.not.relations', [
                            'hiddenField' => true,
                            "checked" => is_array($filtres) ? (!array_key_exists("relations", $filtres["not"]) || boolval($filtres["not"]["relations"])) : true,
                        ]); ?>
                        <span></label>
                    </label>
                </div>
                <div class="input filtre">
                    <?= $this->Form->control("filters.fields.personnages", ["type" => "select", "multiple" => true, "options" => $optionsPersonnages, "value" => is_array($filtres) ? (array_key_exists("personnages", $filtres["fields"]) ? $filtres["fields"]["personnages"] : "") : ""]) ?>
                    <?= $this->Form->control("filters.operator.personnages", ["label" => false, "class" => "operator", "options" => $options, "value" => is_array($filtres) ? (array_key_exists("personnages", $filtres["operator"]) ? $filtres["operator"]["personnages"] : "AND") : "AND"]) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox('filters.not.personnages', [
                            'hiddenField' => true,
                            "checked" => is_array($filtres) ? (!array_key_exists("personnages", $filtres["not"]) || boolval($filtres["not"]["personnages"])) : true,
                        ]); ?>
                        <span></label>
                    </label>
                </div>
                <div class="input filtre">
                    <?= $this->Form->control("filters.fields.tags", ["type" => "select", "multiple" => true, "options" => $optionsTags, "value" => is_array($filtres) ? (array_key_exists("tags", $filtres["fields"]) ? $filtres["fields"]["tags"] : "") : ""]) ?>
                    <?= $this->Form->control("filters.operator.tags", ["label" => false, "class" => "operator", "options" => $options, "value" => is_array($filtres) ? (array_key_exists("tags", $filtres["operator"]) ? $filtres["operator"]["tags"] : "AND") : "AND"]) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox('filters.not.tags', [
                            'hiddenField' => true,
                            "checked" => is_array($filtres) ? (!array_key_exists("tags", $filtres["not"]) || boolval($filtres["not"]["tags"])) : true,
                        ]); ?>
                        <span></label>
                    </label>
                </div>
            </div>
        </div>
        <div class="footer">
            <?= $this->Form->button(__('Submit')) ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>