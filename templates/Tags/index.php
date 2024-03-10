<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var Query $tags
 * @var array $params
 */
?>
<div class="tags index">
    <?= $this->cell("FlyTable", ["entities" => $tags->toArray(), "params" => $params]) ?>
</div>