<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;
use Psr\Log\LogLevel;

/**
 * Relations Controller
 *
 * @property \App\Model\Table\RelationsTable $Relations
 * @method \App\Model\Entity\Relation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RelationsController extends AppController implements ObjectControllerInterface
{

    /**
     * Méthode qui définit si une relation existe avec les personnages.
     *
     * @param array $data Les données du formulaire.
     * @return bool Indication si la relation existe.
     */
    public function exist(array $data): bool
    {
        // Récupération du tableau des noms de personnages de la relation à vérifier.
        $personnages = array_map(function ($id) {
            return $this->Personnages->get($id)->nom;
        }, $data["personnages"]);

        // Tri des personnages par ordre alphabétique dans la relation.
        usort($personnages, function ($perso1, $perso2) {
            return strcmp(strtolower($perso1), strtolower($perso2));
        });

        // Retourne si une relation existe avec le noom de tous ces personnages.
        return $this->Relations->find()->where(["nom" => implode(" / ", $personnages)])->count() > 0;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Récupération des paramètres si présents dans l'url (?inactive=1).
        $params = $this->getRequest()->getParam("?") ?? [];

        // Récupération des relations en fonction des paramètres.
        $relations = is_null($params) || !array_key_exists("inactive", $params) ? $this->Relations->find('active') : $this->Relations->find('inactive');

        // Décompte des relations pour afficher le nombre sur la page d'index.
        $relationsCount = $relations->count();

        // Récupération des fandoms sous forme de query.
        $this->set(compact('relations', 'relationsCount', 'params'));
    }

    /**
     * View method
     *
     * @param string|null $id Relation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // Récupération de la relation avec toutes ses associations
        $relation = $this->Relations->get($id, [
            'contain' => ['personnages'],
        ]);

        // Envoi de la relation vers le template.
        $this->set(compact('relation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Création d'une relation vide.
        $relation = $this->Relations->newEmptyEntity();

        // Données envoyées depuis le formulaire de la page.
        if ($this->request->is('post')) {

            if (!$this->exist($this->request->getData())) {

                // Données du formulaire utilisées dans la relation.
                $relation = $this->Relations->patchEntity($relation, $this->request->getData());

                // Pour chaque personnage dans les données du formulaire, récupération de toutes ses informations à partir de son identifiant.
                foreach ($this->request->getData("personnages") as $id) $relation->personnages[] = $this->Personnages->get($id);

                // Tri des personnages par ordre alphabétique dans la relation.
                usort($relation->personnages, function ($perso1, $perso2) {
                    return strcmp(strtolower($perso1->nom), strtolower($perso2->nom));
                });

                // Automatisation du nom de la relation avec le nom des personnages.
                $relation->nom = implode(" / ", array_column($relation->personnages, "nom"));

                // Sauvegarde de la relation.
                if ($this->Relations->save($relation)) {

                    // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                    $this->Flash->success(__('La relation {0} a été sauvegardée avec succès.', $relation->nom));

                    // Redirection vers l'index des personnages.
                    return $this->redirect(['action' => 'index']);
                }

                // Avertissement du développeur et de l'utilisateur que le personnage n'a pas pu être sauvegardé.
                $this->log("La relation n'a pas pu être sauvegardé", LogLevel::ALERT, $relation);
                $this->Flash->error(__('La relation {0} n\'a pas pu être sauvegardée. Veuillez réessayer.', $relation->nom));
            }

            // Avertissement de l'utilisateur connecté que la relation existe déjà
            $this->Flash->warning(_("La relation existe déjà"));
        }

        // Récupération des personnages sous forme de query, groupés par nom de fandom.
        $personnages = $this->Relations->personnages->find('list', [
            "keyField" => "id",
            "valueField" => "nom",
            "groupField" => "fandom_obj.nom"
        ])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"]);

        //  Envoi des données au template.
        $this->set(compact('relation', 'personnages'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Relation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // Récupération de la relation avec toutes ses associations
        $relation = $this->Relations->get($id, [
            'contain' => ['personnages'],
        ]);

        // Données envoyées depuis le formulaire de la page.
        if ($this->request->is(['patch', 'post', 'put'])) {

            if (!$this->exist($this->request->getData())) {

                // Données du formulaire utilisées dans la relation.
                $relation = $this->Relations->patchEntity($relation, $this->request->getData());

                // Tri des personnages par ordre alphabétique dans la relation.
                usort($relation->personnages, function ($perso1, $perso2) {
                    return strcmp(strtolower($perso1->nom), strtolower($perso2->nom));
                });

                // Sauvegarde de la relation
                if ($this->Relations->save($relation)) {

                    // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                    $this->Flash->success(__('La relation {0} a été sauvegardée avec succès.', $relation->nom));

                    // Redirection vers l'index des relations.
                    return $this->redirect(['action' => 'index']);
                }

                // Avertissement du développeur et de l'utilisateur que le personnage n'a pas pu être sauvegardé.
                $this->log("La relation n\'a pas pu être sauvegardée", LogLevel::ALERT, $relation);
                $this->Flash->error(__('La relation {0} n\'a pas pu être sauvegardée. Veuillez réessayer.', $relation->nom));
            }

            // Avertissement de l'utilisateur connecté que la relation existe déjà
            $this->Flash->warning(_("La relation existe déjà"));
        }

        // Récupération des personnages sous forme de query, groupés par nom de fandom.
        $personnages = $this->Relations->personnages->find('list', [
            "keyField" => "id",
            "valueField" => "nom",
            "groupField" => "fandom_obj.nom"
        ])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"]);

        //  Envoi des données au template.
        $this->set(compact('relation', 'personnages'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Relation id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        // Vérification que la page est appelé depuis un formulaire ou un bouton de suppression.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération du personnage à supprimer.
        $relation = $this->Relations->get($id);

        // Suppression logique (date de suppression valorisée).
        $relation = $this->Relations->patchEntity($relation, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde de la relation
        if ($this->Relations->save($relation)) {

            // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
            $this->Flash->success(__('La relation {0} a été supprimée avec succès.', $relation->nom));
        } else {

            // Avertissement du développeur et de l'utilisateur que le personnage n'a pas pu être supprimé.
            $this->log("La relation n'a pas pu être supprimée.", LogLevel::ALERT, $relation);
            $this->Flash->error(__('La relation {0} n\'a pu être supprimée. Veuillez réessayer.', $relation->nom));
        }

        // Redirection vers l'index des relations.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions de la relation cliquée.
     * @param string|null $id Relation id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null)
    {
        //Nettoyage à vide du champ fanfictions dans la session.
        $this->request->getSession()->write("fanfictions");

        // Paramètres set avec les données du personnage.
        $params = [];
        $params["filters"]["fields"]["relations"] = $id;
        $params["filters"]["not"]["relations"] = true;
        $params["filters"]["operator"]["relations"] = "AND";

        // Paramètres enregistrés dans la session.
        $this->writeSession("fanfictions", $params);

        // Redirection vers l'index des fanfictions.
        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
