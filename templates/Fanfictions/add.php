<!-- Partie Add -->
<div class="row">

    <!-- Partie menu coté -->
    <?= $this->element("sidemenu/fanfiction", ["fanfiction" => $fanfiction, "action" => "add"]) ?>

    <!-- Partie centrale -->
    <div class="column-responsive column-80">

        <!-- Partie formulaire check -->
        <div class="fanfictions form content">
            <div>
                <!-- Formulaire du check -->
                <?= $this->Form->create(null, ['url' => ["controller" => "fanfictions", "action" => "checkLien"]]) ?>

                <?php // Champ lien de la fanfiction (l'utilisateur doit définir le lien de la fanfiction => requis.) 
                ?>
                <?= $this->element("fly/text", ["name" => "lien", "label" => "Lien à check", "placeholder" => "Lien de fanfiction à check"]); ?>

                <!-- Fin du formulaire. -->
                <?= $this->Form->end() ?>
            </div>
        </div>

        <!-- Espace entre les deux formulaires -->
        <br />

        <!-- Partie formulaire ajout -->
        <div class="fanfictions form content">
            <?= $this->element("form/fanfiction", ["fanfiction" => $fanfiction, "action" => "add"]) ?>
        </div>
    </div>
</div>