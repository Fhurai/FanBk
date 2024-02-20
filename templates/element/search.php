<?php
$options = [
    "AND" => "Toutes les valeurs doivent exister",
    "OR" => "Au moins une valeur doit exister",
    "XOR" => "Une et une seule des valeurs doit exister en même temps"
];
?>
<div class="modal searchPanel" aria-modal="modal" accesskey="#searchPanel">
    <div class="modal-content">
        <span aria-modal="close" accesskey="#searchPanel" class="close">&times;</span>
        <div class="header">
            <span class="highlighted title">Recherche</span>
        </div>
        <?= $this->Form->create() ?>
        <div class="body">
            <div class="line">
                <div class="input filtre">
                    <?= $this->Form->control("search.fields.nom", ["class" => "operator", "placeholder" => "Rechercher un nom", "value" => is_array($recherche) ? (array_key_exists("nom", $recherche["fields"]) ? $recherche["fields"]["nom"] : '') : '']) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox('search.not.nom', [
                            'hiddenField' => true,
                            "checked" => is_array($recherche) ? (!array_key_exists("nom", $recherche["not"]) || boolval($recherche["not"]["nom"])) : true,
                        ]); ?>
                        <span></label>
                    </label>
                </div>
                <div class="input filtre">
                    <?= $this->Form->control("search.fields.evaluation", ["class" => "operator", "placeholder" => "Rechercher une évaluation", "value" => is_array($recherche) ? (array_key_exists("evaluation", $recherche["fields"]) ? $recherche["fields"]["evaluation"] : '') : '']) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox("search.not.evaluation", [
                            "hiddenField" => true,
                            "checked" => is_array($recherche) ? (!array_key_exists("evaluation", $recherche["not"]) || boolval($recherche["not"]["evaluation"])) : true
                        ]) ?>
                        <span></label>
                    </label>
                </div>
            </div>
            <div class="line">
                <div class="input filtre">
                    <?= $this->Form->control("search.fields.auteurs", ["type" => "text", "class" => "operator", "placeholder" => "Rechercher un auteur", "value" => is_array($recherche) ? (array_key_exists("auteurs", $recherche["fields"]) ? $recherche["fields"]["auteurs"] : '') : '']) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox("search.not.auteurs", [
                            "hiddenField" => true,
                            "checked" => is_array($recherche) ? (!array_key_exists("auteurs", $recherche["not"]) || boolval($recherche["not"]["auteurs"])) : true
                        ]) ?>
                        <span></label>
                    </label>
                </div>
                <div class="input filtre">
                    <?= $this->Form->control("search.fields.description", ["class" => "operator", "placeholder" => "Rechercher un auteur", "value" => is_array($recherche) ? (array_key_exists("description", $recherche["fields"]) ? $recherche["fields"]["description"] : '') : '']) ?>
                    <label class="input checkbox">
                        <?= $this->Form->checkbox("search.not.description", [
                            "hiddenField" => true,
                            "checked" => is_array($recherche) ? (!array_key_exists("description", $recherche["not"]) || boolval($recherche["not"]["description"])) : true
                        ]) ?>
                        <span></label>
                    </label>
                </div>
                <?php if ($type === "fanfiction") : ?>
                    <div class="input filtre">
                        <?= $type === "fanfiction" ? $this->Form->control("search.fields.series", ["type" => "text", "label" => "Series*", "class" => "operator", "placeholder" => "Rechercher une série", "value" => is_array($recherche) ? (array_key_exists("series", $recherche["fields"]) ? $recherche["fields"]["series"] : '') : '']) : "" ?>

                        <label class="input checkbox">
                            <?= $this->Form->checkbox("search.not.series", [
                                "hiddenField" => true,
                                "checked" => is_array($recherche) ? (!array_key_exists("series", $recherche["not"]) || boolval($recherche["not"]["series"])) : true
                            ]) ?>
                            <span></label>
                        </label>
                    </div>
                <?php endif; ?>
                <?php if ($type === "series") : ?>
                    <div class="input filtre">
                        <?= $type === "series" ? $this->Form->control("search.fields.fanfictions", ["type" => "text", "label" => "Fanfictions", "class" => "operator", "placeholder" => "Rechercher une fanfiction", "value" => is_array($recherche) ? (array_key_exists("fanfiction", $recherche["fields"]) ? $recherche["fields"]["fanfiction"] : '') : '']) : "" ?>

                        <label class="input checkbox">
                            <?= $this->Form->checkbox("search.not.fanfictions", [
                                "hiddenField" => true,
                                "checked" => is_array($recherche) ? (!array_key_exists("fanfictions", $recherche["not"]) || boolval($recherche["not"]["fanfictions"])): true
                            ]) ?>
                            <span></label>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="body">
            <?php if($type === "fanfiction"): ?><p>* : Chercher par série ou absence de série ne donne que les fanfictions liées à des séries.</p><?php endif; ?>
            <div class="line">
                <?= $this->Form->control("search.operator", ["class" => "operator", "options" => $options, "value" => is_array($recherche) ? (array_key_exists("operator", $recherche) ? $recherche["operator"] : "AND") : 'AND']) ?>
            </div>
        </div>
        <div class="footer">
            <?= $this->Form->button(__('Submit')) ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>