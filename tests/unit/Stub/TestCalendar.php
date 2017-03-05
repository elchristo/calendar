<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Model\AbstractCalendar;

/**
 * calendar stub
 */
class TestCalendar extends AbstractCalendar
{
    public function init()
    {
        $this->addSource(
            'TestSource',
            [
                'color_strategy' => 'MyColorStrategy'
            ]
        );

        return parent::init();
    }
}
