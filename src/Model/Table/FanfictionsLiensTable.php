<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FanfictionsLiens Model
 *
 * @method \App\Model\Entity\FanfictionsLien newEmptyEntity()
 * @method \App\Model\Entity\FanfictionsLien newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FanfictionsLien[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FanfictionsLien get($primaryKey, $options = [])
 * @method \App\Model\Entity\FanfictionsLien findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FanfictionsLien patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FanfictionsLien[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FanfictionsLien|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FanfictionsLien saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FanfictionsLien[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FanfictionsLien[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FanfictionsLien[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FanfictionsLien[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FanfictionsLiensTable extends Table
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

        $this->setTable('fanfictions_liens');
        $this->setDisplayField('lien');
        $this->setPrimaryKey('id');

        $this->belongsTo('fanfictions', [
            'foreignKey' => 'id',
            'bindingKey' => 'id',
            'className' => 'Fanfictions',
            'propertyName' => 'fanfiction_obj'
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
            ->scalar('lien')
            ->maxLength('lien', 255)
            ->notEmptyString('lien');

        $validator
            ->integer('fanfiction')
            ->notEmptyString('fanfiction');

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
}
