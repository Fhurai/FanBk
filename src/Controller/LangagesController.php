<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenTime;

/**
 * Langages Controller
 *
 * @property \App\Model\Table\LangagesTable $Langages
 * @method \App\Model\Entity\Langage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LangagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $params = $this->getRequest()->getParam("?") ?? [];

        $langages = is_null($params) || !array_key_exists("inactive", $params) ? $this->Langages->find('active') : $this->Langages->find('inactive');
        $langagesCount = $langages->count();

        $this->set(compact('langages', 'langagesCount', 'params'));
    }

    /**
     * View method
     *
     * @param string|null $id Langage id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $langage = $this->Langages->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('langage'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $langage = $this->Langages->newEmptyEntity();
        if ($this->request->is('post')) {
            $langage = $this->Langages->patchEntity($langage, $this->request->getData());
            if ($this->Langages->save($langage)) {
                $this->Flash->success(__('Le langage {0} a été sauvegardé avec succès.', $langage->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le langage {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $langage->nom));
        }
        $this->set(compact('langage'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Langage id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $langage = $this->Langages->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $langage = $this->Langages->patchEntity($langage, $this->request->getData());
            if ($this->Langages->save($langage)) {
                $this->Flash->success(__('Le langage {0} a été sauvegardé avec succès.', $langage->nom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le langage {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $langage->nom));
        }
        $this->set(compact('langage'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Langage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $langage = $this->Langages->get($id);
        $langage = $this->Langages->patchEntity($langage, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'), 
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Langages->save($langage)) {
            $this->Flash->success(__('Le langage {0} a été supprimé avec succès.', $langage->nom));
        } else {
            $this->Flash->error(__('Le langage {0} n\'a pu être supprimé. Veuillez réessayer.', $langage->nom));
        }

        return $this->redirect(['action' => 'index']);
    }
}
