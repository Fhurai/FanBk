<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\UrlComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\UrlComponent Test Case
 */
class UrlComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\UrlComponent
     */
    protected $Url;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Url = new UrlComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Url);

        parent::tearDown();
    }
}
