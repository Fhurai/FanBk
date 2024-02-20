<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LangagesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LangagesTable Test Case
 */
class LangagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LangagesTable
     */
    protected $Langages;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Langages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Langages') ? [] : ['className' => LangagesTable::class];
        $this->Langages = $this->getTableLocator()->get('Langages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Langages);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\LangagesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
