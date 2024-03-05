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
        $this->viewBuilder()->setHelpers(['FlyPanel']);
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function initialize(): void
    {
        // Appel à l'initialisation du parent.
        parent::initialize();

        // Chargement du composant URL
        $this->Url = $this->loadComponent("url");

        // Récupération des paramètres.
        $params = $this->request->getSession()->check("fanfictions") ? $this->request->getSession()->read("fanfictions") : null;

        // Si les paramètres ne sont pas initialisés ou sous un mauvais format.
        if (is_null($params) || !is_array($params)) {

            // Initialisation du tableau des paramètres avec les fanfictions actives par défaut, ainsi que le tri par date de création en ordre descendants.
            $params = [];
            $params["inactive"] = !is_null($this->request->getParam("?")) ? $this->request->getParam("?")["inactive"] : '0';
            $params["sort"]["creation_date"] = "DESC";
            $params["panels"] = ["lien" => true, "fanfiction" => false];
        }

        if (is_array($params)) {
            // Si les paramètres sont initialisés sous le bon format.

            // Initialisation du paramètre avec les fanfictions actives par défaut.
            $params["inactive"] = (!is_null($this->request->getParam("?")) && array_key_exists("inactive", $this->request->getParam("?"))) ? $this->request->getParam("?")["inactive"] : '0';
        }

        // Paramètres de fanfictions écrits dans la session.
        $this->writeSession("fanfictions", $params);
    }


    /**
     * Méthode qui définit si une fanfiction existe avec nom et auteur.
     *
     * @param array $data Les données du formulaire.
     * @return bool Indication si la fanfiction existe.
     */
    public function exist(array $data): bool
    {
        return $this->Fanfictions->find()->where(["nom LIKE " => "%" . $data["nom"] . "%", "auteur" => intval($data["auteur"])])->count() > 0;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Récupération des paramètres de fanfictions depuis la session
        $params = $this->request->getSession()->read("fanfictions");

        // Si les filtres/la recherche/le tri, ont été modifiés
        if ($this->request->is(["post", "put"])) {

            // Récupération des données du formulaire.
            $data = $this->request->getData();

            // Les paramètres existants sont merge avec les nouveaux paramètres.
            $params = array_merge($params, $data);

            // Paramètres de fanfictions écrits dans la session.
            $this->writeSession("fanfictions", $params);
        }

        // Récupération des fanfictions correspondantes aux paramètres.
        $fanfictions = $this->Fanfictions->find("search", $params);

        // Décompte des fanfictions correspondantes aux paramètres.
        $fanfictionCount = $fanfictions->count();

        // Pagination des fanfictions correspondantes en pages de 25 fanfictions affichées.
        $fanfictions = $this->paginate($fanfictions, ["limit" => 25]);

        // Envoi des variables de formulaires au template.
        $this->setFormVariables();

        // Envoi des variables fanfictions, params & fanfictionCount au template.
        $this->set(compact('fanfictions', 'params', 'fanfictionCount'));
    }

    /**
     * Page d'ajout d'une fanfiction
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function add()
    {
        // Création d'une nouvelle fanfiction.
        $fanfiction = $this->Fanfictions->newEmptyEntity();

        // Des données sont envoyées depuis un formulaire.
        if ($this->request->is("post")) {

            // Si la fanfiction n'existe pas déjà
            if (!$this->exist($this->request->getData())) {

                // Transaction lancée (qui retourne si elle a été commit ou non).
                $committed = $this->Fanfictions->getConnection()->transactional(function () {

                    // Edition de la fanfiction avec les données du formulaire et son identifiant.
                    $fanfiction = $this->editFanfictionDataAssociation($this->request->getData());

                    // Sauvegarde de la fanfiction avec ses associations.
                    if ($this->Fanfictions->save($fanfiction, ["associated" => true])) {

                        // Succès de la sauvegarde, avertissement de l'utilisateur et de la méthode.
                        $this->Flash->success(__("Fanfiction \"{0}\" ajoutée avec succès.", $fanfiction->nom));
                        return true;
                    } else {

                        // Erreur lors de la sauvegarde, avertissement de l'utilisateur et de la méthode.
                        $this->Flash->error("Une erreur a été rencontrée lors de la sauvegarde de la fanfiction. Veuillez réessayer.");
                        return false;
                    }
                });

                // Si la transaction a été commit, redirection vers la page d'index des fanfictions.
                if ($committed)
                    $this->redirect(["action" => "index"]);
            } else

                // La fanfiction existe déjà, avertissement de l'utilisateur.
                $this->Flash->warning("Cette fanfiction existe déjà.");
        }

        // Récupération de l'état de deux panneaux, avec le panneau check de lien ouvert uniquement.
        $panels = $this->getRequest()->getSession()->read("fanfictions.panels", ["lien" => true, "fanfiction" => false]);

        // Si une url de fanfiction se trouve dans la session.
        if ($this->getRequest()->getSession()->check("fanfictions.url")) {

            // Création de cette url.
            $url = $this->Fanfictions->liens->newEmptyEntity();
            $url->lien = $this->getRequest()->getSession()->read("fanfictions.url", "");

            // Ajout dans la fanfiction en cours de création
            $fanfiction->liens = [];
            $fanfiction->liens[] = $url;
        }

        // Envoi de la fanfiction et des variables de formulaires vers le template.
        $this->set(compact("fanfiction", "panels"));
        $this->setFormVariables();
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
        // Récupération de la fanfiction avec toutes ses associations.
        $fanfiction = $this->Fanfictions->getWithAssociations($id);

        // Des données sont envoyées depuis un formulaire.
        if ($this->request->is(["post", "put"])) {

            // Transaction lancée (qui retourne si elle a été commit ou non).
            $committed = $this->Fanfictions->getConnection()->transactional(function () use ($id) {

                // Edition de la fanfiction avec les données du formulaire et son identifiant.
                $fanfiction = $this->editFanfictionDataAssociation($this->request->getData(), (int) $id);

                // Sauvegarde de la fanfiction avec ses associationsS.
                if ($this->Fanfictions->save($fanfiction, ["associated" => true])) {

                    // Succès de la sauvegarde, avertissement de l'utilisateur et de la méthode.
                    $this->Flash->success(__("Fanfiction \"{0}\" éditée avec succès.", $fanfiction->nom));
                    return true;
                } else {

                    // Erreur lors de la sauvegarde, avertissement de l'utilisateur et de la méthode.
                    $this->Flash->error("Une erreur a été rencontrée lors de la sauvegarde de la fanfiction. Veuillez réessayer.");
                    return false;
                }
            });

            // Si la transaction a été commit, redirection vers la page d'index des fanfictions.
            if ($committed)
                $this->redirect(["action" => "index"]);
        }

        // Envoi de la fanfiction et des variables de formulaires vers le template.
        $this->set(compact("fanfiction"));
        $this->setFormVariables();
    }

    /**
     * Méthode qui envoie toutes les données nécessaires au formulaire de fanfiction.
     *
     * @return void
     */
    private function setFormVariables()
    {
        // Récupération des auteurs, triés par nom, au format pour une liste.
        $auteurs = $this->Auteurs->find("list")->order(["nom"]);

        // Récupération des fandoms, triés par nom, au format pour une liste.
        $fandoms = $this->Fandoms->find("list")->order(["nom"]);

        // Récupération des langages, triés par nom, au format pour une liste.
        $langages = $this->Langages->find("list")->order(["nom"]);

        // Récupération des relations, triés par nom, au format pour une liste.
        $relations = $this->Relations->find("list")->order(["nom"]);

        // Récupération des personnages, triés par nom du fandom & nom des personnages, au format pour une liste groupée par fandom.
        $personnages = $this->Personnages->find("list", [
            "keyField" => "id",
            "valueField" => "nom",
            "groupField" => "fandom_obj.nom"
        ])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"]);

        // Récupération des tags, triés par nom, au format pour une liste.
        $tags = $this->Tags->find("list")->order(["nom"]);

        // Récupération des paramètres de l'appli (classement & note).
        $parametres = Configure::check("parametres") ? Configure::read("parametres") : [];

        // Récupération du paramétrage NSFW de l'utilisateur connecté.
        $nsfw = $this->request->getSession()->read("user.nsfw", false);

        if (!$nsfw) {
            // Si l'utilisateur connecté n'a pas le droit au NSFW, retirage des classement au dessus de T / 13.
            $parametres["Classement"] = array_slice($parametres["Classement"], 0, 3, true);
        }

        // Envoi des tableaux de correspondance dans le template.
        $this->Url->setArrays();

        // Envoi de toutes les données récupérées, à part le paramètre NSFW, vers le template.
        $this->set(compact("auteurs", "fandoms", "langages", "relations", "personnages", "tags", "parametres"));
    }

    /**
     * Méthode qui crée/édite une fanfiction en fonction des données envoyées par le formulaire et l'identifiant de la fanfiction à modifier.
     * 
     * @var array $data Données envoyées par le formulaire de fanfiction.
     * @var int $idFanfiction L'identifiant de la fanfiction à modifier.
     * @return Fanfiction La fanfiction crée/modifiée.
     */
    private function editFanfictionDataAssociation(array $data, int $idFanfiction = 0)
    {

        if ($idFanfiction === 0)
            // Si identifiant à 0, c'est une nouvelle fanfiction à créer.
            $fanfiction = $this->Fanfictions->newEmptyEntity();
        else
            // Si identifiant différente de 0, il faut charger la fanfiction avec toutes ses données.
            $fanfiction = $this->Fanfictions->getWithAssociations($idFanfiction);

        // Valorisation des données directes.
        $fanfiction->nom = $data["nom"];
        $fanfiction->classement = $data["classement"];
        $fanfiction->description = $data["description"];
        $fanfiction->auteur = $data["auteur"];
        $fanfiction->langage = $data["langage"];


        /**
         * PARTIE LIENS
         */
        if (!empty($fanfiction->liens)) {
            // Si un tableau des tags existent déjà pour la fanfiction
            // Parcours des données de la fanfiction
            // SI donnée pas dans les données du formulaire, retirer la donnée
            foreach ($fanfiction->liens as $cle => $lien)
                if (array_search($lien->id, $data["liens"]) === false)
                    unset($fanfiction->liens[$cle]);

            // Fanfiction est informé de la modification de ses liens
            $fanfiction->setDirty("liens");
        } else {
            // Pas de tableau des liens existant, il est créé.
            $fanfiction->liens = [];

            if (array_key_exists("liens", $data) && is_array($data["liens"])) {
                foreach ($data["liens"] as $link) { // Parcours des données du formulaire pour les liens
                    $lien = $this->Liens->newEmptyEntity();

                    // Remplissage du nouveau lien avec les données du formulaire et les date/heure de l'instant de création
                    $lien->lien = trim($link);
                    $lien->creation_date = FrozenTime::now("Europe/Paris");
                    $lien->update_date = FrozenTime::now("Europe/Paris");

                    // Ajout des liens dans les données du formulaire.
                    $fanfiction->liens[] = $lien;
                }
            }
        }

        /**
         * PARTIE FANDOMS
         */
        if (!empty($fanfiction->fandoms)) {
            //Parcours des données de la fanfiction
            // SI donnée pas dans les données du formulaire, retirer la donnée
            foreach ($fanfiction->fandoms as $cle => $fandom)
                if (array_search($fandom->id, $data["fandoms"]) === false)
                    unset($fanfiction->fandoms[$cle]);

            //Parcours des données du formulaire
            // Si donnée pas dans la fanfiction, la rajouter.
            foreach ($data["fandoms"] as $idFandom)
                if (array_search($idFandom, array_column($fanfiction->fandoms, "id")) === false)
                    array_push($fanfiction->fandoms, $this->Fandoms->get($idFandom));

            // Fanfiction est informé de la modification de ses fandoms
            $fanfiction->setDirty("fandoms");
        } else {
            // Pas de tableau des tags existant, il est créé.
            $fanfiction->fandoms = [];
            if (array_key_exists("fandoms", $data) && is_array($data["fandoms"])  && !empty($data["fandoms"][0])) {
                foreach ($data["fandoms"] as $id) // Parcours des données du formulaire pour les fandoms
                    $fanfiction->fandoms[] = $this->Fandoms->get($id); // Ajout des fandoms identifiés dans les données du formulaire.
            }
        }

        /**
         * PARTIE RELATIONS
         */
        if (!empty($fanfiction->relations)) {
            //Parcours des données de la fanfiction
            // SI donnée pas dans les données du formulaire, retirer la donnée
            foreach ($fanfiction->relations as $cle => $relation)
                if (array_search($relation->id, $data["relations"]) === false)
                    unset($fanfiction->relations[$cle]);

            //Parcours des données du formulaire
            // Si donnée pas dans la fanfiction, la rajouter.
            foreach ($data["relations"] as $idRelation)
                if (array_search($idRelation, array_column($fanfiction->relations, "id")) === false)
                    array_push($fanfiction->relations, $this->Relations->get($idRelation));

            // Fanfiction est informé de la modification de ses relations
            $fanfiction->setDirty("relations");
        } else {
            // Pas de tableau des relations existant, il est créé.
            $fanfiction->relations = [];
            if (array_key_exists("relations", $data) && is_array($data["relations"]) && !empty($data["relations"][0])) {
                foreach ($data["relations"] as $id) // Parcours des données du formulaire pour les relations
                    $fanfiction->relations[] = $this->Relations->get($id); // Ajout des relations identifiées dans les données du formulaire.
            }
        }

        /**
         * PARTIE PERSONNAGES
         */
        if (!empty($fanfiction->personnages)) {
            //Parcours des données de la fanfiction
            // SI donnée pas dans les données du formulaire, retirer la donné
            foreach ($fanfiction->personnages as $cle => $personnage)
                if (array_search($personnage->id, $data["personnages"]) === false)
                    unset($fanfiction->personnages[$cle]);

            //Parcours des données du formulaire
            // Si donnée pas dans la fanfiction, la rajouter.
            foreach ($data["personnages"] as $idPersonnage)
                if (array_search($idPersonnage, array_column($fanfiction->personnages, "id")) === false)
                    array_push($fanfiction->personnages, $this->Personnages->get($idPersonnage));

            // Fanfiction est informé de la modification de ses personnages
            $fanfiction->setDirty("personnages");
        } else {
            // Pas de tableau des personnages existant, il est créé.
            $fanfiction->personnages = [];

            if (array_key_exists("personnages", $data) && is_array($data["personnages"])  && !empty($data["personnages"][0])) {
                foreach ($data["personnages"] as $id) // Parcours des données du formulaire pour les personnages
                    $fanfiction->personnages[] = $this->Personnages->get($id); // Ajout des personnages identifiés dans les données du formulaire.
            }
        }

        /**
         * PARTIE TAGS
         */
        if (!empty($fanfiction->tags)) { // Si un tableau des tags existent déjà pour la fanfiction

            //Parcours des données de la fanfiction
            // SI donnée pas dans les données du formulaire, retirer la donnée
            foreach ($fanfiction->tags as $cle => $tag)
                if (array_search($tag->id, $data["tags"]) === false)
                    unset($fanfiction->tags[$cle]);

            //Parcours des données du formulaire
            // Si donnée pas dans la fanfiction, la rajouter.
            foreach ($data["tags"] as $idTag)
                if (array_search($idTag, array_column($fanfiction->tags, "id")) === false)
                    array_push($fanfiction->tags, $this->Tags->get($idTag));

            // Fanfiction est informé de la modification de ses tags
            $fanfiction->setDirty("tags");
        } else {
            // Pas de tableau des tags existant, il est créé.
            $fanfiction->tags = [];

            if (array_key_exists("tags", $data) && is_array($data["tags"])  && !empty($data["tags"][0])) {
                foreach ($data["tags"] as $id) // Parcours des données du formulaire pour les tags
                    $fanfiction->tags[] = $this->Tags->get($id); // Ajout des tags identifiés dans les données du formulaire.
            }
        }
        return $fanfiction;
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
        parent::denote($id);
    }

    /**
     * Méthode pour réinitialiser la liste des fanfictions.
     *
     * @return \Cake\Http\Response Redirects to fanfictions index page.
     */
    public function reinitialize()
    {
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
