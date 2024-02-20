<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SeriesTagsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SeriesTagsTable Test Case
 */
class SeriesTagsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SeriesTagsTable
     */
    protected $SeriesTags;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SeriesTags',
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
        $config = $this->getTableLocator()->exists('SeriesTags') ? [] : ['className' => SeriesTagsTable::class];
        $this->SeriesTags = $this->getTableLocator()->get('SeriesTags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SeriesTags);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTagsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTagsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
