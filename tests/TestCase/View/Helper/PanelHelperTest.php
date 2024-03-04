<?php
declare(strict_types=1);

namespace App\Test\TestCase\View\Helper;

use App\View\Helper\FlyPanelHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\PanelHelper Test Case
 */
class PanelHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\View\Helper\PanelHelper
     */
    protected $Panel;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Panel = new FlyPanelHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Panel);

        parent::tearDown();
    }
}
