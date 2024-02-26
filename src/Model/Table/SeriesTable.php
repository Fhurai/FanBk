<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Series;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Series Model
 *
 * @method \App\Model\Entity\Series newEmptyEntity()
 * @method \App\Model\Entity\Series newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Series[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Series get($primaryKey, $options = [])
 * @method \App\Model\Entity\Series findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Series patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Series[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Series|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Series saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Series[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Series[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Series[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Series[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SeriesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('series');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');

        $this->belongsToMany('fanfictions', [
            'foreignKey' => 'series',
            'targetForeignKey' => 'fanfiction',
            'joinTable' => 'series_fanfictions',
        ]);
        $this->hasMany('seriesFandoms')
            ->setForeignKey('series_id')
            ->setDependent(true);
        $this->hasMany('seriesPersonnages')
            ->setForeignKey('series_id')
            ->setDependent(true);
        $this->hasMany('seriesRelations')
            ->setForeignKey('series_id')
            ->setDependent(true);
        $this->hasMany('seriesTags')
            ->setForeignKey('series_id')
            ->setDependent(true);
        $this->hasMany('seriesSearch')
            ->setForeignKey('id')
            ->setDependent(true);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('nom')
            ->maxLength('nom', 100)
            ->notEmptyString('nom');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('note')
            ->allowEmptyString('note');

        $validator
            ->scalar('evaluation')
            ->allowEmptyString('evaluation');

        $validator
            ->dateTime('creation_date')
            ->notEmptyDateTime('creation_date');

        $validator
            ->dateTime('update_date')
            ->notEmptyDateTime('update_date');

        $validator
            ->dateTime('suppression_date')
            ->allowEmptyDateTime('suppression_date');

        return $validator;
    }

    /**
     * @inheritDoc
     * 
     * @return Fanfiction
     */
    public function newEmptyEntity(): Series
    {
        $series = new Series();
        $series->id = null;
        $series->nom = "";
        $series->description = "";
        $series->creation_date = new FrozenTime("Europe/Paris");
        $series->update_date = new FrozenTime("Europe/Paris");
        return $series;
    }

    /**
     * Retourne les series actives.
     * 
     * @return Query La requete des series actives.
     */
    public function findActive(Query $query, $options)
    {
        $subquery = TableRegistry::getTableLocator()->get("SeriesSearch")
            ->find()
            ->select(["id", "nom", "description", "max_classement" => "MAX(classement)", "note", "evaluation", "fanfiction", "auteur"])
            ->group("id");

        $condition =  array_key_exists("nsfw", $options["user"]) && !$options["user"]["nsfw"] ? ["series.suppression_date IS" => null, "max_classement <=" => 3] : ["series.suppression_date IS" => null];

        return $this->find()
            ->join([
                "Search" => [
                    "table" => $subquery,
                    "type" => "INNER",
                    "conditions" => ["Series.id=SeriesSearch__id"]
                ]
            ])
            ->where($condition);
    }

    /**
     * Retourne les series inactives.
     * 
     * @return Query La requête des series inactives.
     */
    public function findInactive(Query $query, $options)
    {
        $subquery = TableRegistry::getTableLocator()->get("SeriesSearch")
            ->find()
            ->select(["id", "nom", "description", "max_classement" => "MAX(classement)", "note", "evaluation", "fanfiction", "auteur"])
            ->group("id");

        $condition =  array_key_exists("nsfw", $options["user"]) && !$options["user"]["nsfw"] ? ["series.suppression_date IS NOT" => null, "max_classement <=" => 3] : ["series.suppression_date IS NOT" => null];

        return $this->find()
            ->join([
                "Search" => [
                    "table" => $subquery,
                    "type" => "INNER",
                    "conditions" => ["Series.id=SeriesSearch__id"]
                ]
            ])
            ->where($condition);
    }

    /**
     * Retourne les series non notées.
     * 
     * @return Query La requête des series non notées.
     */
    public function findNotNoted(Query $query, $options)
    {
        return $this->find("active")->where(["series.note IS" => null]);
    }

    /**
     * Retourne la fanfiction avec ses associations.
     * 
     * @return Query La requête de la fanfiction avec ses associations.
     */
    public function getWithAssociations($primaryKey)
    {
        return $this->get($primaryKey, [
            'contain' => [
                'fanfictions' => function ($q) {
                    return $q->contain([
                        'auteurs',
                        'langages',
                        'fandoms',
                        'personnages',
                        'relations',
                        'tags',
                        'liens',
                    ])->order(["SeriesFanfictions.ordre" => "ASC"]);
                }
            ]
        ]);
    }

    /**
     * Retourne les séries liées à la recherche.
     * 
     * @return Query La requête des séries recherchées.
     */
    public function findSearch(Query $query, $options)
    {

        $query = $this->find("active", $options);

        $condition = [];
        $sort = [];
        $search = [];

        if (!is_null($options)) {

            if (array_key_exists("inactive", $options) && $options["inactive"])
                $query = $this->find("inactive", $options);


            /**
             * PARTIE SORT
             */
            if (array_key_exists("sort", $options)) {
                if (!is_null($options["sort"])) {
                    foreach ($options["sort"] as $key => $sortOption) {
                        if ($sortOption !== "") $sort["series." . $key] = $sortOption;
                    }
                }
            }

            /**
             * PARTIE SEARCH
             */
            if (array_key_exists("search", $options)) {
                if (array_key_exists("fields", $options["search"])) {
                    if (!is_null($options["search"]["fields"])) {
                        foreach ($options["search"]["fields"] as $key => $searchOption) {
                            if ($searchOption !== "") {
                                if (!in_array($key, ["fanfictions", "auteurs"])) {
                                    $cle =  "series." . $key;
                                } else {
                                    $cle =  "Search.SeriesSearch__" . substr($key, 0, -1);
                                }
                                $search[] = "$cle " . (!array_key_exists($key, $options["search"]["not"])  || boolval($options["search"]["not"][$key]) ? "" : "NOT ") . "LIKE '%" . $searchOption . "%'";
                            }
                        }
                    }
                }
                if (array_key_exists("operator", $options["search"]))
                    $search = [$options["search"]["operator"] => $search];
            }

            /**
             * PARTIE FILTERS
             */
            $subquery = clone $query;
            if (array_key_exists("filters", $options)) {
                if (array_key_exists("fields", $options["filters"])) {
                    if (!is_null($options["filters"]["fields"]) && !empty($options["filters"]["fields"])) {
                        foreach ($options["filters"]["fields"] as $key => $filterOption) {
                            if ($filterOption !== "" && $filterOption !== '0') {
                                if (in_array($key, ["fandoms", "relations", "personnages", "tags"])) {
                                    $table = substr($key, 0, -1);
                                    if ($options["filters"]["operator"][$key] === "AND") {
                                        $tempquery = TableRegistry::getTableLocator()->get("Series" . ucfirst($key))
                                            ->find()
                                            ->select(["series_id"])
                                            ->where([
                                                "Series" . ucfirst($key) . "." . $table . "_id IN " => $filterOption
                                            ])
                                            ->group(["series_id"]);
                                    } else {
                                        $tempquery = TableRegistry::getTableLocator()->get("Series" . ucfirst($key))
                                            ->find()
                                            ->select(["series_id"])
                                            ->where([
                                                implode(" " . $options["filters"]["operator"][$key] . " ", array_map(function ($filterValue) use ($key, $table) {
                                                    return "Series" . ucfirst($key) . "." . $table . "_id = " . $filterValue;
                                                }, $filterOption))
                                            ])
                                            ->group(["series_id"]);
                                    }
                                    $subquery->where(["series.id " . (!array_key_exists($key, $options["filters"]["not"])  || boolval($options["filters"]["not"][$key]) ? "IN " : "NOT IN ") => $tempquery]);
                                } else {
                                    $cle = ($key === "classement" ? "max_classement" : "series." . $key);
                                    $condition[] = ["$cle " . (!array_key_exists($key, $options["filters"]["not"])  || boolval($options["filters"]["not"][$key]) ? "" : "!= ") => $filterOption];
                                }
                            }
                        }
                    }
                }
            }
            $subquery = $subquery->select([
                "series.id",
            ])->where([$condition, $search]);
            return $query->select([
                "id",
                "nom",
                "description",
                "note",
                "evaluation",
                "creation_date",
                "update_date",
                "suppression_date"
            ])->where([
                "id IN" => $subquery->count() > 0 ? array_column(array_column($subquery->toArray(), "series"), "id") : [null]
            ])->order($sort);
        }
        return $query;
    }
}
