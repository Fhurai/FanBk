<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use App\Controller\AppController;

/**
 * Series Controller
 *
 * @property \App\Model\Table\SeriesTable $Series
 */
class SeriesController extends AppController implements ObjectControllerInterface
{
    /**
     * Méthode qui va vérifier que l'entité en cours de création/édition n'existe pas déjà.
     *
     * @param array $data Les données du formulaire.
     * @return boolean Indication que le fandom existe déjà ou non.
     */
    public function exist(array $data): bool
    {
        return $this->Series->find()->where(["nom LIKE" => "%" . $data["nom"] . "%"])->count() > 0;
    }

    public function initialize(): void
    {
        // Appel à l'initialisation du parent.
        parent::initialize();

        // Récupération des paramètres.
        $params = $this->request->getSession()->check("series") ? $this->request->getSession()->read("series") : null;

        // Si les paramètres ne sont pas initialisés ou sous un mauvais format.
        if (is_null($params) || !is_array($params)) {

            // Initialisation du tableau des paramètres avec les séries actives par défaut, ainsi que le tri par date de création en ordre descendants.
            $params = [];
            $params["user"] = [];
            $params["user"]["nsfw"] = $this->request->getSession()->read("user.nsfw", false);
            $params["inactive"] = !is_null($this->request->getParam("?")) ? $this->request->getParam("?")["inactive"] : '0';
            $params["sort"]["creation_date"] = "DESC";
        }

        if (is_array($params)) {
            // Si les paramètres sont initialisés sous le bon format.

            // Initialisation du paramètre nsfw et du paramètre avec les séries actives par défaut.
            $params["user"]["nsfw"] = $this->request->getSession()->read("user.nsfw", false);
            $params["inactive"] = (!is_null($this->request->getParam("?")) && array_key_exists("inactive", $this->request->getParam("?"))) ? $this->request->getParam("?")["inactive"] : '0';
        }

        // Paramètres de séries écrits dans la session.
        $this->writeSession("series", $params);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Récupération des paramètres de fanfictions depuis la session
        $params = $this->request->getSession()->read("series");

        // Si les filtres/la recherche/le tri, ont été modifiés
        if ($this->request->is(["post", "put"])) {

            // Récupération des données du formulaire.
            $data = $this->request->getData();

            // Les paramètres existants sont merge avec les nouveaux paramètres.
            $params = array_merge($params, $data);

            // Paramètres de fanfictions écrits dans la session.
            $this->writeSession("series", $params);
        }

        // Récupération des fanfictions correspondantes aux paramètres.
        $series = $this->Series->find("search", $params);

        // Décompte des fanfictions correspondantes aux paramètres.
        $seriesCount = $series->count();

        // Pagination des fanfictions correspondantes en pages de 25 fanfictions affichées.
        $series = $this->paginate($series, ["limit" => 25]);

        // Le finder search ne chargeant pas forcément toutes les associations de la série, on force le chargement.
        $series = array_map(function ($serie) {
            return $this->Series->loadInto($serie, [
                "fanfictions" => [
                    'auteurs',
                    'langages',
                    'fandoms',
                    'personnages',
                    'relations',
                    'tags',
                    'liens',
                ]
            ]);
        }, $series->toArray());

        // Envoi des données complémentaires pour les formulaires filtres.
        $this->setFormVariables();

        // Envoie des données complémentaires pour le descriptif des séries.
        $this->getAssociationsLists();

        // Envoi des données au template.
        $this->set(compact('series', 'params', 'seriesCount'));
    }

    /**
     * Envoi des données complémentaires du descriptif
     *
     * @return void
     */
    private function getAssociationsLists()
    {
        $auteurs = $this->Auteurs->find("list")->toArray();
        $fandoms = $this->Fandoms->find("list")->toArray();
        $langages = $this->Langages->find("list")->toArray();
        $relations = $this->Relations->find("list")->toArray();
        $personnages = $this->Personnages->find("list")->toArray();
        $tags = $this->Tags->find("list")->toArray();

        $this->set(compact("auteurs", "fandoms", "langages", "relations", "personnages", "tags"));
    }

    /**
     * Page d'édition d'une série
     * 
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // Récupération de la série avec ses associations.
        $series = $this->Series->getWithAssociations($id);

        // Récupération de l'indication si l'utilisateur connecté peut voir du contenu NSFW.
        $nsfw = $this->request->getSession()->read("user.nsfw");

        // Si l'utilisateur n'a pas le droit au contenu NSFW et que le classement de la série est NSFW
        if (!$nsfw && $series->classement > 3)

            // Redirection de l'utilisateur.
            $this->redirect(["action" => "index"]);

        // Récupération des paramètres (Classement / Note)
        $parametres = Configure::check("parametres") ? Configure::read("parametres") : [];

        // Envoi des données complémentaires au template.
        $this->getAssociationsLists();

        // Envoi des données au template.
        $this->set(compact("series", "parametres"));
    }

    /**
     * Page d'ajout d'une série
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function add()
    {
        // Création d'une nouvelle série.
        $series = $this->Series->newEmptyEntity();

        // Des données sont envoyées depuis un formulaire.
        if ($this->request->is("post")) {

            // Si la série n'existe pas déjà
            if (!$this->exist($this->request->getData())) {

                // Transaction lancée (qui retourne si elle a été commit ou non).
                $commited = $this->Series->getConnection()->transactional(function () {

                    // Edition de la série avec les données du formulaire et son identifiant.
                    $series = $this->editSeriesDataAssociation($this->request->getData());

                    // Sauvegarde de la série avec ses associations.
                    if ($this->Series->save($series, ["associated" => true])) {

                        // Succès de la sauvegarde, avertissement de l'utilisateur et de la méthode.
                        $this->Flash->success("Série ajoutée avec succès.");
                        return true;
                    } else {

                        // Erreur lors de la sauvegarde, avertissement de l'utilisateur et de la méthode.
                        $this->Flash->error("Une erreur a été rencontrée lors de la sauvegarde de la série. Veuillez réessayer.");
                        return false;
                    }
                });

                // Si la transaction a été commit, redirection vers la page d'index des fanfictions.
                if ($commited)
                    $this->redirect(["action" => "index"]);
            } else

                // La série existe déjà, avertissement de l'utilisateur.
                $this->Flash->warning("Cette série existe déjà.");
        }

        // Envoi de la série et des variables de formulaires vers le template.
        $this->set(compact("series"));
        $this->setFormVariables();
    }

    /**
     * Page d'édition d'une série
     * 
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // Récupération de la fanfiction avec toutes ses associations.
        $series = $this->Series->getWithAssociations($id);

        // Des données sont envoyées depuis un formulaire.
        if ($this->request->is("post")) {

            // Si la série n'existe pas déjà
            if (!$this->exist($this->request->getData())) {

                // Transaction lancée (qui retourne si elle a été commit ou non).
                $commited = $this->Series->getConnection()->transactional(function () use ($id) {

                    // Edition de la série avec les données du formulaire et son identifiant.
                    $series = $this->editSeriesDataAssociation($this->request->getData(), (int) $id);

                    // Sauvegarde de la série avec ses associations.
                    if ($this->Series->save($series, ["associated" => true])) {

                        // Succès de la sauvegarde, avertissement de l'utilisateur et de la méthode.
                        $this->Flash->success("Série éditée avec succès.");
                        return true;
                    } else {

                        // Erreur lors de la sauvegarde, avertissement de l'utilisateur et de la méthode.
                        $this->Flash->error("Une erreur a été rencontrée lors de la sauvegarde de la série. Veuillez réessayer.");
                        return false;
                    }
                });

                // Si la transaction a été commit, redirection vers la page d'index des fanfictions.
                if ($commited)
                    $this->redirect(["action" => "index"]);
            } else

                // La série existe déjà, avertissement de l'utilisateur.
                $this->Flash->warning("Cette série existe déjà.");
        }

        // Envoi de la série et des variables de formulaires vers le template.
        $this->set(compact("series"));
        $this->setFormVariables();
    }

    /**
     * Méthode qui envoie toutes les données nécessaires au formulaire de series.
     * TODO: Refaire avec l'introduction de la Flytable.
     */
    private function setFormVariables()
    {
        $fanfictions = $this->Fanfictions->find("list", [
            "keyField" => "id",
            "valueField" => "nom",
            "groupField" => "auteur_obj.nom"
        ])
            ->contain(["auteurs"])
            ->order(["auteurs.nom" => "ASC", "fanfictions.nom" => "ASC"]);

        $auteurs = $this->Auteurs->find("list")->order(["nom"])->toArray();
        $fandoms = $this->Fandoms->find("list")->order(["nom"])->toArray();
        $langages = $this->Langages->find("list")->order(["nom"])->toArray();
        $relations = $this->Relations->find("list")->order(["nom"]);
        $personnages = $this->Personnages->find("all")->order(["nom"]);
        $tags = $this->Tags->find("all")->order(["nom"]);

        // Options pour lier les personnages à leurs fandoms.
        $optionsPersonnages = $personnages->all()->map(function ($personnage, $key) {
            return [
                "value" => $personnage->id,
                "text" => $personnage->nom,
                "data-fandom" => $personnage->fandom
            ];
        });

        // Options pour lier les relations à leurs fandoms.
        $personnagesParNom = [];
        foreach ($personnages as $personnage) {
            $personnagesParNom[$personnage->nom] = $personnage;
        }
        $optionsRelations = $relations->map(function ($relation, $key) use ($personnagesParNom) {
            $personnagesArray = explode("/", $relation);
            $fandoms = array_unique(array_map(function ($personnage) use ($personnagesParNom) {
                return $personnagesParNom[trim($personnage)]->fandom;
            }, $personnagesArray));
            return [
                "value" => $key,
                "text" => $relation,
                "data-fandoms" => json_encode($fandoms)
            ];
        });

        //Options pour afficher la description des tags
        $optionsTags = $tags->all()->map(function ($tag, $key) {
            return [
                "value" => $tag->id,
                "text" => $tag->nom,
                "title" => $tag->nom . " : " . $tag->description
            ];
        });


        $parametres = Configure::check("parametres") ? Configure::read("parametres") : [];

        $nsfw = $this->request->getSession()->read("user.nsfw", false);

        if (!$nsfw) {
            $parametres["Classement"] = array_slice($parametres["Classement"], 0, 3, true);
        }

        $this->set(compact("fanfictions", "auteurs", "fandoms", "langages", "optionsRelations", "optionsPersonnages", "optionsTags", "parametres"));
    }

    /**
     * Méthode qui crée/édite une séries en fonction des données envoyées par le formulaire et l'identifiant de la série à modifier.
     * 
     * @var array $data Données envoyées par le formulaire de série.
     * @var int $idFanfiction L'identifiant de la série à modifier.
     * @return Series La série crée/modifiée.
     */
    private function editSeriesDataAssociation(array $data, int $idSeries = 0)
    {
        if ($idSeries === 0) $series = $this->Series->newEmptyEntity();
        else $series = $this->Series->getWithAssociations($idSeries);

        /**
         * PARTIE NOM / DESCRIPTION
         */
        $series->nom = trim($data["nom"]);
        $series->description = trim($data["description"]);

        /**
         * PARTIE FANFICTIONS
         */
        if (!empty($series->fanfictions)) {
            //Parcours des données de la fanfiction
            // SI donnée pas dans les données du formulaire, retirer la donnée
            foreach ($series->fanfictions as $cle => $fanfiction)
                if (array_search($fanfiction->id, $data["fanfictions"]) === false)
                    unset($series->fanfictions[$cle]);

            //Parcours des données du formulaire
            // Si donnée pas dans la fanfiction, la rajouter.
            foreach ($data["fanfictions"] as $idFanfiction)
                if (array_search($idFanfiction, array_column($series->fanfictions, "id")) === false) {
                    if (!empty($idFanfiction)) array_push($series->fanfictions, $this->Fanfictions->getWithAssociations($idFanfiction));
                }

            // Series est informé de la modification de ses fanfictions
            $series->setDirty("fanfictions");
        } else {
            // Pas de tableau des tags existant, il est créé.
            $series->fanfictions = [];
            if (array_key_exists("fanfictions", $data) && is_array($data["fanfictions"])) {
                foreach ($data["fanfictions"] as $key => $id) {
                    if (!empty($id)) {
                        // Parcours des données du formulaire pour les fanfictions
                        // Ajout des fanfictions identifiés dans les données du formulaire.
                        $fanfiction = $this->Fanfictions->getWithAssociations($id);
                        $fanfiction->_joinData = new Entity([
                            "ordre" => $key
                        ]);
                        $series->fanfictions[] = $fanfiction;
                    }
                }
            }
        }

        return $series;
    }

    /**
     * Delete method
     *
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        // Verification de la méthode d'acces à la page avec redirection auto si les conditions ne sont pas satisfaites.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération de la série à partir de son identifiant.
        $series = $this->Series->get($id);

        // Valorisation de la série avec la date de suppression et la date d'update.
        $series = $this->Series->patchEntity($series, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde de la série.
        if ($this->Series->save($series))

            // Succès de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->success(__('La série {0} a été supprimée avec succès.', $series->nom));
        else

            // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->error(__('La série {0} n\'a pu être supprimée. Veuillez réessayer.', $series->nom));

        // Redirection vers la page d'index des séries.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Restore method
     *
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function restore($id = null)
    {
        // Verification de la méthode d'acces à la page avec redirection auto si la condition n'est pas satisfaite.
        $this->request->allowMethod(['post']);

        // Récupération de la série à partir de son identifiant.
        $series = $this->Series->get($id);

        // Valorisation de la série avec la date de suppression à vide et la date d'update.
        $series = $this->Series->patchEntity($series, [
            "suppression_date" => null,
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde de la série.
        if ($this->Series->save($series)) {

            // Succès de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->success(__('La série {0} a été restaurée avec succès.', $series->nom));
        } else {

            // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->error(__('La série {0} n\'a pu être restaurée. Veuillez réessayer.', $series->nom));
        }

        // Redirection vers la page d'index des séries.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Note method
     *
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function note($id = null)
    {
        // Des données sont fournies par un formulaire.
        if ($this->request->is(["post", "put"])) {

            // Récupération de la série avec ses associations.
            $series = $this->Series->getWithAssociations($id);

            // Valorisation de la série avec les données du formulaire.
            $series = $this->Series->patchEntity($series, $this->request->getData());

            // Sauvegarde de la série
            if ($this->Series->save($series))

                // Succès de la sauvegarde de la série, avertissement de l'utilisateur connecté.
                $this->Flash->success(__("La série {0} a été noté et évaluée avec succès.", $series->nom));
            else

                // Erreur lors de la sauvegarde de la fanfiction, avertissement de l'utilisateur connecté.
                $this->Flash->error(__("La série {0} n'a pu être notée. Veuillez réessayer.", $series->nom));

            // Redirection vers l'index des fanfictions.
            $this->redirect(["action" => "index"]);
        }
    }

    /**
     * Denote method
     *
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function denote($id = null)
    {
        // Des données sont fournies par un formulaire.
        if ($this->request->is(["post", "put"])) {

            // Récupération de la série avec ses associations.
            $series = $this->Series->getWithAssociations($id);

            // Valorisation de la série avec les données du formulaire + les données dévalorisées et la date d'update.
            $series = $this->Series->patchEntity($series, ["note" => null, "evaluation" => null, "update_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);

            // Sauvegarde de la série
            if ($this->Series->save($series))

                // Succès de la sauvegarde de la série, avertissement de l'utilisateur connecté.
                $this->Flash->success(__("La série {0} a été noté et évaluée avec succès.", $series->nom));
            else

                // Erreur lors de la sauvegarde de la série, avertissement de l'utilisateur connecté.
                $this->Flash->error(__("La série {0} n'a pu être notée. Veuillez réessayer.", $series->nom));

            // Redirection vers l'index des séries.
            $this->redirect(["action" => "index"]);
        }
    }

    /**
     * Méthode pour réinitialiser la liste des séries.
     */
    public function reinitialize()
    {
        // Les paramètres de séries sont réduits à null.
        $this->request->getSession()->write("series", null);

        // Avertissement de l'utilisateur de la réinitialisation.
        $this->Flash->success("Réinitialisation des séries disponibles.");

        // Redirection vers la page d'index des séries.
        $this->redirect(["action" => "index"]);
    }
}
