<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user L'utilisateur à modifier.
 * @var boolean $loggedAdmin Indication si l'utilisateur connecté peut modifier toutes les données de l'utilisateur ou non.
 */
?>
<div class="row">
    <?= $this->element("sidemenu/user", ["user" => $user, "action" => "add"]) ?>
    <div class="column-responsive column-80">
        <div class="users form content">
        <?= $this->element("form/user", ["user" => $user, "loggedAdmin" => $loggedAdmin, "action" => "add"]) ?>
        </div>
    </div>
</div>