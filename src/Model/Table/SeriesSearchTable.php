<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SeriesSearch Model
 *
 * @method \App\Model\Entity\SeriesSearch newEmptyEntity()
 * @method \App\Model\Entity\SeriesSearch newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesSearch[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesSearch get($primaryKey, $options = [])
 * @method \App\Model\Entity\SeriesSearch findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SeriesSearch patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesSearch[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesSearch|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesSearch saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesSearch[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesSearch[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesSearch[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesSearch[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SeriesSearchTable extends Table
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

        $this->setTable('series_search');
        $this->setDisplayField('nom');
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
            ->integer('id')
            ->notEmptyString('id');

        $validator
            ->scalar('nom')
            ->maxLength('nom', 50)
            ->notEmptyString('nom');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('classement')
            ->notEmptyString('classement');

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
            ->scalar('fanfiction')
            ->maxLength('fanfiction', 50)
            ->notEmptyString('fanfiction');

        $validator
            ->scalar('auteur')
            ->maxLength('auteur', 50)
            ->notEmptyString('auteur');

        return $validator;
    }
}
