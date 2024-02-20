<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SeriesFandomsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SeriesFandomsTable Test Case
 */
class SeriesFandomsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SeriesFandomsTable
     */
    protected $SeriesFandoms;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SeriesFandoms',
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
        $config = $this->getTableLocator()->exists('SeriesFandoms') ? [] : ['className' => SeriesFandomsTable::class];
        $this->SeriesFandoms = $this->getTableLocator()->get('SeriesFandoms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SeriesFandoms);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SeriesFandomsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SeriesFandomsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
