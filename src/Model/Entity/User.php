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

    // Propriétés virtuelles, calculées à partir des données de l'utilisateur déjà connues.
    protected $_virtual = ["age", "nsfw"];

    // Cache des propriétés virtuelles pour éviter de recalculer les propriétés virtuelles chaque fois qu'elles sont utilisées.
    protected $_cache = ["_age", "_nsfw"];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    /**
     * Setter personnalisé pour le nom d'utilisateur
     * @return string Le nom d'utilisateur sans espace avant ou après, et avec la première lettre en majuscule.
     */
    protected function _setUsername(string $username): ?string
    {
        return trim(ucfirst($username));
    }

    /**
     * Setter personnalisé pour l'anniversaire
     * @return FrozenTime|null L'anniversaire à l'horaire de Paris.
     */
    protected function _setBirthday(FrozenTime|string $birthday): ?FrozenTime
    {
        return FrozenTime::createFromFormat("Y-m-d H:i:s", is_string($birthday) ? $birthday : $birthday->format("Y-m-d H:i:s"), "Europe/Paris");
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

    /**
     * Setter personnalisé pour le mot de passe.
     * @return string Le mot de passe crypté.
     */
    protected function _setPassword(string $password): ?string
    {
        // Si le mot de passe envoyée par l'utilisateur fait au moins un caractère.
        if (strlen($password) > 0) {

            // Retourne le mot de passe crypté.
            return (new DefaultPasswordHasher())->hash($password);
        }
    }

    /**
     * Getter pour la propriété virtuelle de l'age.
     * @return int|null l'âge de l'utilisateur.
     */
    protected function _getAge(): ?int
    {
        // SI la clé _age existe dans le tableau du cache et que la valeur est vide
        if (!array_key_exists("_age", $this->_cache) && empty($this->_cache["_age"])) {

            if (!is_null($this->birthday)) {
                // Si la date d'anniversaire n'est pas nulle, on fait la comparaison avec la date d'aujourd'hui.
                // Le nombre d'année est utilisé dans la valorisation de l'âge.
                $this->_cache["_age"] = $this->birthday->diff(FrozenTime::now("Europe/Paris"))->y;
            } else
                // Pas de date d'anniversaire, pas de comparaison possible.
                // L'âge est nul dans le cache.
                $this->_cache["_age"] = null;
        }
        // Retourne l'âge enregistré dans le cache.
        return !empty($this->_cache["_age"]) ? intval($this->_cache["_age"]) : null;
    }

    /**
     * Getter pour la propriété virtuelle de l'autorisation nsfw.
     * @return bool l'indication si l'utilisateur peut voir du NSFW ou non.
     */
    protected function _getNsfw(): ?bool
    {
        // SI la clé _nsfw existe dans le tableau du cache et que la valeur est vide
        if (!array_key_exists("_nsfw", $this->_cache)  && empty($this->_cache["_nsfw"])) {

            if (!is_null($this->birthday)) {
                // Si la date d'anniversaire n'est pas nulle, on fait la comparaison avec la date d'aujourd'hui.
                // Le nombre d'année de la comparaison sert à valoriser l'indication que l'utilisateur peut voir du NSFW ou non.
                $this->_cache["_nsfw"] = $this->birthday->diff(FrozenTime::now("Europe/Paris"))->y > 18;
            } else
                // Pas de date d'anniversaire, pas de comparaison possible.
                // L'indication est mise à faux dans le cache.
                $this->_cache["_nsfw"] = false;
        }
        // Retourne l'indication enregistrée dans le cache.
        return boolval($this->_cache["_nsfw"]);
    }
}
