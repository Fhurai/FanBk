<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RelationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RelationsTable Test Case
 */
class RelationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RelationsTable
     */
    protected $Relations;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Relations',
        'app.Personnages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Relations') ? [] : ['className' => RelationsTable::class];
        $this->Relations = $this->getTableLocator()->get('Relations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Relations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\RelationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
