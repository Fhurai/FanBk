<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SeriesPersonnages Model
 *
 * @property \App\Model\Table\SeriesTable&\Cake\ORM\Association\BelongsTo $Series
 *
 * @method \App\Model\Entity\SeriesPersonnage newEmptyEntity()
 * @method \App\Model\Entity\SeriesPersonnage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesPersonnage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SeriesPersonnage get($primaryKey, $options = [])
 * @method \App\Model\Entity\SeriesPersonnage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SeriesPersonnage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesPersonnage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SeriesPersonnage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesPersonnage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SeriesPersonnage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesPersonnage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesPersonnage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SeriesPersonnage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SeriesPersonnagesTable extends Table
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

        $this->setTable('series_personnages');
        $this->setDisplayField('personnage_nom');

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
            ->scalar('personnage_nom')
            ->maxLength('personnage_nom', 50)
            ->notEmptyString('personnage_nom');

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
