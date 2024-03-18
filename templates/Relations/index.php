<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var array $objects
 */
?>
<div class="relations index">
    <?= $this->cell("FlyTable", ["params" => $params, "urlObject" => array_flip($objects)["relations"]]) ?>
</div>