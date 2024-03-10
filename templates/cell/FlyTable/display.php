<?php

/**
 * @var array<Entity> $entities La liste des entités à afficher.
 * @var array<String> $columns Les colonnes du tableau.
 * @var array<String> $filters Les filtres du tableau.
 * @var array<String> $fields Les colonnes du tableau.
 * @var string $header L'entête du tableau.
 * @var string $type Type du tableau.
 */
$classes = ["Auteurs", "Fandoms", "Fanfictions", "Langages", "Relations", "Series", "Tags"];
?>
<div class="flytable">
    <input type="hidden" name='_csrf_<?= $header ?>' value="<?= $this->request->getAttribute("csrfToken") ?>">
    <div class="upperBar">
        <div class="header"><span id="count"><?= count($entities) ?></span> <?= $header ?></div>
        <div class="menu">
            <div><a href="<?= $this->Url->build(["action" => "add"]) ?>">New</a></div>
            <div>
                <?= !array_key_exists("inactive", $params) || $params["inactive"] === '0' ? $this->Html->link(__('Inactive' . (in_array($type, ['relations', 'fanfictions', 'series']) ? 'e' : '') . 's'), ['action' => 'index', '?' => ['inactive' => true]]) : $this->Html->link(__('Actives'), ['action' => 'index']) ?>
            </div>
            <?php if ($type === "complex") : ?>
                <div>Filters</div>
                <div>Search</div>
                <div>Tri</div>
            <?php endif; ?>
            <div>↓</div>
        </div>
    </div>
    <?php if ($type === "simple") : ?>
        <div class="table simple">
            <div class="head">
                <div class="columns">
                    <?php foreach ($columns as $key => $column) : ?>
                        <?php if ($filters[$key] !== "boolean") : ?>
                            <div class="column <?= $filters[$key] ?>" col="<?= $key ?>">
                                <?= trim(ucfirst($column)) ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="column action">Action</div>
                </div>
                <div class="filters">
                    <?php foreach ($filters as $key => $filter) : ?>
                        <?php if ($filters[$key] !== "boolean") : ?>
                            <div class="filter <?= $filter ?>" col="<?= $key ?>">
                                <?php if (in_array(ucfirst($columns[$key] . "s"), $classes)) : ?>
                                    <?php $variable = $columns[$key] . "s"; ?>
                                    <select>
                                        <option></option>
                                        <?php foreach ($$variable as $option) : ?>
                                            <option value="<?= $option->value ?>"><?= $option->nom ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else : ?>
                                    <input type="<?= (in_array($filter, ["integer"]) ? "number" : "text") ?>" placeholder="<?= ($filter === "datetime" ? "dd/mm/yyyy" : (in_array($filter, ["integer"]) ? "00000" : "Enter your search")) ?>" col="<?= $key ?>" <?= (in_array($filter, ["integer"]) ? "min='1'" : "") ?> />
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="filter action"><input /></div>
                </div>
            </div>
            <div class="body"></div>
            <div>
                <input name="flytable_data" type="hidden" value="<?= h(json_encode($entities)) ?>">
                <input name="flytable_fields" type="hidden" value="<?= h(json_encode($fields)) ?>">
                <?php foreach ($classes as $class) : ?>
                    <?php if (isset(${strtolower($class)})) : ?>
                        <input name="fly_<?= strtolower($class) ?>" type="hidden" value="<?= h(json_encode(${strtolower($class)})) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php elseif ($type === "complex") : ?>
        <div class="table complex"></div>
    <?php endif; ?>
    <div class="lowerBar">
        <div class="footer">
            Maximum available : <?= count($entities) ?>
        </div>
        <div class="menu">
            <div>Reinitialize</div>
            <div>↑</div>
        </div>
    </div>
</div>
<?= $this->Html->css(["fly/table"]) ?>
<?= $this->Html->script(["fly/table"]) ?>