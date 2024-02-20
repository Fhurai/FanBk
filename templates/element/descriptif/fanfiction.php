<?php

/**
 * @var App\Model\Entity\Fanfiction $fanfiction 
 * @var array $parametres
 * @var boolean $edit
 */

use Cake\I18n\FrozenTime;

$liens = array_filter($fanfiction->liens, function ($lien) {
    if ($lien->suppression_date === null) return true;
});
?>

<?php if (!is_null($liens) && array_key_exists($fanfiction->classement, $parametres["Classement"])) : ?>
    <div class="descriptif fanfiction">
        <p class="descriptif-line">
            <a class="title" href="<?= $liens[0]->lien ?>" target="_blank"><?= $fanfiction->nom ?></a>
            by <a><?= $fanfiction->auteur_obj->nom ?></a> -
            <span class="descriptif-block">rated : <?= $parametres["Classement"][$fanfiction->classement] ?></span> -
            <span class="descriptif-block">lang : <?= $fanfiction->langage_obj->abbreviation ?></span>
            <?php if (!is_null($fanfiction->note)) : ?><span title="<?= $fanfiction->evaluation ?>" class="right-float"><?= $parametres["Note"][$fanfiction->note] ?></span><?php endif; ?>
        </p>
        <p><?= $fanfiction->description ?></p>
        <p>
            <?php if (!is_null($fanfiction->series) && count($fanfiction->series) > 0) : ?>
                Série<?= count($fanfiction->series) > 1 ? "s" : "" ?> :
                <?php foreach ($fanfiction->series as $cle => $serie) : ?>
                    <a><?= $serie->nom ?></a><?= $cle !== count($fanfiction->series) - 1 ? ", " : "" ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </p>
        <p>
            <?= count($fanfiction->fandoms) > 1 ? "Cross-over" : "Fandom" ?> :
            <?php foreach ($fanfiction->fandoms as $cle => $fandom) : ?>
                <a><?= $fandom->nom ?></a> <?= $cle !== count($fanfiction->fandoms) - 1 ? "&" : "" ?>
            <?php endforeach; ?>
        </p>
        <p>
            <?php if (count($fanfiction->relations) > 0) : ?>
                Relation<?= count($fanfiction->relations) > 1 ? "s" : "" ?> :
                <?php foreach ($fanfiction->relations as $relation) : ?>
                    <a><?= $relation->nom ?></a><?= $cle !== count($fanfiction->relations) - 1 ? ", " : "" ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </p>
        <p>
            <?php if (count($fanfiction->personnages) > 0) : ?>
                Personnage<?= count($fanfiction->personnages) > 1 ? "s" : "" ?> :
                <?php foreach ($fanfiction->personnages as $cle => $personnage) : ?>
                    <a><?= $personnage->nom ?></a><?= $cle !== count($fanfiction->personnages) - 1 ? "," : "" ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </p>
        <p>
            <?php if (count($fanfiction->tags) > 0) : ?>
                Tag<?= count($fanfiction->tags) > 1 ? "s" : "" ?> :
                <?php foreach ($fanfiction->tags as $cle => $tag) : ?>
                    <a title="<?= $tag->description ?>"><?= $tag->nom ?></a><?= $cle !== count($fanfiction->tags) - 1 ? ", " : "" ?>
                <?php endforeach; ?>
            <?php else : ?>
                Tags : /
            <?php endif; ?>
            <?php if ($edit) : ?>
                <span class="right-float clickable">🛠️</span>
            <?php endif; ?>
        </p>
        <?php if ($edit) : ?>
            <div class="sticky">
                <div class="contextual-menu">
                    <ul>
                        <li><?= $this->Html->link(__("✏️ Update"), ["action" => "edit", $fanfiction->id]) ?></li>
                        <li><a aria-modal="open" accesskey="<?= $fanfiction->id ?>">❤️ Note</a></li>
                        <?php if (is_null($fanfiction->suppression_date)) : ?>
                            <li><?= $this->Form->postLink('🗑️ Corbeille', ['action' => 'delete', $fanfiction->id], ['confirm' => __('Are you sure you want to delete "{0}"?', $fanfiction->nom)]) ?></li>
                        <?php else : ?>
                            <li><?= $this->Form->postLink('✨ Restaurer', ['action' => 'restore', $fanfiction->id], ['confirm' => __('Are you sure you want to restore "{0}"?', $fanfiction->nom)]) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($edit) : ?>
        <div class="modal" aria-modal="modal" accesskey="<?= $fanfiction->id ?>">

            <!-- Modal content -->
            <div class="modal-content">
                <span aria-modal="close" accesskey="<?= $fanfiction->id ?>" class="close">&times;</span>
                <div class="header">
                    <span class="highlighted title"><?= $fanfiction->nom ?></span> by <span class="highlighted"><?= $fanfiction->auteur_obj->nom ?></span> -
                    <span class="highlighted">rated : <?= $parametres["Classement"][$fanfiction->classement] ?></span> -
                    <span class="highlighted">lang : <?= $fanfiction->langage_obj->abbreviation ?></span>
                </div>
                <?= $this->Form->create($fanfiction, ["url" => ["controller" => "fanfictions", "action" => "note", $fanfiction->id]]) ?>
                <div class="body center">
                    <?= $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]) ?>
                    <?= $this->Form->control('note', ["options" => $parametres["Note"], "value" => $fanfiction->note, "class" => "center"]); ?>
                    <?= $this->Form->control('evaluation', ["type" => "textarea", "class" => "center"]) ?>
                </div>
                <div class="footer">
                    <?= $this->Form->button(__('Submit')) ?>
                    <?= $this->Form->end(); ?>
                    <?php if (!is_null($fanfiction->note) && !is_null($fanfiction->evaluation)) : ?>
                        <?= $this->Form->postLink('Reset', ["controller" => "fanfictions", 'action' => 'denote', $fanfiction->id], ['confirm' => __('Voulez vous retirer la note et l\'évaluation de "{0}"?', $fanfiction->nom)]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>