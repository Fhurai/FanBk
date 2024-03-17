<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Series;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
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
        // Initialisation sous requête pour les séries avec max_classement.
        $subquery = TableRegistry::getTableLocator()->get("SeriesSearch")
            ->find()
            ->select(["id", "nom", "description", "max_classement" => "MAX(classement)", "note", "evaluation", "fanfiction", "auteur"])
            ->group("id");

        // Retourne les séries correspondantes à la sous requête avec date de suppression vide.
        return $this->find()
            ->join([
                "Search" => [
                    "table" => $subquery,
                    "type" => "INNER",
                    "conditions" => ["Series.id=SeriesSearch__id"]
                ]
            ])
            ->where(["series.suppression_date IS" => null]);
    }

    /**
     * Retourne les series inactives.
     * 
     * @return Query La requête des series inactives.
     */
    public function findInactive(Query $query, $options)
    {
        // Initialisation sous requête pour les séries avec max_classement.
        $subquery = TableRegistry::getTableLocator()->get("SeriesSearch")
            ->find()
            ->select(["id", "nom", "description", "max_classement" => "MAX(classement)", "note", "evaluation", "fanfiction", "auteur"])
            ->group("id");

        // Retourne les séries correspondantes à la sous requête avec date de suppression non vide.
        return $this->find()
            ->join([
                "Search" => [
                    "table" => $subquery,
                    "type" => "INNER",
                    "conditions" => ["Series.id=SeriesSearch__id"]
                ]
            ])
            ->where(["series.suppression_date IS NOT" => null]);
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
     * Retourne les séries correspondantes aux critères de recherche.
     *
     * @param Query $query La requête de base.
     * @param array $options Les critères de recherche.
     * @return Query La requête avec les critères de recherche.
     */
    public function findSearch(Query $query, array $options)
    {
        // Initialisation de la sous requête.
        $subquery = (clone $query);

        // Initialisation du tableau de l'ordre de tri.
        $order = [];
        foreach ($options["sort"] as $value) $order["series." . (strrpos($value[0], "date") ? substr_replace($value[0], "_", strrpos($value[0], "date")) . "date" : $value[0])] = $value[1];

        foreach ($options["filters"] as $value) {
            // Pour chaque filtre, création de la jointure correspondante avec sa condition de sélection.

            if (!in_array($value[0], ["fanfictions", "auteurs"]))
                // Si l'association recherchée n'est pour les fanfictions ou les auteurs, alors jointure sur une vue série avec l'objet de l'association.
                $subquery->matching("series" . ucfirst(str_replace("[]", "", $value[0])), function ($q) use ($value) {
                    return $q->where(["series" . ucfirst(str_replace("[]", "", $value[0])) . "." . substr(str_replace("[]", "", $value[0]), 0, strlen(str_replace("[]", "", $value[0])) - 1) . "_id" => $value[1]]);
                });
            else
                // Si l'association recherchée est pour les fanfictions ou l'auteur, alors jointure sur la table des fanfictions.
                $subquery->matching("fanfictions", function ($q) use ($value) {
                    return $q->where(["fanfictions." . ($value[0] === "fanfictions" ? "id" : "auteur") => $value[1]]);
                });
        }

        // Initialisation du tableau de condition pour les séries.
        $condition = [];
        foreach ($options["search"] as $value) $condition["series." . $value[0] . " LIKE"] = "%" . $value[1] . "%";

        // Retourne les fanfictions qui correspondent aux conditions de la sous requête.
        return $query->find($options["active"] ? "active" : "inactive")->contain([
            'fanfictions' => [
                'auteurs',
                'langages',
                'fandoms',
                'personnages',
                'relations',
                'tags',
                'liens'
            ]
        ])->where(["series.id in" => array_column($subquery->where($condition)->toArray(), "id")])->where(!$options["nsfw"] ? ["max_classement <=" => 2] : [])->order($order);
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
}
