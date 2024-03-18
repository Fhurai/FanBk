<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Fanfiction;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;

/**
 * Fanfictions Model
 *
 * @property \App\Model\Table\FanfictionsLiensTable&\Cake\ORM\Association\HasMany $liens
 * @property \App\Model\Table\FandomsTable&\Cake\ORM\Association\BelongsToMany $fandoms
 * @property \App\Model\Table\PersonnagesTable&\Cake\ORM\Association\BelongsToMany $personnages
 * @property \App\Model\Table\RelationsTable&\Cake\ORM\Association\BelongsToMany $relations
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $tags
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
        $fanfiction = new Fanfiction();
        $fanfiction->id = null;
        $fanfiction->nom = "";
        $fanfiction->description = "";
        $fanfiction->creation_date = new FrozenTime("Europe/Paris");
        $fanfiction->update_date = new FrozenTime("Europe/Paris");

        // Si une url de fanfiction est présente dans la session.
        if (Router::getRequest()->getSession()->check("fanfictions.url")) {

            // Création de l'objet lien, qui est valorisé puis ajouté à la nouvelle fanfiction.
            $fanfiction->liens = [];
            $lien = $this->liens->newEmptyEntity();
            $lien->lien = Router::getRequest()->getSession()->read("fanfictions.url", "");
            $fanfiction->liens[] = $lien;
        }

        return $fanfiction;
    }

    /**
     * Retourne les fanfictions actives.
     * 
     * @return Query La requete des fanfictions actives.
     */
    public function findActive(Query $query, $options)
    {
        $condition = ["fanfictions.suppression_date IS" => null];
        return $this->find()
            ->contain([
                'auteurs',
                'fandoms',
                'personnages',
                'relations',
                'tags',
                'liens',
                'series'
            ])
            ->where($condition);
    }

    /**
     * Retourne les fanfictions inactives.
     * 
     * @return Query La requête des fanfictions inactives.
     */
    public function findInactive(Query $query, $options)
    {
        $condition = ["fanfictions.suppression_date IS NOT" => null];
        return $this->find()
            ->contain([
                'auteurs',
                'fandoms',
                'personnages',
                'relations',
                'tags',
                'liens',
                'series'
            ])
            ->where($condition);
    }

    /**
     * Retourne les fanfictions actives non notées.
     * 
     * @return Query La requête des fanfictions non notées.
     */
    public function findNotNoted(Query $query, array $options)
    {
        return $this->find("active")->where(["fanfictions.note IS" => null]);
    }

    /**
     * Retourne les fanfictions correspondantes aux critères de recherche.
     *
     * @param Query $query La requête de base.
     * @param array $options Les critères de recherche.
     * @return Query La requête avec les critères de recherche.
     */
    public function findSearch(Query $query, array $options)
    {
        // Création de la sous requête.
        $subquery = (clone $query)
            ->contain([
                'auteurs'
            ]);

        // Initialisation du tableau de l'ordre de tri.
        $order = [];
        foreach ($options["sort"] as $value) $order["fanfictions." . (strrpos($value[0], "date") ? substr_replace($value[0], "_", strrpos($value[0], "date")) . "date" : $value[0])] = $value[1];

        foreach ($options["filters"] as $value) {
            // Pour chaque filtre, création de la jointure correspondante avec sa condition de sélection.
            $subquery->matching(str_replace("[]", "", $value[0]), function ($q) use ($value) {
                return $q->where([str_replace("[]", "", $value[0]) . ".id" => $value[1]]);
            });
        }

        // Initialisation du tableau de condition pour les fanfictions.
        $condition = [];
        foreach ($options["search"] as $value) $condition["fanfictions." . $value[0] . " LIKE"] = "%".$value[1]."%";


        if(!$options["nsfw"])
            // Si l'option nsfw n'est pas activée, récupération uniquement des fanfictions classée 2 ou moins.
            $condition["classement <="] = 2;

        // Retourne les fanfictions qui correspondent aux conditions de la sous requête.
        return $query->find($options["active"] ? "active" : "inactive")->where(["fanfictions.id in" => array_column($subquery->where($condition)->toArray(), "id")])->order($order);
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

    /**
     * @inheritDoc
     *
     * @param EventInterface $event
     * @param ArrayObject $data
     * @param ArrayObject $options
     * @return void
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        // Si un auteur est fourni, conversion en integer.
        if (!empty($data["auteur"])) $data["auteur"] = intval($data["auteur"]);

        // Si un langage est fourni, conversion en integer.
        if (!empty($data["langage"])) $data["langage"] = intval($data["langage"]);

        // Si une note est fournie, conversion en integer.
        if (!empty($data["note"])) $data["note"] = intval($data["note"]);

        // Si des liens sont fournis, ce sont des chaines de caracteres.
        if (!empty($data["liens"])) {

            // Création d'entités FanfictionLiens à partir de ces chaines.
            $data["liens"] = array_map(function ($url) {
                $link = $this->liens->newEmptyEntity();
                $link->lien = trim($url);
                $link->creation_date = FrozenTime::now("Europe/Paris");
                $link->update_date = FrozenTime::now("Europe/Paris");
                return $link;
            }, $data["liens"]);
        }
    }

    /**
     * @inheritDoc
     *
     * @param EventInterface $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @return void
     */
    public function afterMarshal(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!empty($options["fandoms"])) {
            // Si des identifiants de fandoms sont fournis, 

            // Récupération des fandoms pour les associer directement à la fanfiction.
            $entity->fandoms = array_map(function ($id) {
                return $this->fandoms->get($id);
            }, array_filter($options["fandoms"], function ($fandom) {
                return !empty($fandom);
            }));
        }

        if (!empty($options["relations"])) {
            // Si des identifiants de relations sont fournis, 

            // Récupération des relations pour les associer directement à la fanfiction.
            $entity->relations = array_map(function ($id) {
                return $this->relations->get($id);
            }, array_filter($options["relations"], function ($relation) {
                return !empty($relation);
            }));
        }

        if (!empty($options["personnages"])) {
            // Si des identifiants de personnages sont fournis, 

            // Récupération des personnages pour les associer directement à la fanfiction.
            $entity->personnages = array_map(function ($id) {
                return $this->personnages->get($id);
            }, array_filter($options["personnages"], function ($personnage) {
                return !empty($personnage);
            }));
        }

        if (!empty($options["tags"])) {
            // Si des identifiants de tags sont fournis, 

            // Récupération des tags pour les associer directement à la fanfiction.
            $entity->tags = array_map(function ($id) {
                return $this->tags->get($id);
            }, array_filter($options["tags"], function ($tag) {
                return !empty($tag);
            }));
        }
    }
}
