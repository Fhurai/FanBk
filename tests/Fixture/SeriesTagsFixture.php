<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SeriesTagsFixture
 */
class SeriesTagsFixture extends TestFixture
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
                'tag_nom' => 'Lorem ipsum dolor sit amet',
                'series_id' => 1,
            ],
        ];
        parent::init();
    }
}
