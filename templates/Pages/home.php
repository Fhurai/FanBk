<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Fanfiction> $fanfictions
 * @var array $parametres
 */
?>
<div class="pages index content">
    <h1>Bienvenue sur Fanfictions Bookmarks</h1>
    <div>
        <h3>Propositions de lecture (non noté)</h3>
        <?php foreach($fanfictions as $fanfiction): ?>
            <?= $this->element("descriptif/fanfiction", ["fanfiction" => $fanfiction, "parametres" => $parametres, "edit" => false]) ?>
        <?php endforeach; ?>
    </div>
</div>