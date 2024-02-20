<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PersonnagesFixture
 */
class PersonnagesFixture extends TestFixture
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
                'fandom' => 1,
                'creation_date' => '2024-02-03 11:30:08',
                'update_date' => '2024-02-03 11:30:08',
                'suppression_date' => '2024-02-03 11:30:08',
            ],
        ];
        parent::init();
    }
}
