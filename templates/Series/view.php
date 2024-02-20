<?php

use Cake\I18n\FrozenTime;

?>
<div class="row">
    <?= $this->element("sidemenu/series", ["series" => $series, "action" => "view"]) ?>
    <div class="column-responsive column-80">
        <div class="series form content">
            <div>
                <h3><?= h($series->nom) ?></h3>
                <table>
                    <p class="descriptif-line">
                        <a class="title"><?= $series->nom ?></a> by <?= implode(' & ', array_map(function ($idAuteur) use ($auteurs) {
                                                                        return "<a>" . $auteurs[$idAuteur] . "</a>";
                                                                    }, $series->auteurs)) ?>
                        <span class="descriptif-block"> - rated : <a><?= $parametres["Classement"][$series->classement] ?></a></span>
                        <span class="descriptif-block"> - lang : <?= implode(',', array_map(function ($iLangage) use ($langages) {
                                                                        return "<a>" . $langages[$iLangage] . "</a>";
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
                        <?= implode(', ', array_map(function ($idFandom) use ($fandoms) {
                            return "<a>" . $fandoms[$idFandom] . "</a>";
                        }, $series->fandoms)) ?>
                    </p>
                    <p>
                        <?php if (count($series->relations) > 0) : ?>
                            Relation<?= count($series->relations) > 1 ? "s" : "" ?> :
                            <?= implode(', ', array_map(function ($IdRelation) use ($relations) {
                                return "<a>" . $relations[$IdRelation] . "</a>";
                            }, $series->relations)) ?>
                        <?php endif; ?>
                    </p>
                    <p>
                        <?php if (count($series->personnages) > 0) : ?>
                            Personnage<?= count($series->personnages) > 1 ? "s" : "" ?> :
                            <?= implode(', ', array_map(function ($idPersonnage) use ($personnages) {
                                return "<a>" . $personnages[$idPersonnage] . "</a>";
                            }, $series->personnages)) ?>
                        <?php endif; ?>
                    </p>
                    <p>
                        <?php if (count($series->tags) > 0) : ?>
                            Tag<?= count($series->tags) > 1 ? "s" : "" ?> :
                            <?= implode(', ', array_map(function ($idTag) use ($tags) {
                                return "<a>" . $tags[$idTag] . "</a>";
                            }, $series->tags)) ?>
                        <?php endif; ?>
                    </p>
                </table>
                <div class="related">
                    <h4><?= __('Related Fanfictions') . " (" . count($series->fanfictions) . ")" ?></h4>
                    <?php if (!empty($series->fanfictions)) : ?>
                        <div class="table-responsive">
                            <table>
                                <tr>
                                    <th><?= __('Ordre') ?></th>
                                    <th><?= __('Nom') ?></th>
                                    <th><?= __('Description') ?></th>
                                    <th><?= __('Actions') ?></th>
                                </tr>
                                <?php foreach ($series->fanfictions as $fanfiction) : ?>
                                    <tr>
                                        <td><?= h($fanfiction->_joinData->ordre) ?></td>
                                        <td><?= $this->Html->link(__($fanfiction->nom), $fanfiction->liens[0]->lien, ['target' => '_blank']) ?></td>
                                        <td><?= h($fanfiction->description) ?></td>
                                        <td>
                                            <?php if (is_null($fanfiction->note)) : ?>
                                                <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
                                                    <a aria-modal="open" accesskey="<?= $fanfiction->id ?>">❤️ Note</a>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <span title="<?= $fanfiction->evaluation ?>" class="right-float"><?= $parametres["Note"][$fanfiction->note] ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($series->fanfictions)) : ?>
    <?php foreach ($series->fanfictions as $fanfiction) : ?>
        <div class="modal" aria-modal="modal" accesskey="<?= $fanfiction->id ?>">

            <!-- Modal content -->
            <div class="modal-content">
                <span aria-modal="close" accesskey="<?= $fanfiction->id ?>" class="close">&times;</span>
                <div class="descriptif fanfiction">
                    <p class="descriptif-line">
                        <?= $fanfiction->nom ?>
                        by <?= $fanfiction->auteur_obj->nom ?> -
                        <span class="descriptif-block">rated : <?= $parametres["Classement"][$fanfiction->classement] ?></span> -
                        <span class="descriptif-block">lang : <?= $fanfiction->langage_obj->abbreviation ?></span>
                    </p>
                    <p>
                        <?= $this->Form->create($fanfiction, ["url" => ["controller" => "fanfictions", "action" => "note", $fanfiction->id]]) ?>
                        <?= $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]) ?>
                        <?= $this->Form->control('note', ["options" => $parametres["Note"], "value" => $fanfiction->note]); ?>
                        <?= $this->Form->control('evaluation', ["type" => "textarea"]) ?>
                        <?= $this->Form->button(__('Submit')) ?>
                        <?= $this->Form->end(); ?>
                        <?php if (!is_null($fanfiction->note) && !is_null($fanfiction->evaluation)) : ?>
                            <?= $this->Form->postLink('Reset', ["controller" => "fanfictions", 'action' => 'denote', $fanfiction->id], ['confirm' => __('Voulez vous retirer la note et l\'évaluation de "{0}"?', $fanfiction->nom)]) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->Html->script(['modal']) ?>