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
class LangagesController extends AppController implements ObjectControllerInterface
{
    /**
     * Méthode qui va vérifier que l'entité en cours de création/édition n'existe pas déjà.
     *
     * @param array $data Les données du formulaire.
     * @return boolean Indication que le fandom existe déjà ou non.
     */
    public function exist(array $data): bool
    {
        return $this->Langages->find()->where(["nom LIKE" => "%" . $data["nom"] . "%", "abbreviation" => $data["abbreviation"]])->count() > 0;
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

        // Récupération des langages en fonction des parametres.
        $langages = is_null($params) || !array_key_exists("inactive", $params) ? $this->Langages->find('active') : $this->Langages->find('inactive');

        // Décompte des langages.
        $langagesCount = $langages->count();

        // Envoi de la liste des langages, leur décompte et les paramètres au template.
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
        // Récupération du langage à partir de son identifiant.
        $langage = $this->Langages->get($id, [
            'contain' => [],
        ]);

        // Envoi du langage au template.
        $this->set(compact('langage'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Création d'un nouveau langage vide.
        $langage = $this->Langages->newEmptyEntity();

        // Si des données sont postées par le formulaire de la page.
        if ($this->request->is('post')) {

            // Si l'auteur fourni n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Valorisation du langage avec les données du formulaire.
                $langage = $this->Langages->patchEntity($langage, $this->request->getData());

                // Sauvegarde du langage.
                if ($this->Langages->save($langage)) {

                    // Succès de la sauvegarde, avertissement de l'utilisateur.
                    $this->Flash->success(__('Le langage {0} a été sauvegardé avec succès.', $langage->nom));

                    // Redirection de l'utilisateur vers l'index des langages.
                    return $this->redirect(['action' => 'index']);
                }

                // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
                $this->Flash->error(__('Le langage {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $langage->nom));
            }

            // Avertissement de l'utilisateur connecté que l'auteur existe déjà
            $this->Flash->warning(_("L'auteur existe déjà"));
        }

        // Envoi du langage vide au template.
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
        // Récupération du langage avec son identifiant.
        $langage = $this->Langages->get($id, [
            'contain' => [],
        ]);

        // Si des données sont envoyées depuis le formulaire de la page.
        if ($this->request->is(['patch', 'post', 'put'])) {

            // Si l'auteur n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Valorisation du langage avec les données du formulaire.
                $langage = $this->Langages->patchEntity($langage, $this->request->getData());

                // Sauvegarde du langage.
                if ($this->Langages->save($langage)) {

                    // Succès de la sauvegarde, avertissement de l'utilisateur.
                    $this->Flash->success(__('Le langage {0} a été sauvegardé avec succès.', $langage->nom));

                    // Redirection vers l'index des langages.
                    return $this->redirect(['action' => 'index']);
                }

                // Erreur lors de la sauvegarde.
                $this->Flash->error(__('Le langage {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $langage->nom));
            }

            // Avertissement de l'utilisateur connecté que la relation existe déjà
            $this->Flash->warning(_("L'auteur existe déjà"));
        }

        // Envoi du langage au template.
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
        // Vérification que la page de suppression est accédée correctement.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération du langage à supprimer.
        $langage = $this->Langages->get($id);

        // Valorisation des dates de modification et de suppression (suppression logique).
        $langage = $this->Langages->patchEntity($langage, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde du fandom.
        if ($this->Langages->save($langage))

            // Succès de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->success(__('Le langage {0} a été supprimé avec succès.', $langage->nom));
        else
            // Erreur lors de la sauvegarde, avertissement de l'utilisateur.
            $this->Flash->error(__('Le langage {0} n\'a pu être supprimé. Veuillez réessayer.', $langage->nom));
        
        // Redirection vers la page d'index des langages.
        return $this->redirect(['action' => 'index']);
    }
}
