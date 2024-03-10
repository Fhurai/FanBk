<?php

declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\View\Cell;

/**
 * FlyTable cell
 * 
 * @property \App\Model\Table\FanfictionsTable $Fanfictions
 * @property \App\Model\Table\FandomsTable $Fandoms
 * @property \App\Model\Table\AuteursTable $Auteurs
 * @property \App\Model\Table\RelationsTable $Relations
 * @property \App\Model\Table\PersonnagesTable $Personnages
 * @property \App\Model\Table\LangagesTable $Langages
 * @property \App\Model\Table\TagsTable $Tags
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\SeriesTable $Series
 */
class FlyTableCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array<string, mixed>
     */
    protected $_validCellOptions = ["type", "columns"];

    protected string $type = "simple";
    protected array $columns = [];

    protected Table $table;

    private Table $Auteurs;
    private Table $Fandoms;
    private Table $Fanfictions;
    private Table $Langages;
    private Table $Relations;
    private Table $Series;
    private Table $Tags;

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->table = $this->fetchModel($this->request->getParam("controller"));

        foreach (["Auteurs", "Fandoms", "Fanfictions", "Langages", "Relations", "Series", "Tags"] as $table) {
            $this->$table = $this->fetchModel($table);
            switch ($this->request->getParam("controller")) {
                case "Personnages":
                    if ($table === "Fandoms")
                        $this->set(strtolower($table), $this->$table->find("all")->toArray());
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Default display method.
     *
     * @param Entity[] $entities
     * @return void
     */
    public function display(array $entities, array $params)
    {
        $header = substr($this->request->getParam("controller"), 0, count($entities) > 1 ? strlen($this->request->getParam("controller")) :  -1);

        $columns = empty($this->columns) ? $this->getColumns() : $this->columns;
        $filters = $this->getFilters();
        $fields = $this->getFields();

        foreach ($columns as $key => $column) if ($column === $fields[$key] && $column === "password") {
            unset($columns[$key]);
            unset($fields[$key]);
            unset($filters[$key]);
        }

        $this->set(compact("entities", "params", "columns", "filters", "fields", "header"));
        $this->set("type", $this->type);
    }

    /**
     * Méthode pour récupérer les colonnes triables du tableau.
     *
     * @return array Les colonnes du tableau.
     */
    private function getColumns(): array
    {
        // array_splice demande un objet et non une reference.
        $columns = array_map(function ($colonne) {
            $name = explode("_", $colonne);
            if (count($name) > 1) {
                return ($name[0] == "update" ? $name[1] . " d'" . $name[0] : $name[1] . " de " . $name[0]);
            }
            return $name[0];
        }, $this->table->getSchema()->columns());
        return array_splice($columns, 0, count($columns) - 1);
    }

    private function getFilters(): array
    {
        $filters = array_map(function ($column) {
            return $this->table->getSchema()->getColumnType($column);
        }, $this->table->getSchema()->columns());

        return array_splice($filters, 0, count($filters) - 1);
    }

    private function getFields(): array
    {
        $fields = $this->table->getSchema()->columns();
        return array_splice($fields, 0, count($fields) - 1);
    }
}
