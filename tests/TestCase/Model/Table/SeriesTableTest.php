<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SeriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SeriesTable Test Case
 */
class SeriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SeriesTable
     */
    protected $Series;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Series',
        'app.Fanfictions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Series') ? [] : ['className' => SeriesTable::class];
        $this->Series = $this->getTableLocator()->get('Series', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Series);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test newEmptyEntity method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTable::newEmptyEntity()
     */
    public function testNewEmptyEntity(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findActive method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTable::findActive()
     */
    public function testFindActive(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findInactive method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTable::findInactive()
     */
    public function testFindInactive(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findNotNoted method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTable::findNotNoted()
     */
    public function testFindNotNoted(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getWithAssociations method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTable::getWithAssociations()
     */
    public function testGetWithAssociations(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findSearch method
     *
     * @return void
     * @uses \App\Model\Table\SeriesTable::findSearch()
     */
    public function testFindSearch(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
