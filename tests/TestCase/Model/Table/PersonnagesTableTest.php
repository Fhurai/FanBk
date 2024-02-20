<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PersonnagesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PersonnagesTable Test Case
 */
class PersonnagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PersonnagesTable
     */
    protected $Personnages;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Personnages',
        'app.Relations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Personnages') ? [] : ['className' => PersonnagesTable::class];
        $this->Personnages = $this->getTableLocator()->get('Personnages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Personnages);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\PersonnagesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
