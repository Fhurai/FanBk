<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;

/**
 * Auteurs Controller
 *
 * @property \App\Model\Table\AuteursTable $Auteurs
 * @method \App\Model\Entity\Auteur[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuteursController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $params = $this->getRequest()->getParam("?") ?? [];

        $auteurs = is_null($params) || !array_key_exists("inactive", $params) ? $this->Auteurs->find('active') : $this->Auteurs->find('inactive');
        $auteursCount = $auteurs->count();

        $this->set(compact('auteurs', 'auteursCount', 'params'));
    }

    /**
     * View method
     *
     * @param string|null $id Auteur id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $auteur = $this->Auteurs->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('auteur'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $auteur = $this->Auteurs->newEmptyEntity();
        if ($this->request->is('post')) {
            $auteur = $this->Auteurs->patchEntity($auteur, $this->request->getData());
            if ($this->Auteurs->save($auteur)) {
                $this->Flash->success(__('Le auteur {0} a été sauvegardé avec succès.', $auteur->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le auteur {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $auteur->nom));
        }
        $this->set(compact('auteur'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Auteur id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $auteur = $this->Auteurs->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $auteur = $this->Auteurs->patchEntity($auteur, $this->request->getData());
            if ($this->Auteurs->save($auteur)) {
                $this->Flash->success(__('Le auteur {0} a été sauvegardé avec succès.', $auteur->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le auteur {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $auteur->nom));
        }
        $this->set(compact('auteur'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Auteur id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $auteur = $this->Auteurs->get($id);
        $auteur = $this->Auteurs->patchEntity($auteur, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'), 
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Auteurs->save($auteur)) {
            $this->Flash->success(__('Le auteur {0} a été supprimé avec succès.', $auteur->nom));
        } else {
            $this->Flash->error(__('Le auteur {0} n\'a pu être supprimé. Veuillez réessayer.', $auteur->nom));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions de l'auteur cliqué.
     * @param string|null $id Auteur id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null){
        $this->request->getSession()->write("fanfictions");
        $params = [];
        $params["search"]["fields"]["auteurs"] = trim($this->Auteurs->get($id)->nom);
        $params["search"]["not"]["auteurs"] = true;
        $params["search"]["operator"]["auteurs"] = "AND";
        
        $this->writeSession("fanfictions", $params);

        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
