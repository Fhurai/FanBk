<div class="row">
    <?= $this->element("sidemenu/series", ["series" => $serie, "action" => "add"]) ?>
    <div class="column-responsive column-80">
        <div class="series form content">
            <?= $this->element("form/series", ["series" => $serie, "action" => "add"]) ?>
        </div>
    </div>
</div>