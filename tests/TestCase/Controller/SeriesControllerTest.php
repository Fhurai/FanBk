<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\SeriesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\SeriesController Test Case
 *
 * @uses \App\Controller\SeriesController
 */
class SeriesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Series',
        'app.Fanfictions',
    ];
}
