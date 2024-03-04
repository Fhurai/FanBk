<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;

/**
 * Tags Controller
 *
 * @property \App\Model\Table\TagsTable $Tags
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TagsController extends AppController implements ObjectControllerInterface
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
        // Récupération des paramètres si présents dans l'url (?inactive=1).
        $params = $this->getRequest()->getParam("?") ?? [];

        // Récupération des tags en fonction des paramètres.
        $tags = is_null($params) || !array_key_exists("inactive", $params) ? $this->Tags->find('active') : $this->Tags->find('inactive');

        // Décompte des tags pour afficher le nombre sur la page d'index.
        $tagsCount = $tags->count();

        //  Envoi des données au template.
        $this->set(compact('tags', 'tagsCount', 'params'));
    }

    /**
     * View method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // Récupération du tag avec toutes ses associations
        $tag = $this->Tags->get($id, [
            'contain' => [],
        ]);

        // Envoi du tag vers le template.
        $this->set(compact('tag'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Création d'un tag vide.
        $tag = $this->Tags->newEmptyEntity();

        // Données envoyées depuis le formulaire de la page.
        if ($this->request->is('post')) {

            // Si le tag fourni n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Données du formulaire utilisées dans le tag.
                $tag = $this->Tags->patchEntity($tag, $this->request->getData());

                // Sauvegarde du tag
                if ($this->Tags->save($tag)) {

                    // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                    $this->Flash->success(__('The tag has been saved.'));

                    // Redirection vers l'index des tags.
                    return $this->redirect(['action' => 'index']);
                }

                // Avertissement de l'utilisateur connecté que le tag existe déjà
                $this->Flash->warning(_("le tag existe déjà"));
            }

            // Avertissement de l'utilisateur que le tag n'a pas pu être sauvegardé.
            $this->Flash->error(__('The tag could not be saved. Please, try again.'));
        }

        //  Envoi des données au template.
        $this->set(compact('tag'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // Récupération du tag avec toutes ses associations
        $tag = $this->Tags->get($id, [
            'contain' => [],
        ]);

        // Données envoyées depuis le formulaire de la page.
        if ($this->request->is(['patch', 'post', 'put'])) {

            // Si le tag fourni n'existe pas déjà.
            if (!$this->exist($this->request->getData())) {

                // Données du formulaire utilisées dans le tag.
                $tag = $this->Tags->patchEntity($tag, $this->request->getData());

                // Sauvegarde du tag
                if ($this->Tags->save($tag)) {

                    // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                    $this->Flash->success(__('The tag has been saved.'));

                    // Redirection vers l'index des tags.
                    return $this->redirect(['action' => 'index']);
                }
                // Avertissement de l'utilisateur connecté que le tag existe déjà
                $this->Flash->warning(_("le tag existe déjà"));
            }

            // Avertissement de l'utilisateur que le tag n'a pas pu être sauvegardé.
            $this->Flash->error(__('The tag could not be saved. Please, try again.'));
        }

        //  Envoi des données au template.
        $this->set(compact('tag'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        // Vérification que la page est appelé depuis un formulaire ou un bouton de suppression.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération du tag à supprimer.
        $tag = $this->Tags->get($id);

        // Suppression logique (date de suppression valorisée).
        $tag = $this->Tags->patchEntity($tag, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde du tag
        if ($this->Tags->save($tag))

            // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
            $this->Flash->success(__('Le tag {0} a été supprimé avec succès.', $tag->nom));
        else

            // Avertissement du développeur et de l'utilisateur que le tag n'a pas pu être supprimé.
            $this->Flash->error(__('Le tag {0} n\'a pu être supprimé. Veuillez réessayer.', $tag->nom));

        // Redirection vers l'index des tags.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions du tag cliqué.
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null)
    {
        //Nettoyage à vide du champ fanfictions dans la session.
        $this->request->getSession()->write("fanfictions");

        // Paramètres set avec les données du tag.
        $params = [];
        $params["filters"]["fields"]["tags"] = $id;
        $params["filters"]["not"]["tags"] = true;
        $params["filters"]["operator"]["tags"] = "AND";

        // Paramètres enregistrés dans la session.
        $this->writeSession("fanfictions", $params);

        // Redirection vers l'index des fanfictions.
        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
