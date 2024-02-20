<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;

/**
 * Personnages Controller
 *
 * @property \App\Model\Table\PersonnagesTable $Personnages
 * @method \App\Model\Entity\Personnage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PersonnagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $params = $this->getRequest()->getParam("?") ?? [];

        $personnages = is_null($params) || !array_key_exists("inactive", $params) ?
            $this->Personnages->find('active') :
            $this->Personnages->find('inactive');
        $personnagesCount = $personnages->count();

        $fandoms = $this->Personnages->fandoms->find('list')->all();
        $this->set(compact('personnages', 'personnagesCount', 'fandoms', 'params'));
    }

    /**
     * View method
     *
     * @param string|null $id Personnage id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $personnage = $this->Personnages->get($id, [
            'contain' => ['relations', 'fandoms'],
        ]);

        $this->set(compact('personnage'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $personnage = $this->Personnages->newEmptyEntity();
        if ($this->request->is('post')) {
            $personnage = $this->Personnages->patchEntity($personnage, $this->request->getData());
            if ($this->Personnages->save($personnage)) {
                $this->Flash->success(__('Le personnage {0} a été sauvegardé avec succès.', $personnage->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le personnage {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $personnage->nom));
        }
        $fandoms = $this->Personnages->fandoms->find('list', ['limit' => 200])->order(["nom" => "ASC"])->all();
        $this->set(compact('personnage', 'fandoms'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Personnage id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $personnage = $this->Personnages->get($id, [
            'contain' => ['relations', 'fandoms'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $personnage = $this->Personnages->patchEntity($personnage, $this->request->getData());
            if ($this->Personnages->save($personnage)) {
                $this->Flash->success(__('Le personnage {0} a été sauvegardé avec succès.', $personnage->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le personnage {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $personnage->nom));
        }
        $fandoms = $this->Personnages->fandoms->find('list', ['limit' => 200])->order(["nom" => "ASC"])->all();
        $this->set(compact('personnage', 'fandoms'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Personnage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $personnage = $this->Personnages->get($id);
        $personnage = $this->Personnages->patchEntity($personnage, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Personnages->save($personnage)) {
            $this->Flash->success(__('Le personnage {0} a été supprimé avec succès.', $personnage->nom));
        } else {
            $this->Flash->error(__('Le personnage {0} n\'a pu être supprimé. Veuillez réessayer.', $personnage->nom));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions du personnage cliqué.
     * @param string|null $id Personnage id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null){
        $this->request->getSession()->write("fanfictions");
        $params = [];
        $params["filters"]["fields"]["personnages"] = $id;
        $params["filters"]["not"]["personnages"] = true;
        $params["filters"]["operator"]["personnages"] = "AND";
        
        $this->writeSession("fanfictions", $params);

        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
