<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'username' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'is_admin' => 1,
                'birthday' => '2024-02-19 21:44:54',
                'creation_date' => '2024-02-19 21:44:54',
                'update_date' => '2024-02-19 21:44:54',
                'suppression_date' => '2024-02-19 21:44:54',
            ],
        ];
        parent::init();
    }
}
