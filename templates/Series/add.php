<div class="row">
    <?= $this->element("sidemenu/series", ["series" => $series, "action" => "add"]) ?>
    <div class="column-responsive column-80">
        <div class="series form content">
            <?= $this->element("form/series", ["series" => $series, "action" => "add"]) ?>
        </div>
    </div>
</div>