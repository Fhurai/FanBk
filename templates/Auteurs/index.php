<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var Query $auteurs
 * @var array $params
 */
?>
<div class="auteurs index">
    <?= $this->cell("FlyTable", ["entities" => $auteurs->toArray(), "params" => $params]) ?>
</div>