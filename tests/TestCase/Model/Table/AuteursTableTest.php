<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AuteursTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AuteursTable Test Case
 */
class AuteursTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AuteursTable
     */
    protected $Auteurs;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Auteurs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Auteurs') ? [] : ['className' => AuteursTable::class];
        $this->Auteurs = $this->getTableLocator()->get('Auteurs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Auteurs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AuteursTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
