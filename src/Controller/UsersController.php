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
class UsersController extends AppController implements ObjectControllerInterface
{

    /**
     * @inheritDoc
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        // Appel à la méthode parente.
        parent::beforeFilter($event);

        // Autorise pages à être accédées sans être connecté.
        $this->Authentication->addUnauthenticatedActions(['login', 'add', 'lost']);
    }

    /**
     * @inheritdoc
     */
    public function initialize(): void
    {
        // Appel de la méthode parente.
        parent::initialize();

        // Récupération de l'indication que l'utilisateur connecté est admin ou non.
        $loggedAdmin = $this->request->getSession()->read("user.is_admin", false);

        // Envoi de la donné user admin au template.
        $this->set(compact("loggedAdmin"));
    }

    /**
     * Méthode qui va vérifier que l'entité en cours de création/édition n'existe pas déjà.
     *
     * @param array $data Les données du formulaire.
     * @return boolean Indication que l'utilisateur existe déjà ou non.
     */
    public function exist(array $data): bool
    {
        return $this->Fandoms->find()->where(["LOWER(username)" => strtolower($data["nom"])])->count() > 0;
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

        // Récupération des utilisateurs en fonction des paramètres.
        $users = is_null($params) || !array_key_exists("inactive", $params) ? $this->Users->find('active') : $this->Users->find('inactive');

        // Décompte des utilisateurs pour afficher le nombre sur la page d'index.
        $usersCount = $users->count();

        //  Envoi des données au template.
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
        // Récupération de l'utilisateur avec toutes ses associations
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        // Envoi de l'utilisateur vers le template.
        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Création d'un utilisateur vide.
        $user = $this->Users->newEmptyEntity();

        // Données envoyées depuis le formulaire de la page.
        if ($this->request->is('post')) {

            // Si l'utilisateur fourni n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Données du formulaire utilisées dans l'utilisateur.
                $user = $this->Users->patchEntity($user, $this->request->getData());

                // Sauvegarde de l'utilisateur
                if ($this->Users->save($user)) {

                    // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                    $this->Flash->success(__('The user has been saved.'));

                    // Redirection vers l'index des utilisateurs.
                    return $this->redirect(['action' => 'index']);
                }

                // Avertissement de l'utilisateur connecté que l'utilisateur existe déjà
                $this->Flash->warning(_("l'utilisateur existe déjà"));
            }

            // Avertissement de l'utilisateur que l'utilisateur n'a pas pu être sauvegardé.
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        //  Envoi des données au template.
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
        // Récupération de l'utilisateur avec toutes ses associations
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        // Données envoyées depuis le formulaire de la page.
        if ($this->request->is(['patch', 'post', 'put'])) {

            // Si l'utilisateur fourni n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Données du formulaire utilisées dans l'utilisateur.
                $user = $this->Users->patchEntity($user, $this->request->getData());

                // Sauvegarde de l'utilisateur
                if ($this->Users->save($user)) {

                    // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                    $this->Flash->success(__('The user has been saved.'));

                    // Redirection vers l'index des utilisateurs.
                    return $this->redirect(['action' => 'index']);
                }

                // Avertissement de l'utilisateur connecté que l'utilisateur existe déjà
                $this->Flash->warning(_("l'utilisateur existe déjà"));
            }

            // Avertissement de l'utilisateur que l'utilisateur n'a pas pu être sauvegardé.
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        //  Envoi des données au template.
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
        // Vérification que la page est appelé depuis un formulaire ou un bouton de suppression.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération de l'utilisateur à supprimer.
        $user = $this->Users->get($id);

        // Suppression logique (date de suppression valorisée).
        $user = $this->Users->patchEntity($user, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde de l'utilisateur
        if ($this->Users->save($user))

            // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
            $this->Flash->success(__('The user has been deleted.'));
        else

            // Avertissement du développeur et de l'utilisateur que l'utilisateur n'a pas pu être supprimé.
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));

        // Redirection vers l'index des utilisateurs.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login Method
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function login()
    {
        // Récupération des données résultantes de la tentative de connexion.
        $result = $this->Authentication->getResult();

        // Récupération de l'utilisateur connecté.
        $user = $result->getData();

        // La tentative de connexion.
        if ($result && $result->isValid() && is_null($user->suppression_date)) {

            // Mise à jour de la date d'update qui est aussi la date de dernière connexion.
            $user->update_date = FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s");

            // Sauvegarde de l'utilisateur.
            if ($this->Users->save($user)) {

                // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                $this->Flash->success(__("Connexion avec succès en tant que : {0}", $user->username));

                // Chargement des infos importantes de l'utilisateur (identifiant, username, email, is_admin, nsfw) dans la session.
                $this->loadIntoSession($user);

                // Redirection vers la page d'accueil de l'application.
                return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
            } else

                // Avertissement de l'utilisateur que la connexion n'a pu être sauvegardée.
                $this->Flash->error(__('Erreur lors de la sauvegarde de la connexion. Veuillez réessayer.'));
        }

        // Des données sont envoyées du formulaire.
        if ($this->request->is('post')) {

            // La tentative de connexion échoue.
            if (!$result->isValid())

                // Avertissement de l'utilisateur de la tentative sans succès.
                $this->Flash->error(__('Nom d\'utilisateur ou mot de passe invalide.'));
            else {

                // La tentative fonctionne pour un compte supprimé.
                if (!is_null($user->suppression_date)) {

                    // Avertissement de l'utilisateur.
                    $this->Flash->error(__('Utilisateur indisponible. Veuillez contacter l\'administrateur.'));

                    // Création de l'email vers l'administrateur pour l'avertir de la connexion sur utilisateur supprimé.
                    $mailer = new Mailer('default');
                    $mailer
                        ->setFrom([$user->email => 'Fanfiction Bookmark'])
                        ->setTo('kulu57@live.com')
                        ->setSubject('FanBk : New access to restricted account')
                        ->deliver("$user->username tried to access their account after suppression. Give access back or contact them ?");
                }
            }
        }
    }

    /**
     * Logout Method
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function logout()
    {
        // Vérification que l'utilisateur est bien connecté.
        $result = $this->Authentication->getResult();

        // Vérification que la tentative de connexion a réussi et que l'utilisateur existe.
        if ($result && $result->isValid()) {

            // Nettoyage de la session.
            $this->request->getSession()->clear();

            // Déconnexion de l'utilisateur.
            $this->Authentication->logout();

            // Redirection vers la page de connexion.
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    /**
     * Method to recuperate a lost login.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function lost()
    {
        // Pas implémenté pour le moment.
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
