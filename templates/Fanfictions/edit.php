<div class="row">
    <?= $this->element("sidemenu/fanfiction", ["fanfiction" => $fanfiction, "action" => "edit"]) ?>
    <div class="column-responsive column-80">
        <div class="fanfictions form content">
            <?= $this->element("form/fanfiction", ["fanfiction" => $fanfiction, "action" => "edit"]) ?>
        </div>
    </div>
</div>