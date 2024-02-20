<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Langage $langage
 */
?>
<div class="row">
    <?= $this->element("sidemenu/langage", ["langage" => $langage, "action" => "add"]) ?>
    <div class="column-responsive column-80">
        <div class="langages form content">
            <?= $this->Form->create($langage) ?>
            <fieldset>
                <legend><?= __('Ajouter Langage') ?></legend>
                <?php
                    echo $this->Form->control('nom');
                    echo $this->Form->control('abbreviation');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
