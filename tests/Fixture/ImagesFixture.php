<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ImagesFixture
 */
class ImagesFixture extends TestFixture
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
                'left' => 1,
                'top' => 1,
                'width' => 1,
                'height' => 1,
                'file_input' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
