<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Url component
 */
class UrlComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    /**
     * Tableau des correspondances pour que les objets manipulés ne soient pas visible dans les URL.
     * 
     * @var array<string>
     */
    private array $_OBJECTS = [
        "a7" => "auteurs",
        "f7" => "fandoms",
        "l8" => "langages",
        "p11" => "personnages",
        "r9" => "relations",
        "t4" => "tags",
        "f11" => "fanfictions",
        "s6" => "series",
        "u5" => "users"
    ];

    /**
     * Tableau des correspondances pour que les actions manipulés ne soient pas visible dans les URL.
     * 
     * @var array<string>
     */
    private array $_ACTIONS = [
        "a3" => "add",
        "g3" => "get",
        "d6" => "delete",
        "e4" => "edit",
        "e6" => "exists",
        "g6" => "getAll",
        "n4" => "note",
        "g11" => "getFiltered"
    ];

    /**
     * Méthode pour envoyer les tableau de corresponances dans le template.
     * 
     * @return void
     */
    public function setArrays()
    {

        // Envoi du tableau de crytapge des objets au template du controller.
        $this->getController()->set("objects", $this->_OBJECTS);

        // Envoi du tableau de crytapge des actions au template du controller.
        $this->getController()->set("actions", $this->_ACTIONS);
    }

    /**
     * Méthode pour récupérer l'objet à manipuler depuis le chaîne cryptée dans l'url.
     *
     * @param string $field L'objet crypté manipulé.
     * @return string L'objet non crypté manipulé.
     */
    public function getObject(string $field)
    {
        return $this->_OBJECTS[$field];
    }

    /**
     * Méthode pour récupérer l'action à manipuler depuis le chaîne cryptée dans l'url.
     *
     * @param string $field L'action cryptée manipulée.
     * @return string L'action non cryptée manipulée.
     */
    public function getAction(string $field)
    {
        return $this->_ACTIONS[$field];
    }
}
