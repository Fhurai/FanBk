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
     * @param array $params
     * @param string $url_object
     * @return void
     */
    public function display(array $params, string $url_object)
    {
        // Récupération du header, au singulier.
        $header = substr($this->request->getParam("controller"), 0, -1);

        // Récupération des colonnes, des filtres de l'entité pour les tableaux simples.
        $columns = empty($this->columns) ? $this->getColumns() : $this->columns;
        $filters = $this->getFilters();
        $fields = $this->getFields();

        foreach ($columns as $key => $column) if ($column === $fields[$key] && $column === "password") {
            unset($columns[$key]);
            unset($fields[$key]);
            unset($filters[$key]);
        }

        $this->set(compact("params", "columns", "filters", "fields", "header", "url_object"));
        $this->set("type", $this->type);
        $this->set("session", $this->request->getSession()->read(strtolower($header . "s"), []));
        $this->setSortOptions();
        $this->setSearchOptions();
        $this->setFiltersOptions();
    }

    /**
     * Méthode pour récupérer l'ensemble des noms de colonnes, lisibles par l'humain (date de...)
     *
     * @return array Les colonnes du tableau.
     */
    private function getColumns(): array
    {

        $columns = array_map(function ($colonne) {
            // Pour chaque colonne.

            // Récupération du nom sous forme de tableau.
            $name = explode("_", $colonne);

            // Si le nom est un tableau de plus d'une valeur alors c'est une date qu'il faut refaire pour l'humain.
            if (count($name) > 1)
                return ($name[0] == "update" ? $name[1] . " d'" . $name[0] : $name[1] . " de " . $name[0]);

            // Retourne le nom refait.
            return $name[0];
        }, $this->table->getSchema()->columns());

        // Retourne l'ensemble des colonnes, à part la date de suppression.
        return array_splice($columns, 0, count($columns) - 1);
    }

    /**
     * Méthode pour récupérer les filtres d'un tableau simple.
     *
     * @return array
     */
    private function getFilters(): array
    {
        // Récupération des types des colonnes.
        $filters = array_map(function ($column) {
            return $this->table->getSchema()->getColumnType($column);
        }, $this->table->getSchema()->columns());

        // Retourne le tableau des types, sans la colonne de date de suppression.
        return array_splice($filters, 0, count($filters) - 1);
    }

    /**
     * Méthode pour récupérer les champs, avec les champs lisibles par la base de données.
     *
     * @return array
     */
    private function getFields(): array
    {
        // Récupération des colonnes.
        $fields = $this->table->getSchema()->columns();

        // Retourne le tableau des colonnes, sans la date de suppression.
        return array_splice($fields, 0, count($fields) - 1);
    }

    /**
     * Méthode pour envoyer les options de tri à la cell.
     *
     * @return void
     */
    private function setSortOptions(): void
    {
        $sortOptions = [
            "order" => [
                "ASC" => "Ascendant",
                "DESC" => "Descendant"
            ],
            "property" => [
                "nom" => "Nom",
                "create_date" => "Date de création",
                "update_date" => "Date de modification"
            ]
        ];

        $this->set(compact("sortOptions"));
    }

    /**
     * Méthode pour envoyer les options de recherche à la cell.
     *
     * @return void
     */
    private function setSearchOptions(): void
    {
        $searchOptions = [
            "property" => [
                "nom" => "Nom",
                "description" => "Description",
                "evaluation" => "Evaluation",
            ]
        ];

        $this->set(compact("searchOptions"));
    }

    /**
     * Méthode pour envoyer les options de filtrage à la cell.
     *
     * @return void
     */
    private function setFiltersOptions(): void
    {
        // Setup des filtres pour l'ensemble des entités.
        $filtersOptions = [
            "property" => [
                "auteurs" => "Auteur",
                "series" => "Serie",
                "fanfictions" => "Fanfiction",
                "fandoms" => "Fandom",
                "personnages" => "Personnage",
                "relations" => "Relation",
                "tags" => "Tag"
            ]
        ];

        // Suppression du filtre pour l'entité (filtrer les fanfictions par une fanfiction, autant faire une recherche ?)
        unset($filtersOptions["property"][strtolower($this->request->getParam("controller"))]);

        // Setup des options pour les filtres avec un regroupement pour les personnages.
        $filtersOptions["options"] = array_map(function ($property) {
            $name = $property . "s";
            $this->$name = $this->fetchModel($name);
            return ($name !== "Personnages") ? $this->$name->find("list")->order(["nom" => "ASC"])->toArray() : $this->$name->find('list', ["keyField" => "id", "valueField" => "nom", "groupField" => "fandom_obj.nom"])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"])->toArray();
        }, $filtersOptions["property"]);

        // Envoi au template de la Cell.
        $this->set(compact("filtersOptions"));
    }
}
