<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Fanfiction;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Fanfictions Model
 *
 * @property \App\Model\Table\FandomsTable&\Cake\ORM\Association\HasMany $Liens
 * @property \App\Model\Table\FandomsTable&\Cake\ORM\Association\BelongsToMany $Fandoms
 * @property \App\Model\Table\PersonnagesTable&\Cake\ORM\Association\BelongsToMany $Personnages
 * @property \App\Model\Table\RelationsTable&\Cake\ORM\Association\BelongsToMany $Relations
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $Tags
 *
 * @method \App\Model\Entity\Fanfiction newEmptyEntity()
 * @method \App\Model\Entity\Fanfiction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Fanfiction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Fanfiction get($primaryKey, $options = [])
 * @method \App\Model\Entity\Fanfiction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Fanfiction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Fanfiction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Fanfiction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Fanfiction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Fanfiction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Fanfiction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Fanfiction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Fanfiction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FanfictionsTable extends Table
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

        $this->setTable('fanfictions');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');

        $this->hasOne('auteurs', [
            'bindingKey' => 'auteur',
            'className' => 'Auteurs',
            'foreignKey' => 'id',
            'propertyName' => 'auteur_obj'
        ]);
        $this->hasOne('langages', [
            'bindingKey' => 'langage',
            'className' => 'langages',
            'foreignKey' => 'id',
            'propertyName' => 'langage_obj'
        ]);
        $this->hasMany('liens', [
            'foreignKey' => 'fanfiction',
            'bindingKey' => 'id',
            'className' => 'FanfictionsLiens',
        ]);
        $this->belongsToMany('fandoms', [
            'foreignKey' => 'fanfiction',
            'targetForeignKey' => 'fandom',
            'joinTable' => 'fanfictions_fandoms',
        ]);
        $this->belongsToMany('personnages', [
            'foreignKey' => 'fanfiction',
            'targetForeignKey' => 'personnage',
            'joinTable' => 'fanfictions_personnages',
        ]);
        $this->belongsToMany('relations', [
            'foreignKey' => 'fanfiction',
            'targetForeignKey' => 'relation',
            'joinTable' => 'fanfictions_relations',
        ]);
        $this->belongsToMany('tags', [
            'foreignKey' => 'fanfiction',
            'targetForeignKey' => 'tag',
            'joinTable' => 'fanfictions_tags',
        ]);
        $this->belongsToMany('series', [
            'foreignKey' => 'fanfiction',
            'targetForeignKey' => 'series',
            'joinTable' => 'series_fanfictions',
        ]);
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
            ->maxLength('nom', 50)
            ->notEmptyString('nom');

        $validator
            ->integer('auteur')
            ->notEmptyString('auteur');

        $validator
            ->integer('classement')
            ->notEmptyString('classement');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('fanfiction')
            ->notEmptyString('fanfiction');

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
    public function newEmptyEntity(): Fanfiction
    {
        $fandom = new Fanfiction();
        $fandom->id = null;
        $fandom->nom = "";
        $fandom->description = "";
        $fandom->creation_date = new FrozenTime("Europe/Paris");
        $fandom->update_date = new FrozenTime("Europe/Paris");
        return $fandom;
    }

    /**
     * Retourne les fanfictions actives.
     * 
     * @return Query La requete des fanfictions actives.
     */
    public function findActive(Query $query, $options)
    {
        $condition = array_key_exists("nsfw", $options) && !$options["nsfw"] ? ["fanfictions.suppression_date IS" => null, "fanfictions.classement <= 3"] : ["fanfictions.suppression_date IS" => null];
        return $this->find()
            ->where($condition);
    }

    /**
     * Retourne les fanfictions inactives.
     * 
     * @return Query La requête des fanfictions inactives.
     */
    public function findInactive(Query $query, $options)
    {
        $condition = array_key_exists("nsfw", $options) && !$options["nsfw"] ? ["fanfictions.suppression_date IS NOT" => null, "fanfictions.classement <= 3"] : ["fanfictions.suppression_date IS NOT" => null];
        return $this->find()
            ->where($condition);
    }

    /**
     * Retourne les fanfictions non notées.
     * 
     * @return Query La requête des fanfictions non notées.
     */
    public function findNotNoted(Query $query, $options)
    {
        return $this->find("active")->where(["fanfictions.note IS" => null]);
    }

    /**
     * Retourne les fanfictions liées à la recherche.
     * 
     * @return Query La requête des fanfictions recherchées.
     */
    public function findSearch(Query $query, $options)
    {


        $query = $this->find("active", $options)->contain([
            'auteurs',
            'langages',
            'fandoms'  => function ($q) {
                return $q->order('nom');
            },
            'personnages'  => function ($q) {
                return $q->order('nom');
            },
            'relations'  => function ($q) {
                return $q->order('nom');
            },
            'tags'  => function ($q) {
                return $q->order('nom');
            },
            'liens',
            'series'  => function ($q) {
                return $q->order('nom');
            }
        ]);

        $condition = [];
        $sort = [];
        $search = [];

        if (!is_null($options)) {

            /**
             * PARTIE INACTIVE
             */
            if (array_key_exists("inactive", $options) && $options["inactive"])
                $query = $this->find("inactive", $options)->contain([
                    'auteurs',
                    'langages',
                    'fandoms'  => function ($q) {
                        return $q->order('nom');
                    },
                    'personnages'  => function ($q) {
                        return $q->order('nom');
                    },
                    'relations'  => function ($q) {
                        return $q->order('nom');
                    },
                    'tags'  => function ($q) {
                        return $q->order('nom');
                    },
                    'liens',
                    'series'  => function ($q) {
                        return $q->order('nom');
                    }
                ]);

            /**
             * PARTIE SORT
             */
            if (array_key_exists("sort", $options)) {
                if (!is_null($options["sort"])) {
                    foreach ($options["sort"] as $key => $sortOption) {
                        if ($sortOption !== "") $sort["fanfictions." . $key] = $sortOption;
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
                                if ($key !== "series") {
                                    $cle = in_array($key, ["auteurs"]) ? $key . ".nom" : "fanfictions." . $key;
                                    $search[] = "$cle " . (!array_key_exists("not", $options["search"]) || !array_key_exists($key, $options["search"]["not"])  || boolval($options["search"]["not"][$key]) ? "" : "NOT ") . "LIKE '%" . $searchOption . "%'";
                                } else {
                                    $query->matching("series", function ($q) use ($options, $key, $searchOption) {
                                        return $q->where(["series.nom " . (!array_key_exists($key, $options["search"]["not"])  || boolval($options["search"]["not"][$key]) ? "" : "NOT ") . "LIKE '%" . $searchOption . "%'"]);
                                    });
                                }
                            }
                            if (array_key_exists("operator", $options["search"]))
                                if (is_array($options["search"]["operator"]))
                                    $search = [$options["search"]["operator"][$key] => $search];
                                else
                                    $search = [$options["search"]["operator"] => $search];
                        }
                    }
                }
            }

            /**
             * PARTIE FILTERS
             */
            if (array_key_exists("filters", $options)) {
                if (array_key_exists("fields", $options["filters"])) {
                    if (!is_null($options["filters"]["fields"]) && !empty($options["filters"]["fields"])) {
                        foreach ($options["filters"]["fields"] as $key => $filterOption) {
                            if ($filterOption !== "" && $filterOption !== '0') {
                                if (in_array($key, ["fandoms", "relations", "personnages", "tags"])) {
                                    if ($options["filters"]["operator"][$key] === "AND") {
                                        $query->matching($key, function ($q) use ($options, $key, $filterOption) {
                                            return $q->where(["$key.id " . (!array_key_exists($key, $options["filters"]["not"])  || boolval($options["filters"]["not"][$key]) ? "in" : "not in ") => $filterOption]);
                                        });
                                    } else {
                                        $filter = "(" . implode(" " . $options["filters"]["operator"][$key] . " ", array_map(function ($filterValue) use ($key, $options) {
                                            return "$key.id " . (!array_key_exists($key, $options["filters"]["not"])  || boolval($options["filters"]["not"][$key]) ? "= " : "!= ") .  $filterValue;
                                        }, $filterOption)) . ")";

                                        $query->matching($key, function ($q) use ($filter) {
                                            return $q->where([$filter]);
                                        });
                                    }
                                } else {
                                    $cle = "fanfictions." . $key;
                                    $condition[] = ["$cle " . (!array_key_exists($key, $options["filters"]["not"])  || boolval($options["filters"]["not"][$key]) ? "" : "!= ") => $filterOption];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $query->where([$condition, $search])->group(["fanfictions.id"])->order($sort);
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
                'auteurs',
                'fandoms',
                'personnages',
                'relations',
                'tags',
                'liens',
                'series'
            ]
        ]);
    }
}
