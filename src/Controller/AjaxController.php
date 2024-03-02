<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Cake\ORM\Table;

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
      $array["http"] = 200;
      return $this->response->withStringBody(json_encode($array));
   }

   /**
    * Méthode pour récupérer l'objet vide à créer et sa table pour le sauvegarder.
    *
    * @return array
    */
   private function getVariables()
   {
      $type = ucfirst($this->Url->getObject($this->request->getParsedBody()["_object"]));
      $singular = substr($this->Url->getObject($this->request->getParsedBody()["_object"]), 0, -1);
      $$singular = $this->$type->newEmptyEntity();

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

         // Si le premier caractère est un underscore ou que la donnée
         if ($key[0] !== '_' && !strpos($key, 'date')) {

            if (!is_array($value))
               // Si la donnée n'est pas un tableau, elle est remise directe dans les données n
               $data[$key] = $value;
            else {
               // La donnée est un tableau (name[])

               // Retirer le tableau du nom
               $key = str_replace("[]", "", $key);

               // Récupérer le nom de la table.
               $table = ucfirst($key);

               // Initialiser le tableau dans les données nettoyées.
               $data[$key] = [];

               // Récupération des objets du tableau à partir de leurs identifiants et valorisation de ceux-ci dans le tableau des données nettoyées.
               foreach ($value as $id) $data[$key][] = $this->$table->get($id);
            }
         }
      }

      // Retourne les données nettoyées.
      return $data;
   }

   /**
    * 
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

         // Si l'entité est bien créée et sauvegardée.
         if ($entity = $this->$action($entity, $table))

            // Retourne l'entité et la liste des entités, comprenant la nouvelle entité.
            return $this->returnSuccessJson([
               "entity" => $entity,
               "list" => $this->$table->find("list")->order(["nom"])->toArray()
            ]);
         else
            // Erreur rencontrée lors de la création ou de la sauvegarde de l'entité.
            return $this->returnSuccessJson(["http" => 500]);
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
    * @return void
    */
   private function _add(Entity $entity, string $table)
   {

      // Nettoyage des données à valoriser dans la nouvelle entité.
      $data = $this->cleanData();

      // Valorisation de la date de création avec la date d'update envoyée par l'appel Ajax.
      $data["creation_date"] = FrozenTime::createFromFormat("Y-m-d H:i:s", $this->request->getParsedBody()["update_date"], "Europe/Paris");

      // Valorisation des autres données de l'entité avec les données de l'appel Ajax.
      $entity = $this->Relations->patchEntity($entity, $data);

      // Selon le type de l'entité.
      switch ($table) {

            // Si entité de type Relations
         case "Relations";

            // Les personnages sont ajoutées à l'entité.
            $entity->personnages = $data["personnages"];

            // Tri des personnages par ordre alphabétique dans la relation.
            usort($entity->personnages, function ($perso1, $perso2) {
               return strcmp(strtolower($perso1->nom), strtolower($perso2->nom));
            });

            // Nom valorisé avec le nom des personnages dans l'ordre alphabétique            $entity->nom = implode(" / ", array_column($entity->personnages, "nom"));
            break;

            // Si entité est un autre type
         default:
            break;
      }

      // Sauvegarde l'entité.
      if ($this->$table->save($entity))
         // Sauvegarde avec succès, retourne l'entité.
         return $entity;
      else
         // Erreur lors de la sauvegarde, retourne faux.
         return false;
   }
}
