<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SeriesFandoms Model
 *
 * @property \App\Model\Table\SeriesTable&\Cake\ORM\Association\BelongsTo $Series
 *
 * @method \App\Model\Entity\SeriesFandom newEmptyEntity()
 * @method \App\Model\Entity\SeriesFandom newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesFandom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesFandom get($primaryKey, $options = [])
 * @method \App\Model\Entity\SeriesFandom findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SeriesFandom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesFandom[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesFandom|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesFandom saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesFandom[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesFandom[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesFandom[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesFandom[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SeriesFandomsTable extends Table
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

        $this->setTable('series_fandoms');
        $this->setDisplayField('fandom_nom');

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
            ->scalar('fandom_nom')
            ->maxLength('fandom_nom', 50)
            ->notEmptyString('fandom_nom');

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
