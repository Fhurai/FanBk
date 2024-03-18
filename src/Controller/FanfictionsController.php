<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;

/**
 * Fanfictions Controller
 *
 * @property \App\Model\Table\FanfictionsTable $Fanfictions
 * @property \App\Model\Table\FandomsTable $Fandoms
 * @property \App\Model\Table\AuteursTable $Auteurs
 * @property \App\Model\Table\RelationsTable $Relations
 * @property \App\Model\Table\PersonnageTable $Personnages
 * 
 * @property \App\Controller\Component\UrlComponent $Url
 */
class FanfictionsController extends AppController implements ObjectControllerInterface
{
    /**
     * @inheritDoc
     * 
     * @return void
     */
    public function beforeRender(EventInterface $event)
    {
        // Appel méthode parente.
        parent::beforeRender($event);

        // Injection d'un nouvel helper FlyPanel dans la vue.
        $this->viewBuilder()->addHelper('FlyPanel');
    }


    /**
     * Méthode qui définit si une fanfiction existe avec nom et auteur.
     *
     * @param array $data Les données du formulaire.
     * @return bool Indication si la fanfiction existe.
     */
    public function exist(array $data): bool
    {
        // Retourne si des fanfictions avec le même nom et le même auteur existent.
        // (Des histoires avec le même nom peuvent exister mais pas par le même auteur).
        return $this->Fanfictions->find()->where(["nom LIKE " => "%" . $data["nom"] . "%", "auteur" => intval($data["auteur"])])->count() > 0;
    }

    /**
     * Méthode d'importation des options nécessaires au formulaire de fanfictions.
     *
     * @return void
     */
    public function importFormOptions(): void
    {
        // Récupération de toutes les entités sous forme de listes.
        // Exception des personnages qui sont regroupées par fandom pour faciliter la recherche.
        $auteurs = $this->Auteurs->find("list")->order(["nom" => "ASC", "update_date" => "ASC"])->all()->toArray();
        $fandoms = $this->Fandoms->find("list")->order(["nom" => "ASC", "update_date" => "ASC"])->all()->toArray();
        $langages = $this->Langages->find("list")->order(["nom" => "ASC", "update_date" => "ASC"])->all()->toArray();
        $relations = $this->Relations->find("list")->order(["nom" => "ASC", "update_date" => "ASC"])->all()->toArray();
        $personnages = $this->Personnages->find('list', ["keyField" => "id", "valueField" => "nom", "groupField" => "fandom_obj.nom"])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"]);
        $tags = $this->Tags->find("list")->order(["nom" => "ASC", "update_date" => "ASC"])->all()->toArray();

        // Récupération des paramètres de l'appli (classement & note).
        $parametres = Configure::check("parametres") ? Configure::read("parametres") : [];

        // Envoi de toutes les listes au template.
        $this->set(compact("auteurs", "fandoms", "langages", "relations", "personnages", "tags", "parametres"));

        // Envoi des tableaux de correspondance dans le template.
        $this->Url->setArrays();
    }

    /**
     * Méthode qui initialiser le controller.
     *
     * @return void
     */
    public function initialize(): void
    {
        //Appel méthode parente.
        parent::initialize();

        // Récupération des paramètres de la session pour les fanfictions.
        $params = $this->request->getSession()->read("fanfictions");

        if (empty($params)) { // Si les paramètres de la session sont vides.

            if ($this->request->getParam("action") === "add")
                // Si la page appelée est la page d'ajout, paramètres des panneaux set.
                $params["panels"] = ["lien" => true, "fanfiction" => false];
        }

        // Envoi des paramètres au template.
        $this->set(compact("params"));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Appel méthode parente.
        parent::index();
    }

    /**
     * Page d'ajout d'une fanfiction
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function add()
    {
        // Appel méthode parente.
        parent::add();
    }

    /**
     * Page d'édition d'une fanfiction
     * 
     * @param string|null $id Fanfiction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // Appel méthode parente.
        parent::edit($id);
    }

    /**
     * Delete method
     *
     * @param string|null $id Fanfiction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        // Appel méthode parente.
        parent::delete($id);
    }

    /**
     * Restore method
     *
     * @param string|null $id Fanfiction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function restore($id = null)
    {
        // Appel méthode parente.
        parent::restore($id);
    }

    /**
     * Note method
     *
     * @param string|null $id Fanfiction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function note($id = null)
    {
        // Appel méthode parente.
        parent::note($id);
    }

    /**
     * Denote method
     *
     * @param string|null $id Fanfiction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function denote($id = null)
    {
        // Appel méthode parente.
        parent::denote($id);
    }

    /**
     * Méthode pour réinitialiser la liste des fanfictions.
     *
     * @return \Cake\Http\Response Redirects to fanfictions index page.
     */
    public function reinitialize()
    {
        // Appel méthode parente.
        parent::reinitialize();
    }

    /**
     * Méthode pour check si un lien de fanfiction est déjà en bdd ou non
     * @return \Cake\Http\Response Redirects to existing fanfiction or add page.
     */
    public function checkLien()
    {
        // Des données sont bien fournies depuis le formulaire.
        if ($this->request->is(["post", "put"])) {

            // Récupération des données fournies par le formulaire.
            $data = $this->request->getData();

            // Récupération du lien à partir de la chaîne de caractères correspondante.
            $lien = $this->Liens->find("all")->contain(["fanfictions"])->where(["FanfictionsLiens.lien LIKE" => "%" . $data["lien"] . "%"])->first();

            // Le lien existe.
            if (!is_null($lien)) {

                // Mise à vide de la session pour les fanfictions.
                $this->request->getSession()->write("fanfictions");

                // Ajout de l'auteur dans la session pour la recherche fanfictions.
                $params = [];
                $params["search"]["fields"]["nom"] = trim($lien->fanfiction_obj->nom);
                $params["search"]["not"]["nom"] = true;
                $params["search"]["operator"]["nom"] = "AND";
                $this->writeSession("fanfictions", $params);

                // Redirection vers la page d'index des fanfictions.
                $this->redirect(["action" => "index"]);
            } else {
                // Mise à vide de la session pour les fanfictions.
                $this->request->getSession()->write("fanfictions");

                // Ajout du de l'état des panneaux et du lien dans la session.
                $params = [];
                $params["panels"] = ["lien" => false, "fanfiction" => true];
                $params["url"] = $data["lien"];
                $this->writeSession("fanfictions", $params);

                // Aucun lien trouvé
                $this->redirect(["action" => "add"]);
            }
        } else

            // Aucune donné de formulaire, c'est un accès direct à la page.
            // Avertissement de l'utilisateur que des données sont manquantes.
            $this->Flash->warning(__("Aucun lien fourni pour le check."));


        // Redirection vers l'index des fanfictions.
        $this->redirect(["action" => "index"]);
    }
}
