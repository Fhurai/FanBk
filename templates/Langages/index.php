<?php

use Cake\ORM\Query;

/**
 * @var \App\View\AppView $this
 * @var Query $langages
 * @var array $params
 */

?>
<div class="langages index">
    <?= $this->cell("FlyTable", ["entities" => $langages->toArray(), "params" => $params]) ?>
</div>