<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'FanBk';
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>

    <!-- #region -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- #endregion -->

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
    <nav class="top-nav">
        <?php if (!is_null($this->request->getSession()->read("user.id"))) : ?>
            <div class="top-nav-title">
                <a href="<?= $this->Url->build('/') ?>">Fanfiction Bookmarks</a>
            </div>
            <div class="top-nav-links">
                <?= $this->Html->link("Fanfictions", ['plugin' => false, 'prefix' => false, 'controller' => 'Fanfictions', 'action' => 'index']) ?>
                <?= $this->Html->link("Series", ['plugin' => false, 'prefix' => false, 'controller' => 'Series', 'action' => 'index']) ?>
                <?php if ($this->request->getSession()->read("user.is_admin")) : ?><?= $this->Html->link("Paramètres", ['plugin' => false, 'prefix' => false, 'controller' => 'Parametres', 'action' => 'index']) ?><?php endif; ?>
            </div>
        <?php endif; ?>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
        <div>
            <ul>
                <?php if (!is_null($this->request->getSession()->read("user.id"))) : ?>
                    <li><?= $this->Html->link("Auteurs", ['plugin' => false, 'prefix' => false, 'controller' => 'Auteurs', 'action' => 'index']) ?></li>
                    <li><?= $this->Html->link("Fandoms", ['plugin' => false, 'prefix' => false, 'controller' => 'Fandoms', 'action' => 'index']) ?></li>
                    <li><?= $this->Html->link("Langages", ['plugin' => false, 'prefix' => false, 'controller' => 'Langages', 'action' => 'index']) ?></li>
                    <li><?= $this->Html->link("Personnages", ['plugin' => false, 'prefix' => false, 'controller' => 'Personnages', 'action' => 'index']) ?></li>
                    <li><?= $this->Html->link("Relations", ['plugin' => false, 'prefix' => false, 'controller' => 'Relations', 'action' => 'index']) ?></li>
                    <li><?= $this->Html->link("Tags", ['plugin' => false, 'prefix' => false, 'controller' => 'Tags', 'action' => 'index']) ?></li>
                    <li>|</li>
                <?php endif; ?>

                <?php if (!is_null($this->request->getSession()->read("user.id"))) : ?>
                    <?php if ($this->request->getSession()->read("user.is_admin")) : ?><li><?= $this->Html->link("Users", ['plugin' => false, 'prefix' => false, 'controller' => 'Users', 'action' => 'index']) ?></li><?php endif; ?>
                    <li class="hidden"><?= $this->Html->link("Déconnexion", ['plugin' => false, 'prefix' => false, 'controller' => 'Users', 'action' => 'logout']) ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </footer>
</body>

</html>