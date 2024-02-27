<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 * 
 * @property \App\Model\Table\FanfictionsTable $Fanfictions
 * @property \App\Model\Table\FandomsTable $Fandoms
 * @property \App\Model\Table\AuteursTable $Auteurs
 * @property \App\Model\Table\RelationsTable $Relations
 * @property \App\Model\Table\PersonnagesTable $Personnages
 * @property \App\Model\Table\LangagesTable $Langages
 * @property \App\Model\Table\LiensTable $Liens
 * @property \App\Model\Table\TagsTable $Tags
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\SeriesTable $Series
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        // Add this line to check authentication result and lock your site
        $this->loadComponent('Authentication.Authentication');

        $this->Auteurs = $this->fetchModel("Auteurs");
        $this->Fandoms = $this->fetchModel("Fandoms");
        $this->Fanfictions = $this->fetchModel("Fanfictions");
        $this->Langages = $this->fetchModel("Langages");
        $this->Liens = $this->fetchModel("FanfictionsLiens");
        $this->Personnages = $this->fetchModel("Personnages");
        $this->Relations = $this->fetchModel("Relations");
        $this->Tags = $this->fetchModel("Tags");
        $this->Users = $this->fetchModel("Users");
        $this->Series = $this->fetchModel("Series");
    }

    /**
     * Méthode qui écrit un tableau dans la session.
     * @param string $cle
     * @param array $array
     */
    protected function writeSession(string $cle, array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value))
                $this->writeSession($cle . ".$key", $value);
            else {
                $this->request->getSession()->write($cle . ".$key", $value);
            }
        }
    }
}
