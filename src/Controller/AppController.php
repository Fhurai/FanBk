<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\I18n\FrozenTime;
use Psr\Log\LogLevel;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 * 
 * @property \App\Model\Table\FanfictionsTable $Fanfictions
 * @property \App\Model\Table\FandomsTable $Fandoms
 * @property \App\Model\Table\AuteursTable $Auteurs
 * @property \App\Model\Table\RelationsTable $Relations
 * @property \App\Model\Table\PersonnagesTable $Personnages
 * @property \App\Model\Table\LangagesTable $Langages
 * @property \App\Model\Table\LiensTable $Liens
 * @property \App\Model\Table\TagsTable $Tags
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\SeriesTable $Series
 */
class AppController extends Controller implements ObjectControllerInterface
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        // Appel méthode parente.
        parent::initialize();

        // Chargement compsants pour gérer les requêtes, les alertes flash, l'url et l'authentification.
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Url');
        $this->loadComponent('Authentication.Authentication');

        // Chargements des tables pour la manipulation des données en BDD.
        $this->Auteurs = $this->fetchModel("Auteurs");
        $this->Fandoms = $this->fetchModel("Fandoms");
        $this->Fanfictions = $this->fetchModel("Fanfictions");
        $this->Langages = $this->fetchModel("Langages");
        $this->Liens = $this->fetchModel("FanfictionsLiens");
        $this->Personnages = $this->fetchModel("Personnages");
        $this->Relations = $this->fetchModel("Relations");
        $this->Tags = $this->fetchModel("Tags");
        $this->Users = $this->fetchModel("Users");
        $this->Series = $this->fetchModel("Series");

        // Envoi à tous templates des tableaux pour encoder les objets et actions lors des appels ajax.
        $this->Url->setArrays();
    }

    /**
     * Méthode qui écrit un tableau dans la session.
     * 
     * @param string $cle La clé générale des données dans la session (l'entité du controller utilisant cette méthode).
     * @param array $array Le tableau de données à enregistrer dans la session.
     */
    protected function writeSession(string $cle, array $array)
    {
        // Parcours du tableau de données à placer dans la session.
        foreach ($array as $key => $value) {

            if (is_array($value))

                // Si la donnée est un tableau, appel récursif.
                $this->writeSession($cle . ".$key", $value);
            else {

                // La donnée n'est pas un tableau, elle peut être placée dans la session.
                $this->request->getSession()->write($cle . ".$key", $value);
            }
        }
    }

    /**
     * Méthode générique pour vérifier l'existence de l'entité avant de la sauvegarder.
     *
     * @param array $data Les données du formulaire.
     * @return boolean Indication si l'entité existe ou non dans la base de données.
     */
    public function exist(array $data): bool
    {
        // Par défaut, retourne faux.
        return false;
    }

    /**
     * Méthode générique pour importer les options nécessaires au formulaire d'ajout ou d'édition d'une entité.
     *
     * @return void
     */
    public function importFormOptions(): void
    {
        // Par défaut, ne fait rien.
    }

    /**
     * Index method
     * Liste toutes les entités du controller.
     *
     * @return void
     */
    public function index()
    {
        // Récupération des paramètres depuis l'url.
        $params = $this->getRequest()->getParam("?") ?? [];

        // Envoi des paramètres au template.
        $this->set(compact("params"));
    }

    /**
     * View method
     * Affiche l'entité identifiée.
     *
     * @param string|null $id Entity id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        // Récupération du nom de l'entité manipulé
        $name = $this->name;

        // Récupération de l'entité avec tous ses associations.
        $entity = substr(strtolower($name), 0, -1);
        $$entity = $this->$name->getWithAssociations($id);

        // Envoi de l'entité au template.
        $this->set($entity, $$entity);
    }

    /**
     * Add method
     * Page de création de l'entité du controller.
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Récupération du nom de l'entité manipulé
        $name = $this->name;

        // Récupération de l'entité vide.
        $entity = substr(strtolower($name), 0, -1);
        $$entity = $this->$name->newEmptyEntity();

        // Si des données sont envoyées par le formulaire de la page.
        if ($this->request->is("post")) {

            // Si l'entité fournie n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Valorisation de l'entité avec les données du formulaire.
                $$entity = $this->$name->patchEntity($$entity, $this->request->getData());

                // Sauvegarde de l'entité
                if ($this->$name->save($$entity)) {

                    // Succès de la sauvegarde, avertissement de l'utilisateur.
                    $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' {0} a été sauvegardé' . (in_array($entity, ['relation', 'series']) ? "e" : "") . ' avec succès.';
                    $args = ($entity === "user" ? $$entity->username : $$entity->nom);
                    $this->Flash->success(__($avertissement, $args));
                    $this->log(__($avertissement, $args), LogLevel::INFO, $$entity);

                    // Redirection de l'utilisateur vers l'index des auteurs.
                    return $this->redirect(['action' => 'index']);
                }

                // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
                $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' n\'a pas pu être sauvegardé' . (in_array($entity, ['relation', 'series']) ? "e" : "") . '. Veuillez réessayer.';
                $args = ($entity === "user" ? $$entity->username : $$entity->nom);
                $this->Flash->error(__($avertissement, $args));
                $this->log(__($avertissement, $args), LogLevel::ERROR, $$entity);
            }

            // Avertissement de l'utilisateur connecté que l'auteur existe déjà
            $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' existe déjà.';
            $this->Flash->warning(__($avertissement));
            $this->log(__($avertissement));
        }

        // Envoi de l'entité vide au template.
        $this->set($entity, $$entity);

        // Import des options pour le formulaire.
        $this->importFormOptions();
    }

    /**
     * Edit method
     * Page d'édition de l'entité du controller.
     *
     * @param string|null $id Entity id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id)
    {
        // Récupération du nom de l'entité manipulé
        $name = $this->name;

        // Récupération de l'entité avec tous ses associations.
        $entity = substr(strtolower($name), 0, -1);
        $$entity = $this->$name->getWithAssociations($id);

        // Si des données sont envoyées par le formulaire de la page.
        if ($this->request->is(["post", "put"])) {

            // Valorisation de l'entité avec les données du formulaire.
            $$entity = $this->$name->patchEntity($$entity, $this->request->getData());

            // Sauvegarde de l'entité
            if ($this->$name->save($$entity)) {

                // Succès de la sauvegarde, avertissement de l'utilisateur.
                $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' {0} a été sauvegardé' . (in_array($entity, ['relation', 'series']) ? "e" : "") . ' avec succès.';
                $args = ($entity === "user" ? $$entity->username : $$entity->nom);
                $this->Flash->success(__($avertissement, $args));
                $this->log(__($avertissement, $args), LogLevel::INFO, $$entity);

                // Redirection de l'utilisateur vers l'index des auteurs.
                return $this->redirect(['action' => 'index']);
            }

            // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
            $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' n\'a pas pu être sauvegardé' . (in_array($entity, ['relation', 'series']) ? "e" : "") . '. Veuillez réessayer.';
            $args = ($entity === "user" ? $$entity->username : $$entity->nom);
            $this->Flash->error(__($avertissement, $args));
            $this->log(__($avertissement, $args), LogLevel::ERROR, $$entity);
        }

        // Envoi de l'entité à éditer au template.
        $this->set($entity, $$entity);

        // Import des options pour le formulaire.
        $this->importFormOptions();
    }

    /**
     * Delete method
     * Méthode de suppression de l'entité du controller.
     * Pas de rendu, juste manipulation de donnée dans la partie backend.
     *
     * @param string|null $id Entity id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        // Vérification que la page de suppression est accédée correctement.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération du nom de l'entité manipulé
        $name = $this->name;

        // Récupération de l'entité à supprimer.
        $entity = substr(strtolower($name), 0, -1);
        $$entity = $this->$name->get($id);

        // Valorisation des dates de modification et de suppression (suppression logique).
        $$entity = $this->$name->patchEntity($$entity, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        if ($this->$name->save($$entity)) {

            // Succès de la sauvegarde, avertissement de l'utilisateur.
            $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' {0} a été supprimé' . (in_array($entity, ['relation', 'series']) ? "e" : "") . ' avec succès.';
            $args = ($entity === "user" ? $$entity->username : $$entity->nom);
            $this->Flash->success(__($avertissement, $args));
            $this->log(__($avertissement, $args), LogLevel::INFO, $$entity);
        } else {

            // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
            $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' {0} n\'a pu être supprimé' . (in_array($entity, ['relation', 'series']) ? "e" : "") . '. Veuillez réessayer.';
            $args = ($entity === "user" ? $$entity->username : $$entity->nom);
            $this->Flash->error(__($avertissement, $args));
            $this->log(__($avertissement, $args), LogLevel::ERROR, $$entity);
        }

        // Redirection vers la page d'index des auteurs.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Restore method
     * Méthode de restoration de l'entité du controller.
     * Pas de rendu, juste manipulation de donnée dans la partie backend.
     *
     * @param string|null $id Entity id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function restore(?string $id = null)
    {
        // Verification de la méthode d'acces à la page avec redirection auto si la condition n'est pas satisfaite.
        $this->request->allowMethod(['post']);

        // Récupération du nom de l'entité manipulé
        $name = $this->name;

        // Récupération de l'entité supprimée.
        $entity = substr(strtolower($name), 0, -1);
        $$entity = $this->$name->get($id);

        // Valorisation de la série avec la date de suppression à vide et la date d'update.
        $$entity = $this->Series->patchEntity($$entity, [
            "suppression_date" => null,
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde de la série.
        if ($this->$name->save($$entity)) {

            // Succès de la sauvegarde, avertissement de l'utilisateur.
            $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' {0} a été restauré' . (in_array($entity, ['relation', 'series']) ? "e" : "") . ' avec succès.';
            $args = ($entity === "user" ? $$entity->username : $$entity->nom);
            $this->Flash->success(__($avertissement, $args));
            $this->log(__($avertissement, $args));
        } else {

            // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
            $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : (in_array($entity, ['relation', 'series']) ? "a" : "e")) . ' ' . $entity . ' {0} n\'a pu être restauré' . (in_array($entity, ['relation', 'series']) ? "e" : "") . '. Veuillez réessayer.';
            $args = ($entity === "user" ? $$entity->username : $$entity->nom);
            $this->Flash->error(__($avertissement, $args));
            $this->log(__($avertissement, $args), LogLevel::ERROR, $$entity);
        }

        // Redirection vers la page d'index des séries.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions de l'entité cliqué.
     * 
     * @param string|null $id Entity id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect(?string $id = null)
    {
        //Nettoyage à vide du champ fanfictions dans la session.
        $this->request->getSession()->write("fanfictions");

        // Récupération du nom de l'entité manipulé (cas particulier, un auteur par fanfiction).
        $name = in_array($this->name, ["Auteurs"]) ? strtolower(substr($this->name, 0, -1)) : strtolower($this->name);

        // Paramètres set avec les données du tag.
        $params = [];
        $params["filters"][$name] = $id;

        // Paramètres enregistrés dans la session.
        $this->writeSession("fanfictions", $params);

        // Redirection vers l'index des fanfictions.
        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }

    /**
     * Note method
     * Méthode pour évaluer et noter l'entité du controller.
     * Pas de rendu, juste manipulation de donnée dans la partie backend.
     *
     * @param string|null $id Entity id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function note(?string $id = null)
    {
        // Des données sont fournies par un formulaire.
        if ($this->request->is(["post", "put"])) {
            // Récupération du nom de l'entité manipulé
            $name = $this->name;

            // Récupération de l'entité supprimée.
            $entity = substr(strtolower($name), 0, -1);
            $$entity = $this->$name->get($id);

            // Valorisation de l'entité avec les données du formulaire.
            $$entity = $this->$name->patchEntity($$entity, $this->request->getData());

            // Sauvegarde de l'entité
            if ($this->$name->save($$entity)) {

                // Succès de la sauvegarde de l'entité, avertissement de l'utilisateur connecté.
                $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : ($entity === "series" ? "a" : "e")) . ' ' . $entity . ' {0} a été noté' . (in_array($entity, ['relation', 'series']) ? "e" : "") . ' et évalué' . (in_array($entity, ['relation', 'series']) ? "e" : "") . ' avec succès.';
                $args = ($entity === "user" ? $$entity->username : $$entity->nom);
                $this->Flash->success(__($avertissement, $args));
                $this->log(__($avertissement, $args));
            } else {
                // Erreur lors de la sauvegarde de l'entité, avertissement de l'utilisateur connecté.
                $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : ($entity === "series" ? "a" : "e")) . ' ' . $entity . ' {0} n\'a pu être noté' . (in_array($entity, ['relation', 'series']) ? "e" : "") . '. Veuillez réessayer.';
                $args = ($entity === "user" ? $$entity->username : $$entity->nom);
                $this->Flash->success(__($avertissement, $args));
                $this->log(__($avertissement, $args));
            }
        }

        // Redirection vers l'index des fanfictions.
        $this->redirect(["action" => "index"]);
    }

    /**
     * Denote method
     * Méthode pour enlever l'évaluation et la note de l'entité du controller.
     * Pas de rendu, juste manipulation de donnée dans la partie backend.
     *
     * @param string|null $id Entity id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function denote($id = null)
    {
        // Des données sont fournies par un formulaire.
        if ($this->request->is(["post", "put"])) {

            // Récupération du nom de l'entité manipulé
            $name = $this->name;

            // Récupération de l'entité supprimée.
            $entity = substr(strtolower($name), 0, -1);
            $$entity = $this->$name->get($id);

            // Valorisation de l'entité avec les données du formulaire + les données dévalorisées et la date d'update.
            $$entity = $this->$name->patchEntity($$entity, ["note" => null, "evaluation" => null, "update_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);

            // Sauvegarde de l'entité
            if ($this->$name->save($$entity)) {

                // Succès de la sauvegarde de l'entité, avertissement de l'utilisateur connecté.
                $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : ($entity === "series" ? "a" : "e")) . ' ' . $entity . ' {0} a été dénoté' . (in_array($entity, ['relation', 'series']) ? "e" : "") . ' et désévalué' . (in_array($entity, ['relation', 'series']) ? "e" : "") . ' avec succès.';
                $args = ($entity === "user" ? $$entity->username : $$entity->nom);
                $this->Flash->success(__($avertissement, $args));
                $this->log(__($avertissement, $args));
            } else {

                // Erreur lors de la sauvegarde de l'entité, avertissement de l'utilisateur connecté.
                $avertissement = 'L' . (in_array($entity, ['auteur', 'utilisateur']) ? "'" : ($entity === "series" ? "a" : "e")) . ' ' . $entity . ' {0} n\'a pu être dénoté' . (in_array($entity, ['relation', 'series']) ? "e" : "") . '. Veuillez réessayer.';
                $args = ($entity === "user" ? $$entity->username : $$entity->nom);
                $this->Flash->success(__($avertissement, $args));
                $this->log(__($avertissement, $args));
            }
        }

        // Redirection vers l'index des fanfictions.
        $this->redirect(["action" => "index"]);
    }

    /**
     * Méthode pour réinitialiser la liste des entités affichées par la session.
     * Majoritairement pour éviter d'avoir un filtre bloqué ad vitam par défaut en arrivant sur la page d'index.
     *
     * @return \Cake\Http\Response Redirects to entities index page.
     */
    public function reinitialize()
    {
        // Récupération du nom de l'entité manipulé
        $name = $this->name;

        // Les paramètres de séries sont réduits à null.
        $this->request->getSession()->write(strtolower($name), null);

        // Avertissement de l'utilisateur de la réinitialisation.
        $this->Flash->success("Réinitialisation des " . $name . " disponibles.");

        // Redirection vers la page d'index des séries.
        $this->redirect(["action" => "index"]);
    }
}
