<?php

/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var array $objects
 */
?>
<div class="series index">
    <?= $this->cell("FlyTable", ["params" => $params, "urlObject" => array_flip($objects)["series"]], ["type" => "complex"]) ?>
</div>