<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenTime;

/**
 * Fandoms Controller
 *
 * @property \App\Model\Table\FandomsTable $Fandoms
 * @property \App\Model\Table\PersonnagesTable $Personnages
 * @method \App\Model\Entity\Fandom[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FandomsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $params = $this->getRequest()->getParam("?") ?? [];

        $fandoms = is_null($params) || !array_key_exists("inactive", $params) ? $this->Fandoms->find('active') : $this->Fandoms->find('inactive');
        $fandomsCount = $fandoms->count();

        $this->set(compact('fandoms', 'fandomsCount', 'params'));
    }

    /**
     * View method
     *
     * @param string|null $id Fandom id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $fandom = $this->Fandoms->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('fandom'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $fandom = $this->Fandoms->newEmptyEntity();
        if ($this->request->is('post')) {
            $fandom = $this->Fandoms->patchEntity($fandom, $this->request->getData());
            if ($this->Fandoms->save($fandom)) {
                $this->Flash->success(__('Le fandom {0} a été sauvegardé avec succès.', $fandom->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le fandom {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $fandom->nom));
        }
        $this->set(compact('fandom'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Fandom id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fandom = $this->Fandoms->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fandom = $this->Fandoms->patchEntity($fandom, $this->request->getData());
            if ($this->Fandoms->save($fandom)) {
                $this->Flash->success(__('Le fandom {0} a été sauvegardé avec succès.', $fandom->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le fandom {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $fandom->nom));
        }
        $this->set(compact('fandom'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Fandom id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fandom = $this->Fandoms->get($id);
        $fandom = $this->Fandoms->patchEntity($fandom, [[
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'), 
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]]);
        if ($this->Fandoms->save($fandom)) {
            $this->Flash->success(__('Le fandom {0} a été supprimé avec succès.', $fandom->nom));
        } else {
            $this->Flash->error(__('Le fandom {0} n\'a pu être supprimé. Veuillez réessayer.', $fandom->nom));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions du fandom cliqué.
     * @param string|null $id Fandom id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null){
        $this->request->getSession()->write("fanfictions");
        $params = [];
        $params["filters"]["fields"]["fandoms"] = $id;
        $params["filters"]["not"]["fandoms"] = true;
        $params["filters"]["operator"]["fandoms"] = "AND";
        
        $this->writeSession("fanfictions", $params);

        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
