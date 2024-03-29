<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Tag;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tags Model
 *
 * @method \App\Model\Entity\Tag newEmptyEntity()
 * @method \App\Model\Entity\Tag newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Tag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tag get($primaryKey, $options = [])
 * @method \App\Model\Entity\Tag findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Tag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tag[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tag|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tag saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TagsTable extends Table
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

        $this->setTable('tags');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');

        $this->belongsToMany('fanfictions', [
            'foreignKey' => 'tag',
            'targetForeignKey' => 'fanfiction',
            'joinTable' => 'fanfictions_tags',
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
            ->scalar('description')
            ->allowEmptyString('description');

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
     * @return Tag
     */
    public function newEmptyEntity(): Tag
    {
        $tag = new Tag();
        $tag->id = null;
        $tag->nom = "";
        $tag->description = "";
        $tag->creation_date = new FrozenTime("Europe/Paris");
        $tag->update_date = new FrozenTime("Europe/Paris");
        return $tag;
    }

    /**
     * Retourne les tags actifs.
     * 
     * @return Query La requete des tags actifs.
     */
    public function findActive(Query $query, $options)
    {
        return $this->find()->contain(["fanfictions"])->where(["suppression_date IS" => null]);
    }

    /**
     * Retourne les tags inactifs.
     * 
     * @return Query La requête des tags inactifs.
     */
    public function findInactive(Query $query, $options)
    {
        return $this->find()->contain(["fanfictions"])->where(["suppression_date IS NOT" => null]);
    }

    /**
     * Retourne le tag avec toutes ses associations.
     * 
     * @return Query La requête du tag avec ses associations.
     */
    public function getWithAssociations($primaryKey): Tag
    {
        return $this->get($primaryKey, [
            "contain" =>  [
                "fanfictions"
            ]
        ]);
    }
}
