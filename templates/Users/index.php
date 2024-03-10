<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var Query $users
 */
?>
<div class="users index">
    <?= $this->cell("FlyTable", ["entities" => $users->toArray(), "params" => $params]) ?>
</div>