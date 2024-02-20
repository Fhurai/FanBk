<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FanfictionsLiensTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FanfictionsLiensTable Test Case
 */
class FanfictionsLiensTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FanfictionsLiensTable
     */
    protected $FanfictionsLiens;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.FanfictionsLiens',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FanfictionsLiens') ? [] : ['className' => FanfictionsLiensTable::class];
        $this->FanfictionsLiens = $this->getTableLocator()->get('FanfictionsLiens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FanfictionsLiens);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FanfictionsLiensTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
