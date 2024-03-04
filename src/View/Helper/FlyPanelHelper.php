<?php

declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Panel helper
 */
class FlyPanelHelper extends Helper
{

    use StringTemplateTrait;

    public $helpers = ['Html'];

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [
        "errorClass" => "error",
        "templates" => [
            "panelBeginOpen" => "<div class='flypanel'><div class='flybtn'><hr/><label for='flypanel btn {{id}}'>+</label><input id='flypanel btn {{id}}' type='checkbox' checked='checked'/></div><div class='panel'>",
            "panelBeginClosed" => "<div class='flypanel'><div class='flybtn'><hr/><label for='flypanel btn {{id}}'>+</label><input id='flypanel btn {{id}}' type='checkbox'/></div><div class='panel'>",
            "panelEnd" => "</div></div>"
        ]
    ];

    /**
     * Méthode pour injecter la première partie du panel
     *
     * @param boolean $bool Indication si le panneau est fermé ou ouvert.
     * @param array $options Options pour le panneau.
     * @return void
     */
    public function panelBegin(bool $bool, array $options)
    {
        // Si le panneau est ouvert, affichage de la partie permettant d'avoir le panneau ouvert.
        if ($bool)
            echo $this->formatTemplate("panelBeginOpen", $options);

        else
            // Si le panneau n'est pas ouvert, affichage de la partie permettant d'avoir le panneau fermé.
            echo $this->formatTemplate("panelBeginClosed", $options);
    }

    /**
     * Méthode pour injecter la deuxieme partie du panel.
     *
     * @return void
     */
    public function panelEnd()
    {
        // Affichage de la fin du panneau et du css.
        echo $this->getTemplates("panelEnd");
        echo $this->Html->css("fly/panel");
    }
}
