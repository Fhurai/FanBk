<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * FanfictionsLien Entity
 *
 * @property int $id
 * @property string $lien
 * @property int $fanfiction
 * @property \Cake\I18n\FrozenTime $creation_date
 * @property \Cake\I18n\FrozenTime $update_date
 * @property \Cake\I18n\FrozenTime|null $suppression_date
 */
class FanfictionsLien extends Entity
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
        'lien' => true,
        'fanfiction' => true,
        'creation_date' => true,
        'update_date' => true,
        'suppression_date' => true,
    ];

    /**
     * Setter personnalisé pour le lien
     * @return string Le lien sans espace avant ou après.
     */
    protected function _setLien(string $lien): ?string
    {
        return trim($lien);
    }

    /**
     * Setter personnalisé pour la date de création
     * @return FrozenTime|null La date de création à l'horaire de Paris.
     */
    protected function _setCreationDate(FrozenTime|string $creation_date): ?FrozenTime
    {
        return FrozenTime::createFromFormat("Y-m-d H:i:s", is_string($creation_date) ? $creation_date : $creation_date->format("Y-m-d H:i:s"), "Europe/Paris");
    }

    /**
     * Setter personnalisé pour la date de modification
     * @return FrozenTime|null La date de modification à l'horaire de Paris.
     */
    protected function _setUpdateDate(FrozenTime|string $update_date): ?FrozenTime
    {
        return FrozenTime::createFromFormat("Y-m-d H:i:s", is_string($update_date) ? $update_date : $update_date->format("Y-m-d H:i:s"), "Europe/Paris");
    }

    /**
     * Setter personnalisé pour la date de suppression
     * @return FrozenTime|null La date de suppression à l'horaire de Paris.
     */
    protected function _setSuppressionDate(FrozenTime|string $suppression_date): ?FrozenTime
    {
        return FrozenTime::createFromFormat("Y-m-d H:i:s", is_string($suppression_date) ? $suppression_date : $suppression_date->format("Y-m-d H:i:s"), "Europe/Paris");
    }
}
