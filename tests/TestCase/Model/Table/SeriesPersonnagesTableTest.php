<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SeriesPersonnagesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SeriesPersonnagesTable Test Case
 */
class SeriesPersonnagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SeriesPersonnagesTable
     */
    protected $SeriesPersonnages;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SeriesPersonnages',
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
        $config = $this->getTableLocator()->exists('SeriesPersonnages') ? [] : ['className' => SeriesPersonnagesTable::class];
        $this->SeriesPersonnages = $this->getTableLocator()->get('SeriesPersonnages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SeriesPersonnages);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SeriesPersonnagesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SeriesPersonnagesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
