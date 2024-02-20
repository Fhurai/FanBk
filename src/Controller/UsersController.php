<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use Cake\I18n\FrozenTime;
use Cake\Mailer\Mailer;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login', 'add', 'lost']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $params = $this->getRequest()->getParam("?") ?? [];

        $users = is_null($params) || !array_key_exists("inactive", $params) ? $this->Users->find('active') : $this->Users->find('inactive');
        $usersCount = $users->count();

        $this->set(compact('users', 'usersCount', 'params'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $user = $this->Users->patchEntity($user, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);
        if ($this->Users->save($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        $user = $result->getData();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid() && is_null($user->suppression_date)) {


            $user->update_date = FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s");

            if ($this->Users->save($user)) {
                $this->Flash->success(__("Connexion avec succès en tant que : {0}", $user->username));
                $this->loadIntoSession($user);

                // redirect to /articles after login success
                $redirect = $this->request->getQuery('redirect', [
                    'controller' => 'Pages',
                    'action' => 'home',
                ]);

                return $this->redirect($redirect);
            } else
                $this->Flash->error(__('Erreur lors de la sauvegarde de la connexion. Veuillez réessayer.'));
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post')) {
            if (!$result->isValid()) {
                $this->Flash->error(__('Nom d\'utilisateur ou mot de passe invalide.'));
            } else {
                if (!is_null($user->suppression_date)) {
                    $this->Flash->error(__('Utilisateur indisponible. Veuillez contacter l\'administrateur.'));
                    // $mailer = new Mailer('default');
                    // $mailer
                    //     ->setFrom([$user->email => 'Fanfiction Bookmark'])
                    //     ->setTo('kulu57@live.com')
                    //     ->setSubject('FanBk : New access to restricted account')
                    //     ->deliver("$user->username tried to access their account after suppression. Give access back or contact them ?");
                }
            }
        }
    }

    // in src/Controller/UsersController.php
    public function logout()
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $this->request->getSession()->clear();
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    public function lost()
    {
        if($this->request->is(["post"])){

        }
    }

    /**
     * Méthode qui va entrer les informations importantes de l'utilisateurs dans sa session.
     */
    private function loadIntoSession(User $user)
    {
        $this->request->getSession()->write("user.id", $user->id);
        $this->request->getSession()->write("user.username", $user->username);
        $this->request->getSession()->write("user.email", $user->email);
        $this->request->getSession()->write("user.is_admin", $user->is_admin);
        $this->request->getSession()->write("user.nsfw", $user->nsfw);
    }
}
