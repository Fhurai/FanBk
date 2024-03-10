<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var Query $relations 
 * @var array $params
 */
?>
<div class="relations index content">
    <?= $this->cell("FlyTable", ["entities" => $relations->toArray(), "params" => $params]) ?>
</div>