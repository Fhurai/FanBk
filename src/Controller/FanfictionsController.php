<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\FrozenTime;

/**
 * Fanfictions Controller
 *
 * @property \App\Model\Table\FanfictionsTable $Fanfictions
 * @property \App\Model\Table\FandomsTable $Fandoms
 * @property \App\Model\Table\AuteursTable $Auteurs
 * @property \App\Model\Table\RelationsTable $Relations
 * @property \App\Model\Table\PersonnageTable $Personnages
 */
class FanfictionsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        $params = $this->request->getSession()->check("fanfictions") ? $this->request->getSession()->read("fanfictions") : null;

        if (is_null($params) || !is_array($params)) {
            $params = [];
            $params["inactive"] = !is_null($this->request->getParam("?")) ? $this->request->getParam("?")["inactive"] : '0';
        }

        if (is_array($params)) {
            $params["inactive"] = (!is_null($this->request->getParam("?")) && array_key_exists("inactive", $this->request->getParam("?"))) ? $this->request->getParam("?")["inactive"] : '0';
        }

        $this->writeSession("fanfictions", $params);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $params = $this->request->getSession()->read("fanfictions");

        if ($this->request->is(["post", "put"])) {
            $data = $this->request->getData();
            $params = array_merge($params, $data);
            $this->writeSession("fanfictions", $params);
        }

        $fanfictions = $this->Fanfictions->find("search", $params);

        $fanfictionCount = $fanfictions->count();
        $fanfictions = $this->paginate($fanfictions, ["limit" => 25]);

        $this->setFormVariables();
        $this->set(compact('fanfictions', 'params', 'fanfictionCount'));
    }

    /**
     * Page d'ajout d'une fanfiction
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function add()
    {
        $fanfiction = $this->Fanfictions->newEmptyEntity();

        if ($this->request->is("post")) {
            $fanfiction = $this->Fanfictions->getConnection()->transactional(function () {
                return $this->editFanfictionDataAssociation($this->request->getData());
            });
            if ($this->Fanfictions->save($fanfiction, ["associated" => true])) {
                $this->Flash->success(__("Fanfiction \"{0}\" ajoutée avec succès.", $fanfiction->nom));
                $this->redirect(["action" => "index"]);
            } else
                $this->Flash->error("Une erreur a été rencontrée lors de la sauvegarde de la fanfiction. Veuillez réessayer.");
        }

        $this->set(compact("fanfiction"));
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
        $fanfiction = $this->Fanfictions->getWithAssociations($id);

        if ($this->request->is(["post", "put"])) {
            $fanfiction = $this->Fanfictions->getConnection()->transactional(function () use ($id) {
                return $this->editFanfictionDataAssociation($this->request->getData(), (int) $id);
            });

            if ($this->Fanfictions->save($fanfiction, ["associated" => true])) {
                $this->Flash->success(__("Fanfiction \"{0}\" éditée avec succès.", $fanfiction->nom));
                $this->redirect(["action" => "index"]);
            } else
                $this->Flash->error("Une erreur a été rencontrée lors de la sauvegarde de la fanfiction. Veuillez réessayer.");
        }

        $this->set(compact("fanfiction"));
        $this->setFormVariables();
    }

    /**
     * Méthode qui envoie toutes les données nécessaires au formulaire de fanfiction.
     */
    private function setFormVariables()
    {
        $auteurs = $this->Auteurs->find("list")->order(["nom"]);
        $fandoms = $this->Fandoms->find("list")->order(["nom"]);
        $langages = $this->Langages->find("list")->order(["nom"]);
        $relations = $this->Relations->find("list")->order(["nom"]);
        $personnages = $this->Personnages->find("list")->order(["nom"]);
        $tags = $this->Tags->find("list")->order(["nom"]);


        $parametres = Configure::check("parametres") ? Configure::read("parametres") : [];
        
        $nsfw = $this->request->getSession()->read("user.nsfw", false);

        if (!$nsfw) {
            $parametres["Classement"] = array_slice($parametres["Classement"], 0, 3, true);
        }

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
        if ($idFanfiction === 0) $fanfiction = $this->Fanfictions->newEmptyEntity();
        else $fanfiction = $this->Fanfictions->getWithAssociations($idFanfiction);

        /**
         * PARTIE NOM / CLASSEMENT / DESCRIPTION
         */
        $fanfiction->nom = trim($data["nom"]);
        $fanfiction->classement = trim($data["classement"]);
        $fanfiction->description = trim($data["description"]);

        /**
         * PARTIE AUTEUR
         */
        if (!empty($data["auteur-new"])) { // Si des données existes pour un nouvel auteur

            // Création de l'entité vide
            $auteur = $this->Auteurs->newEmptyEntity();

            // Remplissage des données de l'auteur
            $auteur->nom = trim($data["auteur-new"]);

            // Sauvegarde de l'auteur (obligation ou il n'est pas sauvegardé lors de la sauvegarde de la fanfiction).
            $this->Auteurs->save($auteur);

            // Ajout de l'identifiant de l'auteur dans la fanfiction.
            $fanfiction->auteur = $auteur->id;
        } else
            // Pas de donnée d'un nouvel auteur, donc utilisation de l'identifiant donné par le formulaire.
            $fanfiction->auteur = $data["auteur"];

        /**
         * PARTIE LANGAGES
         */
        if (!empty($data["langage-new"])) { // Si des données existes pour un nouveau langage

            // Création de l'entité vide
            $langage = $this->Langages->newEmptyEntity();

            // Remplissage des données du langage
            $langage->nom = trim($data["langage-new"]);
            $langage->abbreviation = strtoupper(substr(trim($data["langage-new"]), 0, 2));

            // Ajout du langage dans la fanfiction
            $fanfiction->langage_obj = $langage;
        } else
            // Pas de donnée d'un nouveau langage, donc utilisation de l'identifiant donné par le formulaire.
            $fanfiction->langage = $data["langage"];


        /**
         * PARTIE LIENS
         */
        if (!empty($fanfiction->liens)) { // Si un tableau des tags existent déjà pour la fanfiction
            //Parcours des données de la fanfiction
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
            foreach ($data["fandoms"]["_ids"] as $idFandom)
                if (array_search($idFandom, array_column($fanfiction->fandoms, "id")) === false)
                    array_push($fanfiction->fandoms, $this->Fandoms->get($idFandom));

            // Fanfiction est informé de la modification de ses fandoms
            $fanfiction->setDirty("fandoms");
        } else {
            // Pas de tableau des tags existant, il est créé.
            $fanfiction->fandoms = [];
            if (array_key_exists("fandoms", $data) && is_array($data["fandoms"]["_ids"])) {
                foreach ($data["fandoms"]["_ids"] as $id) // Parcours des données du formulaire pour les fandoms
                    $fanfiction->fandoms[] = $this->Fandoms->get($id); // Ajout des fandoms identifiés dans les données du formulaire.
            }
        }
        if (!empty($data["fandoms-new"])) { // Si un nouveau fandom est créé

            // Création de l'entité vide
            $fandom = $this->Fandoms->newEmptyEntity();

            // Ajout des données du fandom.
            $fandom->nom = trim($data["fandoms-new"]);

            // Ajout du nouveau tag dans les fandoms de la fanfiction.
            $fanfiction->fandoms[] = $fandom;
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
            foreach ($data["relations"]["_ids"] as $idRelation)
                if (array_search($idRelation, array_column($fanfiction->relations, "id")) === false)
                    array_push($fanfiction->relations, $this->Relations->get($idRelation));

            // Fanfiction est informé de la modification de ses relations
            $fanfiction->setDirty("relations");
        } else {
            // Pas de tableau des relations existant, il est créé.
            $fanfiction->relations = [];
            if (array_key_exists("relations", $data) && is_array($data["relations"]["_ids"])) {
                foreach ($data["relations"]["_ids"] as $id) // Parcours des données du formulaire pour les relations
                    $fanfiction->relations[] = $this->Relations->get($id); // Ajout des relations identifiées dans les données du formulaire.
            }
        }
        if (!empty($data["relations-new"])) { // Si une nouvelle relation est créée

            // Création de l'entité vide
            $relation = $this->Relations->newEmptyEntity();

            // Ajout des données de la relation.
            $relation->nom = trim($data["relations-new"]);

            // Ajout de la nouvelle relation dans les relations de la fanfiction.
            $fanfiction->relations[] = $relation;
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
            foreach ($data["personnages"]["_ids"] as $idPersonnage)
                if (array_search($idPersonnage, array_column($fanfiction->personnages, "id")) === false)
                    array_push($fanfiction->personnages, $this->Personnages->get($idPersonnage));

            // Fanfiction est informé de la modification de ses personnages
            $fanfiction->setDirty("personnages");
        } else {
            // Pas de tableau des personnages existant, il est créé.
            $fanfiction->personnages = [];

            if (array_key_exists("personnages", $data) && is_array($data["personnages"]["_ids"])) {
                foreach ($data["personnages"]["_ids"] as $id) // Parcours des données du formulaire pour les personnages
                    $fanfiction->personnages[] = $this->Personnages->get($id); // Ajout des personnages identifiés dans les données du formulaire.
            }
        }
        if (!empty($data["personnages-new"])) { // Si un nouveau personnage est créé

            // Création de l'entité vide
            $personnage = $this->Personnages->newEmptyEntity();

            // Ajout des données du personnage.
            $personnage->nom = trim($data["personnages-new"]);
            $personnage->fandom = array_key_exists(0, $data["fandoms"]) ? $data["fandoms"][0] : 1;

            // Ajout du nouveau personnage dans les personnages de la fanfiction.
            $fanfiction->personnages[] = $personnage;
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
            foreach ($data["tags"]["_ids"] as $idTag)
                if (array_search($idTag, array_column($fanfiction->tags, "id")) === false)
                    array_push($fanfiction->tags, $this->Tags->get($idTag));

            // Fanfiction est informé de la modification de ses tags
            $fanfiction->setDirty("tags");
        } else {
            // Pas de tableau des tags existant, il est créé.
            $fanfiction->tags = [];

            if (array_key_exists("tags", $data) && is_array($data["tags"]["_ids"])) {
                foreach ($data["tags"]["_ids"] as $id) // Parcours des données du formulaire pour les tags
                    $fanfiction->tags[] = $this->Tags->get($id); // Ajout des tags identifiés dans les données du formulaire.
            }
        }
        if (!empty($data["tags-new"])) { // Si un nouveau tag est créé

            // Création de l'entité vide
            $tag = $this->Tags->newEmptyEntity();

            // Ajout des données du tag.
            $tag->nom = trim($data["tags-new"]);
            $tag->description = trim($data["tags-desc-new"]);

            // Ajout du nouveau tag dans les tags de la fanfiction.
            $fanfiction->tags[] = $tag;
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
        $this->request->allowMethod(['post', 'delete']);
        $fanfiction = $this->Fanfictions->get($id);
        $fanfiction = $this->Fanfictions->patchEntity($fanfiction, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Fanfictions->save($fanfiction)) {
            $this->Flash->success(__('La fanfiction {0} a été supprimée avec succès.', $fanfiction->nom));
        } else {
            $this->Flash->error(__('La fanfiction {0} n\'a pu être supprimée. Veuillez réessayer.', $fanfiction->nom));
        }

        return $this->redirect(['action' => 'index']);
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
        $this->request->allowMethod(['post']);
        $fanfiction = $this->Fanfictions->get($id);
        $fanfiction = $this->Fanfictions->patchEntity($fanfiction, [
            "suppression_date" => null,
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Fanfictions->save($fanfiction)) {
            $this->Flash->success(__('La fanfiction {0} a été restaurée avec succès.', $fanfiction->nom));
        } else {
            $this->Flash->error(__('La fanfiction {0} n\'a pu être restaurée. Veuillez réessayer.', $fanfiction->nom));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Note method
     *
     * @param string|null $id Fanfiction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function note($id = null)
    {
        if ($this->request->is(["post", "put"])) {
            $fanfiction = $this->Fanfictions->getWithAssociations($id);
            $fanfiction = $this->Fanfictions->patchEntity($fanfiction, $this->request->getData());

            if ($this->Fanfictions->save($fanfiction))
                $this->Flash->success(__("La fanfiction {0} a été noté et évaluée avec succès.", $fanfiction->nom));
            else
                $this->Flash->error(__("La fanfiction {0} n'a pu être notée. Veuillez réessayer.", $fanfiction->nom));

            $this->redirect(["action" => "index"]);
        }
    }

    /**
     * Denote method
     *
     * @param string|null $id Fanfiction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function denote($id = null)
    {
        if ($this->request->is(["post", "put"])) {
            $fanfiction = $this->Fanfictions->getWithAssociations($id);
            $fanfiction = $this->Fanfictions->patchEntity($fanfiction, ["note" => null, "evaluation" => null, "update_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);

            if ($this->Fanfictions->save($fanfiction))
                $this->Flash->success(__("La fanfiction {0} a été noté et évaluée avec succès.", $fanfiction->nom));
            else
                $this->Flash->error(__("La fanfiction {0} n'a pu être notée. Veuillez réessayer.", $fanfiction->nom));

            $this->redirect(["action" => "index"]);
        }
    }

    /**
     * Méthode pour check si un lien de fanfiction est déjà en bdd ou non
     * @return \Cake\Http\Response|null|void Redirects to existing fanfiction or add page.
     */
    public function checkLien()
    {
        if ($this->request->is(["post", "put"])) {
            $data = $this->request->getData();

            $lien = $this->Liens->find("all")->contain(["fanfictions"])->where(["FanfictionsLiens.lien" => $data["lien"]])->first();
            if (!is_null($lien)) {

                // Mise à vide de la session pour les fanfictions.
                $this->request->getSession()->write("fanfictions");

                // Ajout de l'auteur dans la session pour la recherche fanfictions.
                $params = [];
                $params["search"]["fields"]["auteurs"] = trim($this->Auteurs->get($lien->fanfiction_obj->auteur)->nom);
                $params["search"]["not"]["auteurs"] = true;
                $params["search"]["operator"]["auteurs"] = "AND";
                $this->writeSession("fanfictions", $params);

                // Redirection vers la page d'index des fanfictions.
                $this->redirect(["action" => "index"]);
            } else
                $this->redirect(["action" => "add"]);
        }
        $this->redirect(["action" => "index"]);
    }

    /**
     * Méthode pour changer la valeur de la variable NSFW
     */
    public function nsfw()
    {

        $nsfwToken = $this->request->getSession()->read("user.nsfw");

        if (is_null($nsfwToken))
            $this->request->getSession()->write("user.nsfw", true);
        else
            $this->request->getSession()->write("user.nsfw", !$nsfwToken);

        $this->redirect(['controller' => 'Fanfictions', 'action' => 'index']);
    }

    /**
     * Méthode pour réinitialiser la liste des fanfictions.
     */
    public function reinitialize()
    {
        $this->request->getSession()->write("fanfictions", null);

        $this->Flash->success("Réinitialisation des fanfictions disponibles.");

        $this->redirect(["action" => "index"]);
    }
}
