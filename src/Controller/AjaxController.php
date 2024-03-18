<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Entity;

/**
 * Ajax Controller
 *
 * @property \App\Controller\Component\UrlComponent $Url
 */
class AjaxController extends AppController
{
   /**
    * @inheritdoc
    */
   public function initialize(): void
   {
      // Appel à l'initialisation du parent.
      parent::initialize();

      // Utilisation du layout Ajax.
      $this->viewBuilder()->setLayout("ajax");

      // Chargement du composant URL dans le controller et ses enfants.
      $this->Url = $this->loadComponent("url");
   }

   /**
    * Méthode pour retourner une réponse HTML sous format JSON à partir d'un tableau.
    *
    * @var array<mixed> Tableau de données à envoyer en réponse.
    *
    * @return string Le tableau sous forme d'une chaîne de caractères.
    */
   private function returnSuccessJson(array $array)
   {
      // Ajoute la réponse HTTP 200 au tableau de données à renvoyer au format JSON.
      $array["http"] = 200;

      // Retourne le tableau de données sous forme de string JSON.
      return $this->response->withStringBody(json_encode($array));
   }

   /**
    * Méthode pour récupérer l'objet vide à créer et sa table pour le sauvegarder.
    *
    * @return array [entité, $table]
    */
   private function getVariables()
   {
      // Récupère le type d'objet manipulé décrypté
      $type = ucfirst($this->Url->getObject($this->request->getParsedBody()["_object"]));

      // Récupère le nom de l'objet manipulé décrypté.
      $singular = substr($this->Url->getObject($this->request->getParsedBody()["_object"]), 0, -1);

      // Récupère l'entité manipulé.
      $$singular = (isset($this->request->getParsedBody()["id"]) ? $this->$type->get($this->request->getParsedBody()["id"]) : $this->$type->newEmptyEntity());

      // Retourne l'entité et son type/sa table.
      return [$$singular, $type];
   }

   /**
    * Méthode pour nettoyer les données reçues afin de les utiliser dans la méthode appelée après cette méthode.
    *
    * @return array $data
    */
   private function cleanData()
   {
      //Initialisation du tableau des données nettoyées.
      $data = [];

      // Parcours des données envoyées par l'appel Ajax.
      foreach ($this->request->getParsedBody() as $key => $value) {

         // Si le premier caractère est un underscore, on la remet dans les données clean.
         if ($key[0] !== '_') {
            $data[str_replace("[]", "", $key)] = $value;
         }
      }

      // Retourne les données nettoyées.
      return $data;
   }

   /**
    * Méthode pour récupérer un formulaire correspondant au type de donnée manipulée.
    *
    * @return Response Le formulaire sous forme HTML.
    */
   public function getForm()
   {
      // Layout non rendu.
      $this->autoRender = false;

      // Récupération des données depuis l'appel Ajax.
      $data = $this->request->getParsedBody();

      // Envoi des tableau de correspondance au template pour la traduction URL.
      $this->Url->setArrays();

      // Envoi de l'objet comme type manipulé dans le template.
      $this->set("type", $this->Url->getObject($data["object"]));

      // Initialisation de l'objet manipulé
      $singular = substr($this->Url->getObject($data["object"]), 0, -1);
      $$singular = $this->Auteurs->newEmptyEntity();


      if ($singular == "relation")
         // Si l'objet manipulé est une relation,
         // Récupération des personnages sous forme de query, groupés par nom de fandom et envoi au template.
         $this->set("personnages", $this->Relations->personnages->find('list', [
            "keyField" => "id",
            "valueField" => "nom",
            "groupField" => "fandom_obj.nom"
         ])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"]));
      elseif ($singular === "personnage")
         // Si l'objet manipulé est un personnage, envoi des fandoms au template.
         $this->set("fandoms", $this->Fandoms->find('list')->order(["nom" => "ASC"]));

      if ($this->Url->getAction($data["action"]) === "note") {
         // Envoi de l'identifiant dans le template.
         $this->set("id", $data["id"]);

         $this->set("parametres", Configure::read("parametres"));
      }

      // Envoi de l'objet manipulé dans le template.
      $this->set($singular, $$singular);

      // Retourne le template demandé par la requête Ajax avec toutes les données fournies.
      return $this->render($this->Url->getAction($data["action"]), null);
   }

   /**
    * Méthode appelée pour une action sur  les données.
    *
    * @return string
    * @throws BadRequestException Access n'est pas un appel ajax.
    */
   public function call()
   {
      // Layout non rendu.
      $this->autoRender = false;

      // Si la page est appelé par une method POST
      if ($this->request->is("post")) {

         // Récupération de l'action à accomplir.
         $action = "_" . $this->Url->getAction($this->request->getParsedBody()["_action"]);

         // Récupération de l'entité et de sa table.
         [$entity, $table] = $this->getVariables();

         if ($action !== "_getAll" && $action !== "_getFiltered") {
            // Si l'entité est bien créée et sauvegardée.
            if ($entity = $this->$action($entity, $table))

               // Retourne l'entité et la liste des entités, comprenant la nouvelle entité.
               return $this->returnSuccessJson([
                  "entity" => $entity,
                  "list" => ($action === "_note" ? $this->$table->find("active")->toArray() : $this->$table->find("list")->order(["nom"])->toArray())
               ]);
            else
               // Erreur rencontrée lors de la création ou de la sauvegarde de l'entité.
               return $this->returnSuccessJson(["http" => 500]);
         } else
            // L'appel est pour une liste d'entité à renvoyer.
            return $this->returnSuccessJson([
               "list" => $this->$action($table, $this->request->getParsedBody()["active"])
            ]);
      }

      // La page n'est pas appelée par un appel Ajax, access interdit.
      throw new BadRequestException(
         "No direct access to this page."
      );
   }

   /**
    * Méthode d'ajout d'une nouvelle entité.
    *
    * @param Entity $entity L'entité à créer.
    * @param string $table La table pour manipuler l'entitée.
    * @return Entity|bool L'entité créée si succes. Sinon false.
    */
   private function _add(Entity $entity, string $table)
   {

      // Nettoyage des données à valoriser dans la nouvelle entité.
      $data = $this->cleanData();

      // Valorisation des autres données de l'entité avec les données de l'appel Ajax.
      $entity = $this->$table->patchEntity($entity, $data);

      // Sauvegarde l'entité.
      if ($this->$table->save($entity))
         // Sauvegarde avec succès, retourne l'entité.
         return $entity;
      else
         // Erreur lors de la sauvegarde, retourne faux.
         return false;
   }

   /**
    * Méthode de notation d'une entité.
    *
    * @param Entity $entity L'entité à noter.
    * @param string $table La table pour manipuler l'entité.
    * @return Entiity|bool L'entité noté si succes. Sinon false.
    */
   private function _note(Entity $entity, string $table)
   {
      // Nettoyage des données à valoriser dans la nouvelle entité.
      $data = $this->cleanData();

      // Valorisation des autres données de l'entité avec les données de l'appel Ajax.
      $entity = $this->$table->patchEntity($entity, $data);

      // Sauvegarde l'entité.
      if ($this->$table->save($entity))
         // Sauvegarde avec succès, retourne l'entité.
         return $entity;
      else
         // Erreur lors de la sauvegarde, retourne faux.
         return false;
   }

   /**
    * Méthode pour récupérer toutes les entités sans conditions à part celle d'activité.
    *
    * @param string $table La table pour manipuler les entités.
    * @param boolean $actives Indication si entités actives ou non.
    * @return Query La requête de récupération des entités.
    */
   private function _getAll(string $table, bool $actives = true)
   {
      return $this->$table->find($actives ? "active" : "inactive");
   }

   /**
    * Méthode pour récupérer les entités correspondantes aux conditions de filtre.
    *
    * @param string $table La table pour manipuler les entités.
    * @param boolean $actives Indication si entités actives ou non.
    * @return Query La requête de récupération des entités.
    */
   private function _getFiltered(string $table, bool $actives = true)
   {
      return $this->$table->find("search", array_merge($this->request->getParsedBody(), ["nsfw" => $this->request->getSession()->read("user.nsfw")]));
   }
}
