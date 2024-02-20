<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;

/**
 * Relations Controller
 *
 * @property \App\Model\Table\RelationsTable $Relations
 * @method \App\Model\Entity\Relation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RelationsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $params = $this->getRequest()->getParam("?") ?? [];

        $relations = is_null($params) || !array_key_exists("inactive", $params) ? $this->Relations->find('active') : $this->Relations->find('inactive');
        $relationsCount = $relations->count();

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
        $relation = $this->Relations->get($id, [
            'contain' => ['personnages'],
        ]);

        $this->set(compact('relation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $relation = $this->Relations->newEmptyEntity();
        if ($this->request->is('post')) {
            $relation = $this->Relations->patchEntity($relation, $this->request->getData());

            usort($relation->personnages, function ($perso1, $perso2) {
                return strcmp(strtolower($perso1->nom), strtolower($perso2->nom));
            });
            $relation->nom = implode(" / ", array_column($relation->personnages, "nom"));

            if ($this->Relations->save($relation)) {
                $this->Flash->success(__('La relation {0} a été sauvegardée avec succès.', $relation->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La relation {0} n\'a pas pu être sauvegardée. Veuillez réessayer.', $relation->nom));
        }
        $personnages = $this->Relations->personnages->find('list', [
            "keyField" => "id",
            "valueField" => "nom",
            "groupField" => "fandom_obj.nom"
        ])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"])->all();
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
        $relation = $this->Relations->get($id, [
            'contain' => ['personnages'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $relation = $this->Relations->patchEntity($relation, $this->request->getData());
            if ($this->Relations->save($relation)) {
                $this->Flash->success(__('La relation {0} a été sauvegardée avec succès.', $relation->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La relation {0} n\'a pas pu être sauvegardée. Veuillez réessayer.', $relation->nom));
        }
        $personnages = $this->Relations->personnages->find('list', [
            "keyField" => "id",
            "valueField" => "nom",
            "groupField" => "fandom_obj.nom"
        ])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"])->all();
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
        $this->request->allowMethod(['post', 'delete']);
        $relation = $this->Relations->get($id);
        $relation = $this->Relations->patchEntity($relation, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Relations->save($relation)) {
            $this->Flash->success(__('La relation {0} a été supprimée avec succès.', $relation->nom));
        } else {
            $this->Flash->error(__('La relation {0} n\'a pu être supprimée. Veuillez réessayer.', $relation->nom));
        }

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
        $this->request->getSession()->write("fanfictions");
        $params = [];
        $params["filters"]["fields"]["relations"] = $id;
        $params["filters"]["not"]["relations"] = true;
        $params["filters"]["operator"]["relations"] = "AND";

        $this->writeSession("fanfictions", $params);

        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
