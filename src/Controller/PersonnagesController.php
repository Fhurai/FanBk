<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Personnages Controller
 *
 * @property \App\Model\Table\PersonnagesTable $Personnages
 * @method \App\Model\Entity\Personnage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PersonnagesController extends AppController implements ObjectControllerInterface
{
    /**
     * Méthode qui va vérifier que l'entité en cours de création/édition n'existe pas déjà.
     *
     * @param array $data Les données du formulaire.
     * @return boolean Indication que le personnage existe déjà ou non.
     */
    public function exist(array $data): bool
    {
        return $this->Personnages->find()->where(["nom LIKE" => "%" . $data["nom"] . "%", "fandom" => $data["fandom"]])->count() > 0;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        parent::index();
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
        parent::view($id);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        parent::add();

        // Récupération des fandoms sous forme de query.
        $fandoms = $this->Personnages->fandoms->find('list', ['limit' => 200])->order(["nom" => "ASC"]);

        //  Envoi des données au template.
        $this->set(compact('fandoms'));
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
        parent::edit($id);

        // Récupération des fandoms sous forme de query.
        $fandoms = $this->Personnages->fandoms->find('list', ['limit' => 200])->order(["nom" => "ASC"]);

        //  Envoi des données au template.
        $this->set(compact('fandoms'));
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
        parent::delete($id);
    }

    /**
     * Restore method
     *
     * @param string|null $id Personnage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function restore($id = null)
    {
        parent::restore($id);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions du personnage cliqué.
     * 
     * @param string|null $id Personnage id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null)
    {
        parent::filterRedirect($id);
    }
}
