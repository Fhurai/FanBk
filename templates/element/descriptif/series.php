<?php

/**
 * @var App\Model\Entity\series $series 
 * @var array $parametres
 * @var boolean $edit
 */
use Cake\I18n\FrozenTime;
?>
<div class="descriptif series">
    <p class="descriptif-line">
        <a href="<?= $this->Url->build(["action" => "view", $series->id]) ?>" class="title"><?= $series->nom ?></a>
        by <?= implode(' & ', array_map(function ($idAuteur) use ($auteurs) {
                return $auteurs[$idAuteur];
            }, $series->auteurs)) ?>
        <span class="descriptif-block">rated : <?= $parametres["Classement"][$series->classement] ?></span> -
        <span class="descriptif-block">lang : <?= implode(',', array_map(function ($iLangage) use ($langages) {
                                                    return $langages[$iLangage];
                                                }, $series->langages)) ?></span>
        <?php if (!is_null($series->note)) : ?><span title="<?= $series->evaluation ?>" class="right-float"><?= $parametres["Note"][$series->note] ?></span><?php endif; ?>
    </p>
    <p>
        <?= $series->description ?>
    </p>
    <p>
        Nombre fanfiction(s) : <a><?= count($series->fanfictions) ?></a>
    </p>
    <p>
        <?= count($series->fandoms) > 1 ? "Cross-over" : "Fandom" ?> :
        <?php foreach ($series->fandoms as $cle => $fandom) : ?>
            <a><?= $fandoms[$fandom] ?></a><?= $cle !== count($series->fandoms) - 1 ? " &" : "" ?>
        <?php endforeach; ?>
    </p>
    <p>
        <?php if (count($series->relations) > 0) : ?>
            Relation<?= count($series->relations) > 1 ? "s" : "" ?> :
            <?php foreach ($series->relations as $cle => $relation) : ?>
                <a><?= $relations[$relation] ?></a><?= $cle !== count($series->relations) - 1 ? ", " : "" ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </p>
    <p>
        <?php if (count($series->personnages) > 0) : ?>
            Personnage<?= count($series->personnages) > 1 ? "s" : "" ?> :
            <?php foreach ($series->personnages as $cle => $personnage) : ?>
                <a><?= $personnages[$personnage] ?></a><?= $cle !== count($series->personnages) - 1 ? ", " : "" ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </p>
    <p>
        <?php if (count($series->tags) > 0) : ?>
            Tag<?= count($series->tags) > 1 ? "s" : "" ?> :
            <?php foreach ($series->tags as $cle => $tag) : ?>
                <a><?= $tags[$tag] ?></a><?= $cle !== count($series->tags) - 1 ? ", " : "" ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($edit) : ?>
            <span class="right-float clickable">🛠️</span>
        <?php endif; ?>
    </p>
    <?php if ($edit) : ?>
        <div class="sticky">
            <div class="contextual-menu">
                <ul>
                    <li><?= $this->Html->link(__("✏️ Update"), ["action" => "edit", $series->id]) ?></li>
                    <li><a aria-modal="open" accesskey="<?= $series->id ?>">❤️ Note</a></li>
                    <?php if (is_null($series->suppression_date)) : ?>
                        <li><?= $this->Form->postLink('🗑️ Corbeille', ['action' => 'delete', $series->id], ['confirm' => __('Are you sure you want to delete "{0}"?', $series->nom)]) ?></li>
                    <?php else : ?>
                        <li><?= $this->Form->postLink('✨ Restaurer', ['action' => 'restore', $series->id], ['confirm' => __('Are you sure you want to restore "{0}"?', $series->nom)]) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php if ($edit) : ?>
    <div class="modal" aria-modal="modal" accesskey="<?= $series->id ?>">

        <!-- Modal content -->
        <div class="modal-content">
            <span aria-modal="close" accesskey="<?= $series->id ?>" class="close">&times;</span>
            <div class="descriptif fanfiction">
                <p class="descriptif-line">
                    <a class="title"><?= $series->nom ?></a> by <?= implode(',', array_map(function ($idAuteur) use ($auteurs) {
                                                                    return $auteurs[$idAuteur];
                                                                }, $series->auteurs)) ?>
                    <span class="descriptif-block">rated : <?= $parametres["Classement"][$series->classement] ?></span> -
                    <span class="descriptif-block">lang : <?= implode(',', array_map(function ($iLangage) use ($langages) {
                                                                return $langages[$iLangage];
                                                            }, $series->langages)) ?></span>
                    <?php if (!is_null($series->note)) : ?><span title="<?= $series->evaluation ?>" class="right-float"><?= $parametres["Note"][$series->note] ?></span><?php endif; ?>
                </p>
                <p>
                    <?= $this->Form->create($series, ["url" => ["controller" => "series", "action" => "note", $series->id]]) ?>
                    <?= $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]) ?>
                    <?= $this->Form->control('note', ["options" => $parametres["Note"], "value" => $series->note]); ?>
                    <?= $this->Form->control('evaluation', ["type" => "textarea"]) ?>
                    <?= $this->Form->button(__('Submit')) ?>
                    <?= $this->Form->end(); ?>
                    <?php if (!is_null($series->note) && !is_null($series->evaluation)) : ?>
                        <?= $this->Form->postLink('Reset', ["controller" => "series", 'action' => 'denote', $series->id], ['confirm' => __('Voulez vous retirer la note et l\'évaluation de "{0}"?', $series->nom)]) ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>

    </div>
<?php endif; ?>