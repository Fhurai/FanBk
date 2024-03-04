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
class FandomsController extends AppController implements ObjectControllerInterface
{
    /**
     * Méthode qui va vérifier que l'entité en cours de création/édition n'existe pas déjà.
     *
     * @param array $data Les données du formulaire.
     * @return boolean Indication que le fandom existe déjà ou non.
     */
    public function exist(array $data): bool
    {
        return $this->Fandoms->find()->where(["nom LIKE" => "%" . $data["nom"] . "%"])->count() > 0;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Récupération des paramètres depuis l'url.
        $params = $this->getRequest()->getParam("?") ?? [];

        // Récupération des fandoms en fonction des parametres.
        $fandoms = is_null($params) || !array_key_exists("inactive", $params) ? $this->Fandoms->find('active') : $this->Fandoms->find('inactive');

        // Décompte des fandoms.
        $fandomsCount = $fandoms->count();

        // Envoi de la liste des fandoms, leur décompte et les paramètres au template.
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
        // Récupération du fandom à partir de son identifiant.
        $fandom = $this->Fandoms->get($id, [
            'contain' => [],
        ]);

        // Envoi du fandom au template.
        $this->set(compact('fandom'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Création d'un nouveau fandom vide.
        $fandom = $this->Fandoms->newEmptyEntity();

        // Si des données sont postées par le formulaire de la page.
        if ($this->request->is('post')) {

            // Si l'auteur fourni n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Valorisation du fandom avec les données du formulaire.
                $fandom = $this->Fandoms->patchEntity($fandom, $this->request->getData());

                // Sauvegarde du fandom.
                if ($this->Fandoms->save($fandom)) {

                    // Succès de la sauvegarde, avertissement de l'utilisateur.
                    $this->Flash->success(__('Le fandom {0} a été sauvegardé avec succès.', $fandom->nom));

                    // Redirection de l'utilisateur vers l'index des fandoms.
                    return $this->redirect(['action' => 'index']);
                }

                // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
                $this->Flash->error(__('Le fandom {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $fandom->nom));
            }

            // Avertissement de l'utilisateur connecté que l'auteur existe déjà
            $this->Flash->warning(_("L'auteur existe déjà"));
        }

        // Envoi du fandom vide au template.
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
        // Récupération du fandom avec son identifiant.
        $fandom = $this->Fandoms->get($id, [
            'contain' => [],
        ]);

        // Si des données sont envoyées depuis le formulaire de la page.
        if ($this->request->is(['patch', 'post', 'put'])) {

            // Si l'auteur n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Valorisation du fandom avec les données du formulaire.
                $fandom = $this->Fandoms->patchEntity($fandom, $this->request->getData());

                // Sauvegarde du fandom.
                if ($this->Fandoms->save($fandom)) {

                    // Succès de la sauvegarde, avertissement de l'utilisateur.
                    $this->Flash->success(__('Le fandom {0} a été sauvegardé avec succès.', $fandom->nom));

                    // Redirection vers l'index des fandoms.
                    return $this->redirect(['action' => 'index']);
                }

                // Erreur lors de la sauvegarde.
                $this->Flash->error(__('Le fandom {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $fandom->nom));
            }

            // Avertissement de l'utilisateur connecté que la relation existe déjà
            $this->Flash->warning(_("L'auteur existe déjà"));
        }

        // Envoi du fandom au template.
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
        // Vérification que la page de suppression est accédée correctement.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération du fandom à supprimer.
        $fandom = $this->Fandoms->get($id);

        // Valorisation des dates de modification et de suppression (suppression logique).
        $fandom = $this->Fandoms->patchEntity($fandom, [[
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]]);

        // Sauvegarde du fandom.
        if ($this->Fandoms->save($fandom))

            // Succès de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->success(__('Le fandom {0} a été supprimé avec succès.', $fandom->nom));

        else
            // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->error(__('Le fandom {0} n\'a pu être supprimé. Veuillez réessayer.', $fandom->nom));

        // Redirection vers la page d'index des fandoms.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions du fandom cliqué.
     * @param string|null $id Fandom id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null)
    {
        // Vidage des paramètres de fanfictions.
        $this->request->getSession()->write("fanfictions");
        $params = [];

        // Valorisation des paramètres fanfictions avec le fandom cliqué.
        $params["filters"]["fields"]["fandoms"] = $id;
        $params["filters"]["not"]["fandoms"] = true;
        $params["filters"]["operator"]["fandoms"] = "AND";

        // Ecriture des paramètres dans la session.
        $this->writeSession("fanfictions", $params);

        // Redirection vers l'index des fanfictions.
        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
