<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\FanfictionsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\FanfictionsController Test Case
 *
 * @uses \App\Controller\FanfictionsController
 */
class FanfictionsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Fanfictions',
        'app.Auteurs',
        'app.Langages',
        'app.Liens',
        'app.Fandoms',
        'app.Personnages',
        'app.Relations',
        'app.Tags',
    ];
}
