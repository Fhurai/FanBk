<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SeriesTags Model
 *
 * @property \App\Model\Table\SeriesTable&\Cake\ORM\Association\BelongsTo $Series
 *
 * @method \App\Model\Entity\SeriesTag newEmptyEntity()
 * @method \App\Model\Entity\SeriesTag newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesTag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesTag get($primaryKey, $options = [])
 * @method \App\Model\Entity\SeriesTag findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SeriesTag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesTag[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesTag|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesTag saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesTag[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesTag[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesTag[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesTag[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SeriesTagsTable extends Table
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

        $this->setTable('series_tags');
        $this->setDisplayField('tag_nom');

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
            ->scalar('tag_nom')
            ->maxLength('tag_nom', 50)
            ->notEmptyString('tag_nom');

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
