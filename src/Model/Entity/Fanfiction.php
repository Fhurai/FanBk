<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Fanfiction Entity
 *
 * @property int $id
 * @property string $nom
 * @property int $auteur
 * @property int $classement
 * @property string|null $description
 * @property int $langage
 * @property int|null $note
 * @property string|null $evaluation
 * @property \Cake\I18n\FrozenTime $creation_date
 * @property \Cake\I18n\FrozenTime $update_date
 * @property \Cake\I18n\FrozenTime|null $suppression_date
 *
 * @property \App\Model\Entity\FanfictionsLien[] $liens
 * @property \App\Model\Entity\Fandom[] $fandoms
 * @property \App\Model\Entity\Personnage[] $personnages
 * @property \App\Model\Entity\Relation[] $relations
 * @property \App\Model\Entity\Tag[] $tags
 */
class Fanfiction extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'nom' => true,
        'auteur' => true,
        'classement' => true,
        'description' => true,
        'langage' => true,
        'note' => true,
        'evaluation' => true,
        'creation_date' => true,
        'update_date' => true,
        'suppression_date' => true,
        'auteur_obj' => true,
        'langage_obj' => true,
        'fandoms' => true,
        'personnages' => true,
        'relations' => true,
        'tags' => true,
        '_joinData' => true
    ];
}
