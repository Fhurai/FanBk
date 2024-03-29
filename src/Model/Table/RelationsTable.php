<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Relation;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Relations Model
 *
 * @property \App\Model\Table\PersonnagesTable&\Cake\ORM\Association\BelongsToMany $personnages
 *
 * @method \App\Model\Entity\Relation newEmptyEntity()
 * @method \App\Model\Entity\Relation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Relation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Relation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Relation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Relation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Relation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Relation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Relation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Relation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Relation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Relation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Relation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class RelationsTable extends Table
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

        $this->setTable('relations');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');

        $this->belongsToMany('personnages', [
            'foreignKey' => 'relation',
            'targetForeignKey' => 'personnage',
            'joinTable' => 'relations_personnages',
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
            ->maxLength('nom', 75)
            ->notEmptyString('nom');

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
     * @return Relation
     */
    public function newEmptyEntity(): Relation
    {
        $relation = new Relation();
        $relation->id = null;
        $relation->nom = "";
        $relation->creation_date = new FrozenTime("Europe/Paris");
        $relation->update_date = new FrozenTime("Europe/Paris");
        $relation->personnages = [];
        return $relation;
    }

    /**
     * Retourne les relations actifs.
     * 
     * @return Query La requete des relations actifs.
     */
    public function findActive(Query $query, $options)
    {
        return $this->find()->where(["suppression_date IS" => null]);
    }

    /**
     * Retourne les relations inactifs.
     * 
     * @return Query La requête des relations inactifs.
     */
    public function findInactive(Query $query, $options)
    {
        return $this->find()->where(["suppression_date IS NOT" => null]);
    }

    /**
     * Retourne le relation avec ses associations.
     * 
     * @return Query La requête du relation avec ses associations.
     */
    public function getWithAssociations($primaryKey)
    {
        return $this->get($primaryKey, [
            'contain' => [
                'personnages'
            ]
        ]);
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
        // Si les personnages sont bien fournies dans les données de modification.
        if (!empty($options["personnages"])) {

            // Pour chaque personnage dans les données du formulaire, récupération de toutes ses informations à partir de son identifiant.
            $personnages = array_map(function ($personnage) {
                return $this->personnages->get($personnage);
            }, $options["personnages"]);

            // Tri des personnages par ordre alphabétique dans la relation.
            usort($personnages, function ($perso1, $perso2) {
                return strcmp(strtolower($perso1->nom), strtolower($perso2->nom));
            });

            // Automatisation du nom de la relation avec le nom des personnages.
            $entity->nom = implode(" / ", array_column($personnages, "nom"));
            $entity->personnages = $personnages;
        }
    }
}
