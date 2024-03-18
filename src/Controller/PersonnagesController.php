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
        // Retourne si des personnages avec le même nom existe pour le même fandom existent.
        // (Des personnages peuvent avoir le même nom mais pas dans le même fandom).
        return $this->Personnages->find()->where(["nom LIKE" => "%" . $data["nom"] . "%", "fandom" => $data["fandom"]])->count() > 0;
    }

    /**
     * Méthode qui importe les options nécessaires au formulaire de personnages.
     *
     * @return void
     */
    public function importFormOptions(): void
    {
        // Récupération des fandoms sous forme de liste.
        $fandoms = $this->Fandoms->find('list')->order(["nom" => "ASC"])->toArray();

        //  Envoi des données au template.
        $this->set(compact('fandoms'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Appel méthode parente.
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
        // Appel méthode parente.
        parent::view($id);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Appel méthode parente.
        parent::add();
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
        // Appel méthode parente.
        parent::edit($id);
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
        // Appel méthode parente.
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
        // Appel méthode parente.
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
        // Appel méthode parente.
        parent::filterRedirect($id);
    }
}
