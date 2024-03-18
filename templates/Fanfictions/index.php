<?php

/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var array $objects
 */
?>
<div class="fanfictions index">
    <?= $this->cell("FlyTable", ["params" => $params, "urlObject" => array_flip($objects)["fanfictions"]], ["type" => "complex"]) ?>
</div>