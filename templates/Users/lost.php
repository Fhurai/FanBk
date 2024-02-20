<div class="users form">
    <?= $this->Flash->render() ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Récupération du mot de passe') ?></legend>
        <?= $this->Form->control('username', ['required' => true]) ?>
        <?= $this->Form->control('email', ['required' => true]) ?>
    </fieldset>
    <?= $this->Form->submit(__('Get it back')); ?>
    <?= $this->Form->end() ?>
</div>