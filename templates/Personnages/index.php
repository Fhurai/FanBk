<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var Query $personnages
 * @var array $params
 */
?>
<div class="personnages index content">
    <?= $this->cell("FlyTable", ["entities" => $personnages->toArray(), "params" => $params]) ?>
</div>