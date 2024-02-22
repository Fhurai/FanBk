<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;
use Psr\Log\LogLevel;

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
        // Récupération des paramètres si présents dans l'url (?inactive=1).
        $params = $this->getRequest()->getParam("?") ?? [];

        // Récupération des personnages en fonction des paramètres.
        $personnages = is_null($params) || !array_key_exists("inactive", $params) ?
            $this->Personnages->find('active') :
            $this->Personnages->find('inactive');

        // Décompte des personnages pour afficher le nombre sur la page d'index.
        $personnagesCount = $personnages->count();

        // Récupération des fandoms sous forme de query.
        $fandoms = $this->Personnages->fandoms->find('list');

        //  Envoi des données au template.
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
        // Récupération du personnage avec toutes ses associations
        $personnage = $this->Personnages->get($id, [
            'contain' => ['relations', 'fandoms'],
        ]);

        // Envoi du personnage vers le template.
        $this->set(compact('personnage'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Création d'un personnage vide.
        $personnage = $this->Personnages->newEmptyEntity();
        
        // Données envoyées depuis le formulaire de la page.
        if ($this->request->is('post')) {

            // Données du formulaire utilisées dans le personnage.
            $personnage = $this->Personnages->patchEntity($personnage, $this->request->getData());

            // Sauvegarde du personnage
            if ($this->Personnages->save($personnage)) {

                // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                $this->Flash->success(__('Le personnage {0} a été sauvegardé avec succès.', $personnage->nom));

                // Redirection vers l'index des personnages.
                return $this->redirect(['action' => 'index']);
            }

            // Avertissement du développeur et de l'utilisateur que le personnage n'a pas pu être sauvegardé.
            $this->log("Le personnage n'a pas pu être sauvegardé", LogLevel::ALERT, $personnage);
            $this->Flash->error(__('Le personnage {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $personnage->nom));
        }

        // Récupération des fandoms sous forme de query.
        $fandoms = $this->Personnages->fandoms->find('list', ['limit' => 200])->order(["nom" => "ASC"]);

        //  Envoi des données au template.
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
        // Récupération du personnage avec toutes ses associations
        $personnage = $this->Personnages->get($id, [
            'contain' => ['relations', 'fandoms'],
        ]);

        // Données envoyées depuis le formulaire de la page.
        if ($this->request->is(['patch', 'post', 'put'])) {

            // Données du formulaire utilisées dans le personnage.
            $personnage = $this->Personnages->patchEntity($personnage, $this->request->getData());

            // Sauvegarde du personnage
            if ($this->Personnages->save($personnage)) {

                // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
                $this->Flash->success(__('Le personnage {0} a été sauvegardé avec succès.', $personnage->nom));

                // Redirection vers l'index des personnages.
                return $this->redirect(['action' => 'index']);
            }

            // Avertissement du développeur et de l'utilisateur que le personnage n'a pas pu être sauvegardé.
            $this->log("Le personnage n'a pas pu être sauvegardé", LogLevel::ALERT, $personnage);
            $this->Flash->error(__('Le personnage {0} n\'a pas pu être sauvegardé. Veuillez réessayer.', $personnage->nom));
        }

        // Récupération des fandoms sous forme de query.
        $fandoms = $this->Personnages->fandoms->find('list', ['limit' => 200])->order(["nom" => "ASC"]);

        //  Envoi des données au template.
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
        // Vérification que la page est appelé depuis un formulaire ou un bouton de suppression.
        $this->request->allowMethod(['post', 'delete']);

        // Récupération du personnage à supprimer.
        $personnage = $this->Personnages->get($id);

        // Suppression logique (date de suppression valorisée).
        $personnage = $this->Personnages->patchEntity($personnage, [
            "suppression_date" => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s'),
            "update_date" => FrozenTime::now("Europe/Paris")->format("Y-m-d H:i:s"),
        ]);

        // Sauvegarde du personnage
        if ($this->Personnages->save($personnage)) {

            // Aucune erreur de sauvegarde, avertissement de l'utilisateur de ce succes.
            $this->Flash->success(__('Le personnage {0} a été supprimé avec succès.', $personnage->nom));

        } else {

            // Avertissement du développeur et de l'utilisateur que le personnage n'a pas pu être supprimé.
            $this->log("Le personnage n'a pas pu être supprimé.", LogLevel::ALERT, $personnage);
            $this->Flash->error(__('Le personnage {0} n\'a pu être supprimé. Veuillez réessayer.', $personnage->nom));
        }

        // Redirection vers l'index des personnages.
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions du personnage cliqué.
     * 
     * @param string|null $id Personnage id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null){
        //Nettoyage à vide du champ fanfictions dans la session.
        $this->request->getSession()->write("fanfictions");

        // Paramètres set avec les données du personnage.
        $params = [];
        $params["filters"]["fields"]["personnages"] = $id;
        $params["filters"]["not"]["personnages"] = true;
        $params["filters"]["operator"]["personnages"] = "AND";
        
        // Paramètres enregistrés dans la session.
        $this->writeSession("fanfictions", $params);

        // Redirection vers l'index des fanfictions.
        $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Fanfictions", "action" => "index"]);
    }
}
