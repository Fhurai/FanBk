<?php

/**
 * @var string $type Le type d'objet manipulés
 * @var array $params Les paramètres
 */
?>
<div class="access-bar">
    <ul>
        <?php if (!is_null($this->request->getSession()->read("user.id")) && $this->request->getSession()->read("user.is_admin")) : ?>
            <li><?= $this->Html->link(__('🔖 ' . (in_array($type, ['relations', 'fanfictions', 'series']) ? 'Nouvelle' : 'Nouveau')), ['action' => 'add']) ?></li>
            <li>|</li>
        <?php endif; ?>
        <li><?= !array_key_exists("inactive", $params) || $params["inactive"] === '0' ? $this->Html->link(__('🗃️ Supprimé' . (in_array($type, ['relations', 'fanfictions', 'series']) ? 'e' : '') . 's'), ['action' => 'index', '?' => ['inactive' => true]]) : $this->Html->link(__('📚 Actives'), ['action' => 'index']) ?></li>

        <?php if (in_array($type, ["fanfictions", "series"])) : ?>
            <li><a aria-modal="open" accesskey="#filtersPanel">🎛️ Filtres</a></li>
            <li><a aria-modal="open" accesskey="#searchPanel">🔍 Recherche</a></li>
            <li>|</li>
            <li><a aria-modal="open" accesskey="#sortPanel">📐 Tri</a></li>
        <?php endif; ?>
    </ul>
</div>