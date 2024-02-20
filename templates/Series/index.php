<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Series> $series
 * @var array $params
 */
?>
<div class="series index content">
    <?= $this->element("accessbar", ["type" => "series", "params" => $params]) ?>
    <h3><?= __('Series (' . $seriesCount . ')') ?></h3>
    <?php foreach ($series as $serie) : ?>
        <?php if (is_array($serie->fanfictions) && !empty($serie->fanfictions)) : ?>
            <?= $this->element("descriptif/series", ["series" => $serie, "parametres" => $parametres, "edit" => !is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")]) ?>
        <?php endif; ?>
    <?php endforeach; ?>
    <div class="paginator">
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ', ['class' => '']) ?>
                <?= $this->Paginator->prev('< ') ?>
                <?= $this->Paginator->counter(__('{{page}} / {{pages}}')) ?>
                <?= $this->Paginator->next(' >') ?>
                <?= $this->Paginator->last(' >>') ?>
            </ul>
        </div>
        <?= $this->Html->link(__("⏪ Réinitialiser"), ["action" => "reinitialize"]) ?>
    </div>
</div>
<?= $this->element("filters", ["filtres" => array_key_exists("filters", $params) ? $params["filters"] : ["fields" => [], "operator" => [], "not" => []]]) ?>
<?= $this->element("search", ["recherche" => array_key_exists("search", $params) ? $params["search"] : ["fields" => [], "not" => []], "type" => "series"]) ?>
<?= $this->element("sort", ["tri" => array_key_exists("sort", $params) ? $params["sort"] : []]) ?>
<?= $this->Html->script(['descriptif/descriptif', 'modal']) ?>