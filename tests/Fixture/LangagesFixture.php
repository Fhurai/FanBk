<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LangagesFixture
 */
class LangagesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'nom' => 'Lorem ipsum dolor sit amet',
                'abbreviation' => 'Lo',
                'creation_date' => '2024-01-27 02:20:38',
                'update_date' => '2024-01-27 02:20:38',
                'suppression_date' => '2024-01-27 02:20:38',
            ],
        ];
        parent::init();
    }
}
