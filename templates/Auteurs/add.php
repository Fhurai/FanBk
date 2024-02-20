<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Auteur $auteur
 */
?>
<div class="row">
    <?= $this->element("sidemenu/auteur", ["auteur" => $auteur, "action" => "add"]) ?>
    <div class="column-responsive column-80">
        <div class="auteurs form content">
            <?= $this->Form->create($auteur) ?>
            <fieldset>
                <legend><?= __('Add Auteur') ?></legend>
                <?php
                    echo $this->Form->control('nom');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
