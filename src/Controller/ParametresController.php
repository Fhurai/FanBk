<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Parametres Controller
 *
 * @method \App\Model\Entity\Parametre[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ParametresController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Initialisation de la variable des paramètres.
        $parametres = ["Classement" => [], "Note" => []];

        // Si le fichier config existe, récupération des paramètres.
        if (Configure::check("parametres")) $parametres = Configure::read("parametres");

        // Envoi des paramètres à la page.
        $this->set(compact('parametres'));
    }

    /**
     * Edit method
     * 
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     */
    public function edit(?string $id = null){
        // Initialisation de la variable des paramètres.
        $parametres = ["Classement" => [], "Note" => []];

        // Si le fichier config existe, récupération des paramètres.
        if (Configure::check("parametres")) $parametres = Configure::read("parametres");

        if($this->request->is("post")){
            
            try{
                Configure::write("parametres", $this->request->getData());
                Configure::dump("parametres", "default", ["parametres"]);

                $this->Flash->success(__("Succès de la sauvegarde des paramètres."));

                $this->redirect(["plugin" => false, "prefix" => false, "controller" => "Pages", "action"=> "home"]);
            }catch(\Exception $e){
                Log::write("error", $e->getMessage());
                $this->Flash->error(__("Erreur lors de la sauvegarde des paramètres. Veuillez réessayer."));
            }
        }

        // Envoi des paramètres à la page.
        $this->set(compact('parametres'));
    }
}
