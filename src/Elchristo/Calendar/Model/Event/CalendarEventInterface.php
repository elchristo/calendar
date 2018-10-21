<?php

namespace Elchristo\Calendar\Model\Event;

use \DateTime;

/**
 * Interface to be implemented by calendar events
 */
interface CalendarEventInterface
{
    public function getId();

    public function getUid();

    public function getTitle();

    public function setTitle($title);

    public function getTitleShort();

    public function setTitleShort($titleShort);

    public function getDescription();

    public function setDescription($description);

    public function isPublic();

    public function isAlldayEvent();

    public function getStart();

    public function getEnd();

    public function setStart(DateTime $dt);

    public function setEnd(DateTime $dt);

    public function getType();

    public function setType($type);

    public function getCreationDate();

    public function getLastModificationDate();
}
