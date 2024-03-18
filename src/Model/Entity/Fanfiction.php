<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

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

    // Propriétés virtuelles, calculées à partir des données de la série déjà connues.
    protected $_virtual = ["classement_obj", "note_obj", "langage_obj"];

    // Cache des propriétés virtuelles pour éviter de recalculer les propriétés virtuelles chaque fois qu'elles sont utilisées.
    protected $_cache = ["_classement_obj", "note_obj", "_langage_obj"];

    /**
     * Setter personnalisé pour le nom
     * @return string Le nom sans espace avant ou après, et avec la première lettre en majuscule.
     */
    protected function _setNom(string $name): ?string
    {
        return trim(ucfirst($name));
    }

    /**
     * Setter personnalisé pour la description
     * @return string La description sans espace avant ou après, et avec la première lettre en majuscule.
     */
    protected function _setDescription(string $description): ?string
    {
        return trim(ucfirst($description));
    }

    /**
     * Setter personnalisé pour l'évaluation
     * @return string L'évaluation sans espace avant ou après, et avec la première lettre en majuscule.
     */
    protected function _setEvaluation(?string $evaluation): ?string
    {
        return !is_null($evaluation) ? trim(ucfirst($evaluation)) : null;
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
     * @var FrozenTime|string|null $suppression_date La nouvelle date.
     * @return FrozenTime|null La date de suppression à l'horaire de Paris.
     */
    protected function _setSuppressionDate(FrozenTime|string|null $suppression_date): ?FrozenTime
    {
        return !empty($suppression_date) ? FrozenTime::createFromFormat("Y-m-d H:i:s", is_string($suppression_date) ? $suppression_date : $suppression_date->format("Y-m-d H:i:s"), "Europe/Paris") : null;
    }

    /**
     * Getter personnalisé pour le classement sous forme de chaine de caractères.
     *
     * @return string|null Le classement sous forme de chaine de caractères.
     */
    protected function _getClassementObj(): ?string
    {
        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_classement_obj", $this->_cache) || is_null($this->_cache["_classement_obj"])) {

            $this->_cache["_classement_obj"] = !empty($this->classement) ? Configure::read("parametres.Classement", [0 => "A", 1 => "B", 2 => "C", 3 => "D", 4 => "E", 5 => "F"])[$this->classement] : "";
        }
        return $this->_cache["_classement_obj"];
    }

    /**
     * Getter personnalité pour la note sous forme de chaîne de caractères.
     *
     * @return string|null La note sous forme de chaîne de caractères.
     */
    protected function _getNoteObj(): ?string
    {
        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_note_obj", $this->_cache) || is_null($this->_cache["_note_obj"])) {

            $this->_cache["_note_obj"] = !empty($this->note) ? Configure::read("parametres.Note", [0 => "A", 1 => "B", 2 => "C", 3 => "D", 4 => "E", 5 => "F"])[$this->classement] : "";
        }
        return $this->_cache["_note_obj"];
    }

    /**
     * Getter personnalisé pour le langage sous forme de chaîne de caractères.
     *
     * @return string|null Le langage sous forme de chaîne de caractères.
     */
    protected function _getLangageObj(): ?string
    {
        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("langage_obj", $this->_cache) || is_null($this->_cache["langage_obj"])) {

            $this->_cache["langage_obj"] = !empty($this->langage) ? TableRegistry::getTableLocator()->get("Langages")->get($this->langage)->nom : "";
        }
        return $this->_cache["langage_obj"];
    }
}
