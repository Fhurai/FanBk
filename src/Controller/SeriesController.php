<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use App\Controller\AppController;
use App\Model\Table\SeriesTable;

/**
 * Series Controller
 *
 * @property \App\Model\Table\SeriesTable $Series
 */
class SeriesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $params = $this->request->getSession()->check("series") ? $this->request->getSession()->read("series") : null;

        if (is_null($params) || !is_array($params)) {
            $params = [];
            $params["user"] = [];
            $params["user"]["nsfw"] = $this->request->getSession()->read("user.nsfw", false);
            $params["inactive"] = !is_null($this->request->getParam("?")) ? $this->request->getParam("?")["inactive"] : '0';
            $params["sort"]["creation_date"] = "DESC";
        } else {
            $params["user"]["nsfw"] = $this->request->getSession()->read("user.nsfw", false);
            $params["inactive"] = (!is_null($this->request->getParam("?")) && array_key_exists("inactive", $this->request->getParam("?"))) ? $this->request->getParam("?")["inactive"] : '0';
        }

        $this->writeSession("series", $params);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        $params = $this->request->getSession()->read("series");

        if ($this->request->is(["post", "put"])) {
            $data = $this->request->getData();
            $params = array_merge($params, $data);
            $this->writeSession("series", $params);
        }

        $series = $this->Series->find("search", $params);
        $seriesCount = $series->count();
        $series = $this->paginate($series, ["limit" => 25]);

        $seriesTemp = [];
        foreach ($series as $serie) {
            $seriesTemp[] = $this->Series->loadInto($serie, [
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
        }

        $series = $seriesTemp;

        $this->setFormVariables();
        $this->getAssociationsLists();
        $this->set(compact('series', 'params', 'seriesCount'));
    }

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
        $series = $this->Series->getWithAssociations($id);

        $nsfw = $this->request->getSession()->read("user.nsfw");

        if (!$nsfw && $series->classement > 3)
            $this->redirect(["action" => "index"]);

        $parametres = Configure::check("parametres") ? Configure::read("parametres") : [];

        $this->getAssociationsLists();

        $this->set(compact("series", "parametres"));
    }

    /**
     * Page d'ajout d'une série
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function add()
    {
        $series = $this->Series->newEmptyEntity();

        if ($this->request->is("post")) {
            $series = $this->Series->getConnection()->transactional(function () {
                return $this->editSeriesDataAssociation($this->request->getData());
            });
            if ($this->Series->save($series, ["associated" => true])) {
                $this->Flash->success("Série ajoutée avec succès.");
                $this->redirect(["action" => "index"]);
            } else
                $this->Flash->error("Une erreur a été rencontrée lors de la sauvegarde de la série. Veuillez réessayer.");
        }

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
        $series = $this->Series->getWithAssociations($id);

        if ($this->request->is(["post", "put"])) {
            $series = $this->Series->getConnection()->transactional(function () use ($id) {
                return $this->editSeriesDataAssociation($this->request->getData(), (int) $id);
            });
            if ($this->Series->save($series, ["associated" => true])) {
                $this->Flash->success("Série ajoutée avec succès.");
                $this->redirect(["action" => "index"]);
            } else
                $this->Flash->error("Une erreur a été rencontrée lors de la sauvegarde de la série. Veuillez réessayer.");
        }

        $this->set(compact("series"));
        $this->setFormVariables();
    }

    /**
     * Méthode qui envoie toutes les données nécessaires au formulaire de series.
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
     * @return Fanfiction La série crée/modifiée.
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

            // Fanfiction est informé de la modification de ses fanfictions
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
        $this->request->allowMethod(['post', 'delete']);
        $series = $this->Series->get($id);
        $series = $this->Series->patchEntity($series, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Series->save($series)) {
            $this->Flash->success(__('La série {0} a été supprimée avec succès.', $series->nom));
        } else {
            $this->Flash->error(__('La série {0} n\'a pu être supprimée. Veuillez réessayer.', $series->nom));
        }

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
        $this->request->allowMethod(['post']);
        $series = $this->Series->get($id);
        $series = $this->Series->patchEntity($series, [
            "suppression_date" => null,
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Series->save($series)) {
            $this->Flash->success(__('La série {0} a été restaurée avec succès.', $series->nom));
        } else {
            $this->Flash->error(__('La série {0} n\'a pu être restaurée. Veuillez réessayer.', $series->nom));
        }

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
        if ($this->request->is(["post", "put"])) {
            $series = $this->Series->getWithAssociations($id);
            $series = $this->Series->patchEntity($series, $this->request->getData());

            if ($this->Series->save($series))
                $this->Flash->success(__("La série {0} a été noté et évaluée avec succès.", $series->nom));
            else
                $this->Flash->error(__("La série {0} n'a pu être notée. Veuillez réessayer.", $series->nom));

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
        if ($this->request->is(["post", "put"])) {
            $series = $this->Series->getWithAssociations($id);
            $series = $this->Series->patchEntity($series, ["note" => null, "evaluation" => null, "update_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);

            if ($this->Series->save($series))
                $this->Flash->success(__("La série {0} a été noté et évaluée avec succès.", $series->nom));
            else
                $this->Flash->error(__("La série {0} n'a pu être notée. Veuillez réessayer.", $series->nom));

            $this->redirect(["action" => "index"]);
        }
    }

    /**
     * Méthode pour réinitialiser la liste des séries.
     */
    public function reinitialize()
    {
        $this->request->getSession()->write("series", null);

        $this->Flash->success("Réinitialisation des séries disponibles.");

        $this->redirect(["action" => "index"]);
    }
}
