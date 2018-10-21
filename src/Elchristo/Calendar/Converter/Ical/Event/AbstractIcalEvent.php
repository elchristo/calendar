<?php

namespace Elchristo\Calendar\Converter\Ical\Event;

use Elchristo\Calendar\Converter\ConvertibleEventInterface;
use Elchristo\Calendar\Model\Event\CalendarEventInterface;

/**
 * Abstract iCalendar event (VEVENT - RFC 2445)
 */
abstract class AbstractIcalEvent implements ConvertibleEventInterface
{
    /** @var string Timestamp format defined by RFC 2445 */
    const DTSTAMP_FORMAT = 'YmdTHisZ';

    /** @var string */
    const CRLF = "\r\n";

    /** @var boolean Encode string properties (SUMMARY, DESCRIPTION, ...) into utf-8 */
    private $encodeUtf8 = true;

    /** @var CalendarEventInterface Calendar event to convert */
    protected $event;

    /** @var string DTSTAMP */
    private $timestamp;

    /** @var string CREATED */
    private $created;

    /** @var string LAST-MODIFIED */
    private $lastModified;

    /** @var string SUMMARY */
    private $summary;

    /** @var string DESCRIPTION */
    private $description;

    /** @var string LOCATION */
    private $location;

    /** @var string LOCATION */
    private $categories;

    /** @var string CLASS */
    private $class = 'PUBLIC';

    /** @var string TRANSP (OPAQUE|TRANSPARENT) */
    private $transp = 'OPAQUE';

    /** @var string STATUS (TENTATIVE|CONFIRMED|CANCELLED) */
    private $status = 'CONFIRMED';

    /** @var string Event visibility (FREE|BUSY|OOF|TENTATIVE) */
    private $intendedStatus = 'BUSY';

    /** @var string optional unique identifier (UID) prefix */
    private $uidPrefix;

    /** @var array Additional options */
    protected $options = [];

    /**
     *
     * @param CalendarEventInterface $event Calendar event to convert
     */
    public function __construct(CalendarEventInterface $event)
    {
        $this->event = $event;
        $tsNow = (string) \time();
        $this->timestamp = $tsNow;
        $this->created = $tsNow;
        $this->lastModified = $tsNow;

        $this->summary = $this->event->getTitle();
        $this->description = $this->event->getDescription();
    }

    /**
     * Setter for additional options
     *
     * @param array $options
     * @return self
     *
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get additional options
     *
     * @return array $options
     *
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get a single option value by option name
     *
     * @param string $option Option to look for
     * @return mixed $option Option if exists, otherwise NULL
     */
    public function getOption($option)
    {
        return (\strlen($option) > 1 && \array_key_exists($option, $this->options))
            ? $this->options[$option]
            : null;
    }

    /**
     * Convert event into VEVENT format
     * @return string BEGIN:VEVENT ... END:VEVENT
     */
    public function asIcal()
    {
        return
            'BEGIN:VEVENT' . self::CRLF
            . 'UID:' . $this->getUid() . self::CRLF
            . $this->getDateTimeStartEnd() . self::CRLF
            . 'DTSTAMP:' . $this->getDtstamp() . self::CRLF
            . 'CLASS:' . $this->getClass() . self::CRLF
            . 'CREATED:' . $this->getCreated() . self::CRLF
            . 'DESCRIPTION:' . $this->getDescription() . self::CRLF
            . 'LAST-MODIFIED:' . $this->getLastModified() . self::CRLF
            . 'LOCATION:' . \utf8_encode($this->location) . self::CRLF
            . 'SEQUENCE:' . "0" . self::CRLF
            . 'STATUS:' . $this->getStatus() . self::CRLF
            . 'SUMMARY:' . $this->getSummary() . self::CRLF
            . 'CATEGORIES:' . $this->getCategories() . self::CRLF
            . 'TRANSP:' . $this->getTransp() . self::CRLF
            . 'X-MICROSOFT-CDO-INTENDEDSTATUS:' . $this->getIntendedStatus() . self::CRLF
            . 'END:VEVENT' . self::CRLF;
    }

    /**
     * Return unique identifier of VEVENT
     * @return string
     */
    public function getUid()
    {
        return $this->uidPrefix . $this->event->getUid();
    }

    /**
     * Return SUMMARY value
     * @return string
     */
    public function getSummary()
    {
        return ($this->encodeUtf8) ? \utf8_encode($this->summary) : $this->summary;
    }

    /**
     * Set SUMMARY value
     * @param string $summary value
     * @return self
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Return DESCRIPTION value
     * @return string
     */
    public function getDescription()
    {
        $description = \str_replace(self::CRLF, '\n', $this->description);
        return ($this->encodeUtf8) ? \utf8_encode($description) : $description;
    }

    /**
     * Set DESCRIPTION value
     * @param string $description value
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Return LOCATION value
     * @return string
     */
    public function getLocation()
    {
        return ($this->encodeUtf8) ? \utf8_encode($this->location) : $this->location;
    }

    /**
     * Set LOCATION value
     * @param string $location value
     * @return self
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Return LOCATION value
     * @return string
     */
    public function getCategories()
    {
        return ($this->encodeUtf8) ? \utf8_encode($this->categories) : $this->categories;
    }

    /**
     * Set CATEGORIES value
     * @param string $categories value
     * @return self
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Return CLASS value
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set CLASS value
     * @param string $class value
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Return TRANSP value
     * @return string
     */
    public function getTransp()
    {
        return $this->transp;
    }

    /**
     * Set TRANSP value
     * @param string $transp value
     * @return self
     */
    public function setTransp($transp)
    {
        $this->transp = $transp;
        return $this;
    }

    /**
     * Return STATUS value
     * @return string TENTATIVE|CONFIRMED|CANCELLED
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set STATUS value
     * @param string $status TENTATIVE|CONFIRMED|CANCELLED
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Return X-MICROSOFT-CDO-INTENDEDSTATUS value
     * @return string
     */
    public function getIntendedStatus()
    {
        return $this->intendedStatus;
    }

    /**
     * Set X-MICROSOFT-CDO-INTENDEDSTATUS value
     * @param string $status value
     * @return self
     */
    public function setIntendedStatus($status)
    {
        $this->intendedStatus = $status;
        return $this;
    }

    /**
     * Set optional prefix for unique event identifier
     * @param string $prefix
     * @return self
     */
    public function setUidPrefix($prefix)
    {
        $this->uidPrefix = $prefix;
        return $this;
    }

    /**
     * Return DTSTAMP value
     * @return string Timestamp en format YYYYMMDDTHHMMSSZ
     */
    protected function getDtstamp()
    {
        return $this->timestamp;
    }

    /**
     * Set DTSTAMP value
     * @param string $timestamp Timestamp (format YYYYMMDDTHHMMSSZ)
     * @return self
     */
    public function setDtstamp($timestamp)
    {
        if (true === $this->isValidTimestamp($timestamp)) {
            $this->timestamp = $timestamp;
        }

        return $this;
    }

    /**
     *
     * @return \DateTimeZone
     */
    public function getTimezone()
    {
        return $this->event->getStart()->getTimezone();
    }

    /**
     * Return CREATED value
     * @return string Timestamp (format YYYYMMDDTHHMMSSZ)
     */
    protected function getCreated()
    {
        return $this->created;
    }

    /**
     * Set CREATED value
     * @param string $timestamp Timestamp (format YYYYMMDDTHHMMSSZ)
     * @return self
     */
    public function setCreated($timestamp)
    {
        if (true === $this->isValidTimestamp($timestamp)) {
            $this->created = $timestamp;
        }

        return $this;
    }

    /**
     * Return LAST-MODIFIED value
     * @return string Timestamp (format YYYYMMDDTHHMMSSZ)
     */
    protected function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Set LAST-MODIFIED value
     * @param string $timestamp Timestamp (format YYYYMMDDTHHMMSSZ)
     * @return self
     */
    public function setLastModified($timestamp)
    {
        if (true === $this->isValidTimestamp($timestamp)) {
            $this->lastModified = $timestamp;
        }

        return $this;
    }

    /**
     * Return DTSTART and DTEND values
     *
     * @return string
     */
    protected function getDateTimeStartEnd()
    {
        $start = $this->event->getStart();
        $end = $this->event->getEnd();

        /*
         * Add one day to event duration for allday events to respect iCalendar/VEVENT standard (RFC2446)
         * @see http://tools.ietf.org/html/rfc2446
         */
        if (true === $this->event->isAlldayEvent() && $start != $end) {
            $end->add(new \DateInterval('P1D'));
        }

        $dtStart = $start->format('Ymd');
        $dtEnd = $end->format('Ymd');
        $tz = $this->getTimezone()->getName();

        if (false === $this->event->isAlldayEvent()) {
            $dtStart .= 'T' . $start->format('His');
            $dtEnd .= 'T' . $end->format('His');
        }

        return
            "DTSTART;TZID=" . $tz . ':' . $dtStart . self::CRLF
            . "DTEND;TZID=" . $tz . ':' . $dtEnd;
    }

    /**
     * Validate timestamp format (needs exactly 16 caracters), eg. 20130928T175328Z
     *
     * @param string $timestamp Timestamp string to validate
     * @return boolean
     */
    protected function isValidTimestamp($timestamp)
    {
        return (\strlen($timestamp) == 16 && 1 === \preg_match('/^(\d{8})(T)(\d{6})(Z)$/', $timestamp))
            ? true
            : false;
    }
}
