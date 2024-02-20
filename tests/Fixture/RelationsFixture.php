<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RelationsFixture
 */
class RelationsFixture extends TestFixture
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
                'creation_date' => '2024-02-03 11:30:16',
                'update_date' => '2024-02-03 11:30:16',
                'suppression_date' => '2024-02-03 11:30:16',
            ],
        ];
        parent::init();
    }
}
