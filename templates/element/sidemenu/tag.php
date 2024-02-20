<?php

/**
 * @var \App\Model\Entity\Tag $tag
 * @var string $action
 */
?>
<aside class="column">
    <div class="side-nav">
        <h4 class="heading"><?= __('Actions') ?></h4>
        <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
            <?php if ($action === "view") : ?>
                <?= $this->Html->link(__('Editer tag'), ['action' => 'edit', $tag->id], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>

            <?php if (in_array($action, ["view", "edit"])) : ?>
                <?= $this->Form->postLink(__('Supprimer tag'), ['action' => 'delete', $tag->id], ['confirm' => __('Are you sure you want to delete {0}?', $tag->nom), 'class' => 'side-nav-item']) ?>
            <?php endif; ?>
        <?php endif; ?>

        <?= $this->Html->link(__('Lister tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>

        <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
            <?php if ($action === "view") : ?>
                <?= $this->Html->link(__('Nouveau tag'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</aside>