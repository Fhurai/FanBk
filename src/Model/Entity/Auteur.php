<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Auteur Entity
 *
 * @property int $id
 * @property string $nom
 * @property \Cake\I18n\FrozenTime $creation_date
 * @property \Cake\I18n\FrozenTime $update_date
 * @property \Cake\I18n\FrozenTime|null $suppression_date
 */
class Auteur extends Entity
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
        'creation_date' => true,
        'update_date' => true,
        'suppression_date' => true,
    ];
}
