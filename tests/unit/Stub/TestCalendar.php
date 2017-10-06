<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Model\AbstractCalendar;
use Elchristo\Calendar\Test\unit\Stub\TestSource;

/**
 * calendar stub
 */
class TestCalendar extends AbstractCalendar
{
    public function init()
    {
        $this->addSource(
            TestSource::class,
            [
                'color_strategy' => 'MyColorStrategy'
            ]
        );

        return parent::init();
    }
}
