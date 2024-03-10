<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var Query $fandoms
 * @var array $params
 */
?>
<div class="fandoms index">
    <?= $this->cell("FlyTable", ["entities" => $fandoms->toArray(), "params" => $params]) ?>
</div>