<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use App\Controller\AppController;

/**
 * Series Controller
 *
 * @property \App\Model\Table\SeriesTable $Series
 */
class SeriesController extends AppController implements ObjectControllerInterface
{
    /**
     * Méthode qui va vérifier que l'entité en cours de création/édition n'existe pas déjà.
     *
     * @param array $data Les données du formulaire.
     * @return boolean Indication que le fandom existe déjà ou non.
     */
    public function exist(array $data): bool
    {
        // Retourne si une série existe déjà avec ce nom.
        return $this->Series->find()->where(["nom LIKE" => "%" . $data["nom"] . "%"])->count() > 0;
    }

    /**
     * Méthode d'importation des options nécessaires au formulaire de fanfictions.
     *
     * @return void
     */
    public function importFormOptions(): void
    {
        // Récupération des fanfictions pouvant être utilisées dans une série.
        $fanfictions = $this->Fanfictions->find("list")->order("nom");

        // Récupération des paramètres de l'appli (classement & note).
        $parametres = Configure::check("parametres") ? Configure::read("parametres") : [];

        // Envoi de la liste des fanfictions et des paramètres au template.
        $this->set(compact("fanfictions", "parametres"));

        // Envoi des tableaux de correspondance dans le template.
        $this->Url->setArrays();
    }

    /**
     * Méthode qui initialiser le controller.
     *
     * @return void
     */
    public function initialize(): void
    {
        // Appel méthode parente.
        parent::initialize();

        // Récupération des paramètres de la session pour les séries.
        $params = $this->request->getSession()->read("series");

        // Envoi des paramètres au template.
        $this->set(compact("params"));
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
     * Page d'édition d'une série
     * 
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // Appel méthode parente.
        parent::view($id);
    }

    /**
     * Page d'ajout d'une série
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function add()
    {
        // Appel méthode parente.
        parent::add();
    }

    /**
     * Page d'édition d'une série
     * 
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
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
     * @param string|null $id Series id.
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
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function restore($id = null)
    {
        // Appel méthode parente.
        parent::restore($id);
    }

    /**
     * Note method
     *
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function note($id = null)
    {
        // Appel méthode parente.
        parent::note($id);
    }

    /**
     * Denote method
     *
     * @param string|null $id Series id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function denote($id = null)
    {
        // Appel méthode parente.
        parent::denote($id);
    }

    /**
     * Méthode pour réinitialiser la liste des séries.
     *
     * @return \Cake\Http\Response Redirects to series index page.
     */
    public function reinitialize()
    {
        // Appel méthode parente.
        parent::reinitialize();
    }
}
