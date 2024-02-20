<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SeriesRelationsFixture
 */
class SeriesRelationsFixture extends TestFixture
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
                'relation_nom' => 'Lorem ipsum dolor sit amet',
                'series_id' => 1,
            ],
        ];
        parent::init();
    }
}
