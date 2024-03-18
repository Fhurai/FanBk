<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Relations Controller
 *
 * @property \App\Model\Table\RelationsTable $Relations
 * @method \App\Model\Entity\Relation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RelationsController extends AppController implements ObjectControllerInterface
{

    /**
     * Méthode qui définit si une relation existe avec les personnages.
     *
     * @param array $data Les données du formulaire.
     * @return bool Indication si la relation existe.
     */
    public function exist(array $data): bool
    {
        // Récupération du tableau des noms de personnages de la relation à vérifier.
        $personnages = array_map(function ($id) {
            return $this->Personnages->get($id)->nom;
        }, $data["personnages"]);

        // Tri des personnages par ordre alphabétique dans la relation.
        usort($personnages, function ($perso1, $perso2) {
            return strcmp(strtolower($perso1), strtolower($perso2));
        });

        // Retourne si une relation existe avec le nom de tous ces personnages.
        return $this->Relations->find()->where(["nom" => implode(" / ", $personnages)])->count() > 0;
    }

    /**
     * Méthode qui importe les options nécessaires au formulaire des relations.
     *
     * @return void
     */
    public function importFormOptions(): void
    {
        // Récupération des personnages sous forme de query, groupés par nom de fandom.
        $personnages = $this->Personnages->find('list', ["keyField" => "id", "valueField" => "nom", "groupField" => "fandom_obj.nom"])->contain(["fandoms"])->order(["fandoms.nom" =>  "ASC", "personnages.nom" => "ASC"]);

        //  Envoi des données au template.
        $this->set(compact('personnages'));
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
     * @param string|null $id Relation id.
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
     * @param string|null $id Relation id.
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
     * @param string|null $id Relation id.
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
     * @param string|null $id Relation id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function restore($id = null)
    {
        // Appel méthode parente.
        parent::restore($id);
    }

    /**
     * Méthode pour rediriger l'utilisateur vers les fanfictions de la relation cliquée.
     * @param string|null $id Relation id.
     * @return \Cake\Http\Response|null|void Redirects to Fanfictions index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function filterRedirect($id = null)
    {
        // Appel méthode parente.
        parent::filterRedirect($id);
    }
}
