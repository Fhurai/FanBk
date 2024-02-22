<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Fandom;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Fandoms Model
 *
 * @method \App\Model\Entity\Fandom newEmptyEntity()
 * @method \App\Model\Entity\Fandom newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Fandom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Fandom get($primaryKey, $options = [])
 * @method \App\Model\Entity\Fandom findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Fandom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Fandom[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Fandom|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Fandom saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Fandom[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Fandom[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Fandom[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Fandom[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FandomsTable extends Table
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

        $this->setTable('fandoms');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');

        $this->hasMany('personnages')
            ->setForeignKey('fandom')
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
            ->maxLength('nom', 50)
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
     * @return Fandom
     */
    public function newEmptyEntity(): Fandom{
        $fandom = new Fandom();
        $fandom->id = null;
        $fandom->nom = "";
        $fandom->creation_date = new FrozenTime("Europe/Paris");
        $fandom->update_date = new FrozenTime("Europe/Paris");
        return $fandom;
    }

    /**
     * Retourne les fandoms actifs.
     * 
     * @return Query La requete des fandoms actifs.
     */
    public function findActive(Query $query, $options){
        return $this->find()->where(["suppression_date IS" => null]);
    }

    /**
     * Retourne les fandoms inactifs.
     * 
     * @return Query La requête des fandoms inactifs.
     */
    public function findInactive(Query $query, $options){
        return $this->find()->where(["suppression_date IS NOT" => null]);
    }

    /**
     * Retourne le fandom avec toutes ses associations.
     * 
     * @return Query La requête du fandom avec ses associations.
     */
    public function getWithAssociations($primaryKey): Fandom {
        return $this->get($primaryKey, [
            'contain' => [
                'personnages'
            ]
        ]);
    }
}
