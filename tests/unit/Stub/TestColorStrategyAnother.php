<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Service\Color\AbstractColorStrategy;

class TestColorStrategyAnother extends AbstractColorStrategy
{
    public function getColorCodeByEvent()
    {
        return '#000000';
    }
}
