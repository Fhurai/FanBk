<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FanfictionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FanfictionsTable Test Case
 */
class FanfictionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FanfictionsTable
     */
    protected $Fanfictions;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Fanfictions',
        'app.Fandoms',
        'app.Personnages',
        'app.Relations',
        'app.Tags',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Fanfictions') ? [] : ['className' => FanfictionsTable::class];
        $this->Fanfictions = $this->getTableLocator()->get('Fanfictions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Fanfictions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FanfictionsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
