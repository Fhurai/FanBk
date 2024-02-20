<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Fandom $fandom
 */
?>
<div class="row">
    <?= $this->element("sidemenu/fandom", ["fandom" => $fandom, "action" => "add"]) ?>
    <div class="column-responsive column-80">
        <div class="fandoms form content">
            <?= $this->Form->create($fandom) ?>
            <fieldset>
                <legend><?= __('Ajouter Fandom') ?></legend>
                <?php
                    echo $this->Form->control('nom', ['empty' => false, 'placeholder' => 'Nouveau fandom']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
