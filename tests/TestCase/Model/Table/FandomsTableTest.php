<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FandomsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FandomsTable Test Case
 */
class FandomsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FandomsTable
     */
    protected $Fandoms;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Fandoms',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Fandoms') ? [] : ['className' => FandomsTable::class];
        $this->Fandoms = $this->getTableLocator()->get('Fandoms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Fandoms);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FandomsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
