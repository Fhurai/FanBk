<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Personnage;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Personnages Model
 *
 * @property \App\Model\Table\RelationsTable&\Cake\ORM\Association\BelongsToMany $Relations
 *
 * @method \App\Model\Entity\Personnage newEmptyEntity()
 * @method \App\Model\Entity\Personnage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Personnage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Personnage get($primaryKey, $options = [])
 * @method \App\Model\Entity\Personnage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Personnage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Personnage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Personnage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Personnage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Personnage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Personnage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Personnage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Personnage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class PersonnagesTable extends Table
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

        $this->setTable('personnages');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');

        $this->hasOne('fandoms', [
            'bindingKey' => 'fandom',
            'className' => 'Fandoms',
            'foreignKey' => 'id',
            'propertyName' => 'fandom_obj'
        ]);

        $this->belongsToMany('relations', [
            'foreignKey' => 'personnage',
            'targetForeignKey' => 'relation',
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
            ->maxLength('nom', 50)
            ->notEmptyString('nom');

        $validator
            ->integer('fandom')
            ->notEmptyString('fandom');

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
     * @return Personnage
     */
    public function newEmptyEntity(): Personnage{
        $personnage = new Personnage();
        $personnage->id = null;
        $personnage->nom = "Nouveau personnage";
        $personnage->fandom = 1;
        $personnage->creation_date = new FrozenTime("Europe/Paris");
        $personnage->update_date = new FrozenTime("Europe/Paris");
        return $personnage;
    }

    /**
     * Retourne les personnages actifs.
     * 
     * @return Query La requete des personnages actifs.
     */
    public function findActive(Query $query, $options){
        return $this->find()->where(["suppression_date IS" => null]);
    }

    /**
     * Retourne les personnages inactifs.
     * 
     * @return Query La requête des personnages inactifs.
     */
    public function findInactive(Query $query, $options){
        return $this->find()->where(["suppression_date IS NOT" => null]);
    }

    /**
     * Retourne le personnage avec ses associations.
     * 
     * @return Query La requête du personnage avec ses associations.
     */
    public function getWithAssociations($primaryKey){
        return $this->get($primaryKey, [
            'contain' => [
                'fandoms',
                'relations'
            ]
        ]);
    }
}
