<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var array $users
 */
?>
<div class="users index">
    <?= $this->cell("FlyTable", ["params" => $params, "urlObject" => array_flip($objects)["users"]]) ?>
</div>