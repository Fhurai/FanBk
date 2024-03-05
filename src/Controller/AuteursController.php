<?php

declare(strict_types=1);

namespace App\Controller;

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
        return $this->Auteurs->find()->where(["nom" => $data["nom"]])->count() > 0;
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
     * @param string|null $id Auteur id.
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
        parent::edit($id);
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
        parent::delete($id);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions de l'auteur cliqué.
     * @param string|null $id Auteur id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null)
    {
        parent::filterRedirect($id);
    }
}
