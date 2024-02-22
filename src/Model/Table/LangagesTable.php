<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Langage;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Langages Model
 *
 * @method \App\Model\Entity\Langage newEmptyEntity()
 * @method \App\Model\Entity\Langage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Langage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Langage get($primaryKey, $options = [])
 * @method \App\Model\Entity\Langage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Langage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Langage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Langage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Langage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Langage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Langage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Langage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Langage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class LangagesTable extends Table
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

        $this->setTable('langages');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');
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
            ->scalar('abbreviation')
            ->maxLength('abbreviation', 2)
            ->notEmptyString('abbreviation');

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
     * @return Langage
     */
    public function newEmptyEntity(): Langage
    {
        $fandom = new Langage();
        $fandom->id = null;
        $fandom->nom = "";
        $fandom->abbreviation = "";
        $fandom->creation_date = new FrozenTime("Europe/Paris");
        $fandom->update_date = new FrozenTime("Europe/Paris");
        return $fandom;
    }

    /**
     * Retourne les langages actifs.
     * 
     * @return Query La requete des langages actifs.
     */
    public function findActive(Query $query, $options)
    {
        return $this->find()->where(["suppression_date IS" => null]);
    }

    /**
     * Retourne les langages inactifs.
     * 
     * @return Query La requête des langages inactifs.
     */
    public function findInactive(Query $query, $options)
    {
        return $this->find()->where(["suppression_date IS NOT" => null]);
    }
}
