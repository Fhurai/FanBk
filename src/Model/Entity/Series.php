<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Series Entity
 *
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property int|null $note
 * @property string|null $evaluation
 * @property \Cake\I18n\FrozenTime $creation_date
 * @property \Cake\I18n\FrozenTime $update_date
 * @property \Cake\I18n\FrozenTime|null $suppression_date
 * @property int|null $classement
 * @property array|null $_joinData
 *
 * @property \App\Model\Entity\Fanfiction[] $fanfictions
 * @property \App\Model\Entity\Fanfiction[] $langages
 * @property \App\Model\Entity\Fanfiction[] $auteurs
 * @property \App\Model\Entity\Fanfiction[] $fandoms
 * @property \App\Model\Entity\Fanfiction[] $relations
 * @property \App\Model\Entity\Fanfiction[] $personnages
 * @property \App\Model\Entity\Fanfiction[] $tags
 */
class Series extends Entity
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
        'description' => true,
        'note' => true,
        'evaluation' => true,
        'creation_date' => true,
        'update_date' => true,
        'suppression_date' => true,
        'classement' => true,
        '_joinData' => true,
        'fanfictions' => true,
        'langages' => true,
        'auteurs' => true,
        'fandoms' => true,
        'relations' => true,
        'personnages' => true,
        'tags' => true,
    ];

    // Propriétés virtuelles, calculées à partir des données de la série déjà connues.
    protected $_virtual = ["classement", "classement_obj", "langages", "langage_obj", "auteurs", "fandoms", "relations", "personnages", "tags"];

    // Cache des propriétés virtuelles pour éviter de recalculer les propriétés virtuelles chaque fois qu'elles sont utilisées.
    protected $_cache = ["_classement", "_classement_obj", "_langages", "_langage_obj", "_auteurs", "_fandoms", "_relations", "_personnages", "_tags"];

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
     * Classement de la série en fonction des classements de ses fanfictions.
     * @return int|boolean La valeur max de classement, sinon false.
     */
    protected function _getClassement(): mixed
    {

        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_classement", $this->_cache) || is_null($this->_cache["_classement"])) {

            if (!empty($this->fanfictions)) //Vérification que les fanfictions de la série existent
                $this->_cache["_classement"] = max(array_column($this->fanfictions, "classement")); // Classement de la série est le classement le plus haut des fanfictions
            else //Pas de fanfiction disponible
                $this->_cache["_classement"] = false; // Classement n'existe pas.

        }
        return $this->_cache["_classement"];
    }

    /**
     * Getter personnalisé pour le classement sous forme de chaîne de caractères.
     *
     * @return string|null Le classement sous forme de chaîne de caractères.
     */
    protected function _getClassementObj(): ?string
    {
        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_classement_obj", $this->_cache) || is_null($this->_cache["_classement_obj"])) {

            $this->_cache["_classement_obj"] = Configure::read("parametres.Classement", [0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => ""])[$this->classement];
        }
        return $this->_cache["_classement_obj"];
    }

    /**
     * Langages de la série en fonction des langages de ses fanfictions.
     * @return array|boolean Le tableau des langages, sinon false.
     */
    protected function _getLangages()
    {

        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_langages", $this->_cache) || is_null($this->_cache["_langages"])) {

            if (!empty($this->fanfictions)) //Vérification que les fanfictions de la série existent
                $this->_cache["_langages"] = array_unique(array_column($this->fanfictions, "langage")); // Langages de la série sont celles des fanfictions.
            else //Pas de fanfiction disponible
                $this->_cache["_langages"] = false; // Langages n'existent pas.

        }
        return $this->_cache["_langages"];
    }

    /**
     * Getter personnalisé pour le langage sous forme de chaîne de caractères.
     *
     * @return string|null Le langage sous forme de chaîne de caractères.
     */
    protected function _getLangageObj(): ?string
    {
        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_langage_obj", $this->_cache) || is_null($this->_cache["_langage_obj"])) {
            $this->_cache["_langage_obj"] = $this->langages ? implode(" & ", array_map(function ($id) {
                return TableRegistry::getTableLocator()->get("Langages")->get($id)->nom;
            }, $this->langages)) : "";
        }
        return $this->_cache["_langage_obj"];
    }

    /**
     * Auteurs de la série en fonction des auteurs de ses fanfictions.
     * @return array|boolean Le tableau des auteurs, sinon false.
     */
    protected function _getAuteurs()
    {

        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_auteurs", $this->_cache) || is_null($this->_cache["_auteurs"])) {

            if (!empty($this->fanfictions)) //Vérification que les fanfictions de la série existent

                // Auteurs de la série sont celles des fanfictions.
                $this->_cache["_auteurs"] = array_map(function ($id) {
                    return TableRegistry::getTableLocator()->get("Auteurs")->get($id);
                }, array_unique(array_column($this->fanfictions, "auteur")));
            else //Pas de fanfiction disponible
                $this->_cache["_auteurs"] = false; // Auteurs n'existe pas.

        }
        return $this->_cache["_auteurs"];
    }

    /**
     * Fandoms de la série en fonction des fandoms de ses fanfictions.
     * @return string|null Le tableau des fandoms, sinon false.
     */
    protected function _getFandoms()
    {

        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_fandoms", $this->_cache) || is_null($this->_cache["_fandoms"])) {

            //Vérification que les fanfictions de la série existent
            if (!empty($this->fanfictions)) {

                //Initialisation du tableau des fandoms
                $this->_cache["_fandoms"] = [];

                //Merge du tableau des fandoms de la fanfiction parcourue dans le cache des fandoms.
                foreach ($this->fanfictions as $fanfiction) {
                    $this->_cache["_fandoms"] = array_unique(array_merge($this->_cache["_fandoms"], array_column($fanfiction->fandoms, "id")));
                }

                // Récupération de tous les fandoms sous forme d'objet.
                $sortedFandoms = array_map(function ($id) {
                    return TableRegistry::getTableLocator()->get("Fandoms")->get($id);
                }, $this->_cache["_fandoms"]);

                // Tri des fandoms par nom.
                usort($sortedFandoms, function ($a, $b) {
                    return strcmp($a->nom, $b->nom);
                });

                // Valorisation du cache avec le tableau trié de fandoms.
                $this->_cache["_fandoms"] = $sortedFandoms;
            } else //Pas de fanfiction disponible
                $this->_cache["_fandoms"] = ""; // Fandoms n'existe pas.
        }
        return $this->_cache["_fandoms"];
    }

    /**
     * Relations de la série en fonction des relations de ses fanfictions.
     * @return array|boolean Le tableau des relations, sinon false.
     */
    protected function _getRelations()
    {

        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_relations", $this->_cache) || is_null($this->_cache["_relations"])) {

            //Vérification que les fanfictions de la série existent
            if (!empty($this->fanfictions)) {

                //Initialisation du tableau des relations
                $this->_cache["_relations"] = [];

                //Merge du tableau des relations de la fanfiction parcourue dans le cache des relations.
                foreach ($this->fanfictions as $fanfiction) {
                    $this->_cache["_relations"] = !is_null($fanfiction->relations) ? array_unique(array_merge($this->_cache["_relations"], array_column($fanfiction->relations, "id"))) : [];
                }

                // Récupération de tous les relations sous forme d'objet.
                $sortedRelations = array_map(function ($id) {
                    return TableRegistry::getTableLocator()->get("Relations")->get($id);
                }, $this->_cache["_relations"]);

                // Tri des relations par nom.
                usort($sortedRelations, function ($a, $b) {
                    return strcmp($a->nom, $b->nom);
                });

                // Valorisation du cache avec le tableau trié de relations.
                $this->_cache["_relations"] = $sortedRelations;
            } else //Pas de fanfiction disponible
                $this->_cache["_relations"] = false; // Relations n'existe pas.
        }
        return $this->_cache["_relations"];
    }

    /**
     * Personnages de la série en fonction des personnages de ses fanfictions.
     * @return array|boolean Le tableau des personnages, sinon false.
     */
    protected function _getPersonnages()
    {

        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_personnages", $this->_cache) || is_null($this->_cache["_personnages"])) {

            //Vérification que les fanfictions de la série existent
            if (!empty($this->fanfictions)) {

                //Initialisation du tableau des personnages
                $this->_cache["_personnages"] = [];

                //Merge du tableau des personnages de la fanfiction parcourue dans le cache des personnages.
                foreach ($this->fanfictions as $fanfiction) {
                    $this->_cache["_personnages"] = !is_null($fanfiction->personnages) ? array_unique(array_merge($this->_cache["_personnages"], array_column($fanfiction->personnages, "id"))) : [];
                }

                // Récupération de tous les personnages sous forme d'objet.
                $sortedPersonnages = array_map(function ($id) {
                    return TableRegistry::getTableLocator()->get("Personnages")->get($id);
                }, $this->_cache["_personnages"]);

                // Tri des personnages par nom.
                usort($sortedPersonnages, function ($a, $b) {
                    return strcmp($a->nom, $b->nom);
                });

                // Valorisation du cache avec le tableau trié de personnages.
                $this->_cache["_personnages"] = $sortedPersonnages;
            } else //Pas de fanfiction disponible
                $this->_cache["_personnages"] = false; // Personnages n'existe pas.
        }
        return $this->_cache["_personnages"];
    }

    /**
     * Tags de la série en fonction des tags de ses fanfictions.
     * @return array|boolean Le tableau des tags, sinon false.
     */
    protected function _getTags()
    {

        // La variable dans le cache n'existe pas ou est nulle.
        if (!array_key_exists("_tags", $this->_cache) || is_null($this->_cache["_tags"])) {

            //Vérification que les fanfictions de la série existent
            if (!empty($this->fanfictions)) {

                //Initialisation du tableau des tags
                $this->_cache["_tags"] = [];

                //Merge du tableau des tags de la fanfiction parcourue dans le cache des tags.
                foreach ($this->fanfictions as $fanfiction) {
                    $this->_cache["_tags"] = !is_null($fanfiction->tags) ? array_unique(array_merge($this->_cache["_tags"], array_column($fanfiction->tags, "id"))) : [];
                }

                // Récupération de tous les tags sous forme d'objet.
                $sortedTags = array_map(function ($id) {
                    return TableRegistry::getTableLocator()->get("Tags")->get($id);
                }, $this->_cache["_tags"]);

                // Tri des tags par nom.
                usort($sortedTags, function ($a, $b) {
                    return strcmp($a->nom, $b->nom);
                });

                // Valorisation du cache avec le tableau trié de tags.
                $this->_cache["_tags"] = $sortedTags;
            } else //Pas de fanfiction disponible
                $this->_cache["_tags"] = false; // Tags n'existe pas.
        }
        return $this->_cache["_tags"];
    }
}
