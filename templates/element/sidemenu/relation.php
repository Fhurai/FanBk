<?php

/**
 * @var \App\Model\Entity\Relation $relation
 * @var string $action
 */
?>
<aside class="column">
    <div class="side-nav">
        <h4 class="heading"><?= __('Actions') ?></h4>
        <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
            <?php if ($action === "view") : ?>
                <?= $this->Html->link(__('Editer relation'), ['action' => 'edit', $relation->id], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>

            <?php if (in_array($action, ["view", "edit"])) : ?>
                <?= $this->Form->postLink(__('Supprimer relation'), ['action' => 'delete', $relation->id], ['confirm' => __('Are you sure you want to delete {0}?', $relation->nom), 'class' => 'side-nav-item']) ?>
            <?php endif; ?>
        <?php endif; ?>

        <?= $this->Html->link(__('Lister relations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>

        <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
            <?php if ($action === "view") : ?>
                <?= $this->Html->link(__('Nouvelle relation'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</aside>