<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SeriesRelations Model
 *
 * @property \App\Model\Table\SeriesTable&\Cake\ORM\Association\BelongsTo $Series
 *
 * @method \App\Model\Entity\SeriesRelation newEmptyEntity()
 * @method \App\Model\Entity\SeriesRelation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesRelation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesRelation get($primaryKey, $options = [])
 * @method \App\Model\Entity\SeriesRelation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SeriesRelation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesRelation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesRelation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesRelation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesRelation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesRelation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesRelation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesRelation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SeriesRelationsTable extends Table
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

        $this->setTable('series_relations');
        $this->setDisplayField('relation_nom');

        $this->belongsTo('Series', [
            'foreignKey' => 'series_id',
            'joinType' => 'INNER',
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
            ->scalar('relation_nom')
            ->maxLength('relation_nom', 75)
            ->notEmptyString('relation_nom');

        $validator
            ->integer('series_id')
            ->notEmptyString('series_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('series_id', 'Series'), ['errorField' => 'series_id']);

        return $rules;
    }
}
