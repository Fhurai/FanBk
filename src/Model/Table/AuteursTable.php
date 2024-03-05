<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Auteur;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Auteurs Model
 *
 * @method \App\Model\Entity\Auteur newEmptyEntity()
 * @method \App\Model\Entity\Auteur newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Auteur[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Auteur get($primaryKey, $options = [])
 * @method \App\Model\Entity\Auteur findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Auteur patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Auteur[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Auteur|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Auteur saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Auteur[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Auteur[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Auteur[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Auteur[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AuteursTable extends Table
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

        $this->setTable('auteurs');
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
     * @return Auteur
     */
    public function newEmptyEntity(): Auteur
    {
        $fandom = new Auteur();
        $fandom->id = null;
        $fandom->nom = "";
        $fandom->creation_date = new FrozenTime("Europe/Paris");
        $fandom->update_date = new FrozenTime("Europe/Paris");
        return $fandom;
    }

    /**
     * Retourne les auteurs actifs.
     * 
     * @return Query La requete des auteurs actifs.
     */
    public function findActive(Query $query, $options)
    {
        return $this->find()->where(["suppression_date IS" => null]);
    }

    /**
     * Retourne les auteurs inactifs.
     * 
     * @return Query La requête des auteurs inactifs.
     */
    public function findInactive(Query $query, $options)
    {
        return $this->find()->where(["suppression_date IS NOT" => null]);
    }

    /**
     * Retourne l'auteur avec toutes ses associations.
     * 
     * @return Query La requête de l'auteur avec ses associations.
     */
    public function getWithAssociations($primaryKey): Auteur
    {
        return $this->get($primaryKey);
    }
}
