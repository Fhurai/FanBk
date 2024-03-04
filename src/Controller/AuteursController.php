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
class AuteursController extends AppController implements ObjectControllerInterface
{
    /**
     * Méthode qui va vérifier que l'entité en cours de création/édition n'existe pas déjà.
     *
     * @param array $data Les données du formulaire.
     * @return boolean Indication que l'auteur existe déjà ou non.
     */
    public function exist(array $data): bool
    {
        return $this->Auteurs->find()->where(["nom LIKE" => "%" . $data["nom"] . "%"])->count() > 0;
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

        // Récupération des auteurs en fonction des parametres.
        $auteurs = is_null($params) || !array_key_exists("inactive", $params) ? $this->Auteurs->find('active') : $this->Auteurs->find('inactive');

        // Décompte des auteurs.
        $auteursCount = $auteurs->count();

        // Envoi de la liste des auteurs, leur décompte et les paramètres au template.
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
        // Récupération de l'auteur à partir de son identifiant.
        $auteur = $this->Auteurs->get($id, [
            'contain' => [],
        ]);

        // Envoi de l'auteur au template.
        $this->set(compact('auteur'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Création d'un nouvel auteur vide.
        $auteur = $this->Auteurs->newEmptyEntity();

        // Si des données sont postées par le formulaire de la page.
        if ($this->request->is('post')) {

            // Si l'auteur fourni n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Valorisation de l'auteur avec les données du formulaire.
                $auteur = $this->Auteurs->patchEntity($auteur, $this->request->getData());

                // Sauvegarde de l'auteur.
                if ($this->Auteurs->save($auteur)) {

                    // Succès de la sauvegarde, avertissement de l'utilisateur.
                    $this->Flash->success(__('Le auteur {0} a été sauvegardé avec succès.', $auteur->nom));

                    // Redirection de l'utilisateur vers l'index des auteurs.
                    return $this->redirect(['action' => 'index']);
                }

                // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
                $this->Flash->error(__('Le auteur {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $auteur->nom));
            }

            // Avertissement de l'utilisateur connecté que l'auteur existe déjà
            $this->Flash->warning(_("L'auteur existe déjà"));
        }

        // Envoi de l'auteur vide au template.
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
        // Récupération de l'auteur avec son identifiant.
        $auteur = $this->Auteurs->get($id, [
            'contain' => [],
        ]);

        // Si des données sont envoyées depuis le formulaire de la page.
        if ($this->request->is(['patch', 'post', 'put'])) {

            // Si l'auteur n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Valorisation de l'auteur avec les données du formulaire.
                $auteur = $this->Auteurs->patchEntity($auteur, $this->request->getData());

                // Sauvegarde de l'auteur.
                if ($this->Auteurs->save($auteur)) {

                    // Succès de la sauvegarde, avertissement de l'utilisateur.
                    $this->Flash->success(__('Le auteur {0} a été sauvegardé avec succès.', $auteur->nom));

                    // Redirection vers l'index des auteurs.
                    return $this->redirect(['action' => 'index']);
                }

                // Erreur lors de la sauvegarde.
                $this->Flash->error(__('Le auteur {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $auteur->nom));
            }

            // Avertissement de l'utilisateur connecté que la relation existe déjà
            $this->Flash->warning(_("L'auteur existe déjà"));
        }

        // Envoi de l'auteur au template.
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
        // Vérification que la page de suppression est accédée correctement.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération de l'auteur à supprimer.
        $auteur = $this->Auteurs->get($id);

        // Valorisation des dates de modification et de suppression (suppression logique).
        $auteur = $this->Auteurs->patchEntity($auteur, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde de l'auteur.
        if ($this->Auteurs->save($auteur))
        
            // Succès de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->success(__('Le auteur {0} a été supprimé avec succès.', $auteur->nom));
        else
            // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->error(__('Le auteur {0} n\'a pu être supprimé. Veuillez réessayer.', $auteur->nom));

        // Redirection vers la page d'index des auteurs.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions de l'auteur cliqué.
     * @param string|null $id Auteur id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null)
    {
        // Vidage des paramètres de fanfictions.
        $this->request->getSession()->write("fanfictions", null);

        // Valorisation des paramètres fanfictions avec l'auteur cliqué.
        $params = [];
        $params["search"]["fields"]["auteurs"] = trim($this->Auteurs->get($id)->nom);
        $params["search"]["not"]["auteurs"] = true;
        $params["search"]["operator"]["auteurs"] = "AND";

        // Ecriture des paramètres dans la session.
        $this->writeSession("fanfictions", $params);

        // Redirection vers l'index des fanfictions.
        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
