<?php

/**
 * @var \App\Model\Entity\User $user
 * @var string $action
 */
?>
<aside class="column">
    <div class="side-nav">
        <h4 class="heading"><?= __('Actions') ?></h4>
        <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
            <?php if ($action === "view") : ?>
                <?= $this->Html->link(__('Editer Utilisateur'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>

            <?php if (in_array($action, ["view", "edit"])) : ?>
                <?= $this->Form->postLink(__('Supprimer Utilisateur'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete {0}?', $user->username), 'class' => 'side-nav-item']) ?>
            <?php endif; ?>
        <?php endif; ?>

        <?= $this->Html->link(__('Lister Utilisateurs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>

        <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
            <?php if ($action === "view") : ?>
                <?= $this->Html->link(__('Nouveau Utilisateur'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</aside>