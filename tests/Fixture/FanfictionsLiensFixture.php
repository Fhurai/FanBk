<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FanfictionsLiensFixture
 */
class FanfictionsLiensFixture extends TestFixture
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
                'lien' => 'Lorem ipsum dolor sit amet',
                'fanfiction' => 1,
                'creation_date' => '2024-02-03 19:37:49',
                'update_date' => '2024-02-03 19:37:49',
                'suppression_date' => '2024-02-03 19:37:49',
            ],
        ];
        parent::init();
    }
}
