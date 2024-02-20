<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SeriesPersonnage Entity
 *
 * @property string $personnage_nom
 * @property int $series_id
 *
 * @property \App\Model\Entity\Series $series
 */
class SeriesPersonnage extends Entity
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
        'personnage_nom' => true,
        'series_id' => true,
        'series' => true,
    ];
}
