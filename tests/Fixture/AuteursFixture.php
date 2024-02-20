<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AuteursFixture
 */
class AuteursFixture extends TestFixture
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
                'creation_date' => '2024-01-27 02:20:32',
                'update_date' => '2024-01-27 02:20:32',
                'suppression_date' => '2024-01-27 02:20:32',
            ],
        ];
        parent::init();
    }
}
