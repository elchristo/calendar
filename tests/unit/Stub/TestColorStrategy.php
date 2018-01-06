<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Service\Color\AbstractColorStrategy;

class TestColorStrategy extends AbstractColorStrategy
{
    public function getColorCodeByEvent()
    {
        $hash = \md5($this->getEvent()->getUid());

	$r = \hexdec(substr($hash, 8, 2));
        $g = \hexdec(substr($hash, 4, 2));
        $b = \hexdec(substr($hash, 0, 2));

        if ($r < 128) {
            $r += 128;
        }

        if ($g < 128) {
            $g += 128;
        }

        if ($b < 128) {
            $b += 128;
        }

        return '#' . \dechex($r) . \dechex($g) . \dechex($b);
    }
}
