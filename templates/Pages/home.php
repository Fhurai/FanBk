<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Fanfiction> $fanfictions
 * @var array $parametres
 */
?>
<div class="pages index content">
    <h1>Welcome on Fanfictions Bookmarks</h1>
    <p>To add more stories in your horizon, here some unnoted stories.</p>
    <hr />
    <div>
        <h4>Fanfictions</h4>
        <?php foreach ($fanfictions as $fanfiction) : ?>
            <?= $this->element("descriptif/fanfiction", ["fanfiction" => $fanfiction, "parametres" => $parametres, "edit" => false]) ?>
        <?php endforeach; ?>
    </div>
    <hr />
    <div>
        <h4>Series</h4>
        <?php foreach ($series as $serie) : ?>
            <?= $this->element("descriptif/series", ["series" => $serie, "parametres" => $parametres, "edit" => false]) ?>
        <?php endforeach; ?>
    </div>
</div>