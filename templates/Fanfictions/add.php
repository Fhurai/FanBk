<?php

/**
 * @var \App\Model\Entity\Fanfiction $fanfiction La fanfiction en cours de création.
 * @var array $params Paramètres session.
 */
?>
<!-- Partie Add -->
<div class="row">

    <!-- Partie menu coté -->
    <?= $this->element("sidemenu/fanfiction", ["fanfiction" => $fanfiction, "action" => "add"]) ?>

    <!-- Partie centrale -->
    <div class="column-responsive column-80">

        <!-- Partie formulaire check -->
        <div class="fanfictions form content">

            <?php // Premère partie du panneau 
            ?>
            <?php echo $this->FlyPanel->panelBegin($params["panels"]["lien"], ["id" => "lien"]); ?>

            <!-- Formulaire du check -->
            <?= $this->Form->create(null, ['url' => ["controller" => "fanfictions", "action" => "checkLien"]]) ?>

            <?php // Champ lien de la fanfiction (l'utilisateur doit définir le lien de la fanfiction => requis.) 
            ?>
            <?= $this->element("fly/text", ["name" => "lien", "label" => "Lien à check", "placeholder" => "Lien de fanfiction à check"]); ?>

            <!-- Fin du formulaire. -->
            <?= $this->Form->end() ?>

            <?php // Deuxième partie du panneau 
            ?>
            <?php echo $this->FlyPanel->panelEnd(); ?>
        </div>

        <!-- Espace entre les deux formulaires -->
        <br />

        <!-- Partie formulaire ajout -->
        <div class="fanfictions form content">

            <?php // Premère partie du panneau 
            ?>
            <?php echo $this->FlyPanel->panelBegin($params["panels"]["fanfiction"], ["id" => "fanfiction"]); ?>

            <?= $this->element("form/fanfiction", ["fanfiction" => $fanfiction, "action" => "add"]) ?>

            <?php // Deuxième partie du panneau 
            ?>
            <?php echo $this->FlyPanel->panelEnd(); ?>
        </div>
    </div>
</div>