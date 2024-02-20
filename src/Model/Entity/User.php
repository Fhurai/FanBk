<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property bool $is_admin
 * @property \Cake\I18n\FrozenTime|null $birthday
 * @property \Cake\I18n\FrozenTime $creation_date
 * @property \Cake\I18n\FrozenTime $update_date
 * @property \Cake\I18n\FrozenTime|null $suppression_date
 */
class User extends Entity
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
        'username' => true,
        'password' => true,
        'email' => true,
        'is_admin' => true,
        'birthday' => true,
        'creation_date' => true,
        'update_date' => true,
        'suppression_date' => true,
    ];

    protected $_virtual = ["age", "nsfw"];

    protected $_cache = ["_age", "_nsfw"];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    // Add this method
    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }

    protected function _getAge()
    {
        if (!array_key_exists("_age", $this->_cache)) {
            if (!is_null($this->birthday)) {
                $this->_cache["_age"] = $this->birthday->diff(FrozenTime::now("Europe/Paris"))->y;
            } else
                $this->_cache["_age"] = null;
        }
        return $this->_cache["_age"];
    }

    protected function _getNsfw()
    {
        if (!array_key_exists("_nsfw", $this->_cache)) {
            if (!is_null($this->birthday)) {
                $this->_cache["_nsfw"] = $this->birthday->diff(FrozenTime::now("Europe/Paris"))->y > 18;
            } else
                $this->_cache["_nsfw"] = null;
        }
        return $this->_cache["_nsfw"];
    }
}
