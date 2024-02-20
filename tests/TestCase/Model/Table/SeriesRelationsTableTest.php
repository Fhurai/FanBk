<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SeriesRelationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SeriesRelationsTable Test Case
 */
class SeriesRelationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SeriesRelationsTable
     */
    protected $SeriesRelations;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SeriesRelations',
        'app.Series',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SeriesRelations') ? [] : ['className' => SeriesRelationsTable::class];
        $this->SeriesRelations = $this->getTableLocator()->get('SeriesRelations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SeriesRelations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SeriesRelationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SeriesRelationsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
