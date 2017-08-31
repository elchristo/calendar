# Changelog

## 0.6.0

 - add converter for "FullCalendar" events (see https://github.com/fullcalendar/fullcalendar)
 - fix psr-4 autoloading declaration and some phpdoc
 - upgrade vendor dependencies

## 0.5.0

 - Fix phpunit version in composer.json for compatibity reasons and upgrade vendor dependencies (6ddf01c)
 - Improved source identifier generation and added corresponding unit tests (8187133 d632375)

## 0.4.0

 - add default methods to source interface (db726e4)
 - some refactoring of tests (c20d8a0)
 - remove PHP 5.5 support (9c13cfa)
 - update some vendor dependencies

## 0.3.1

 - fix use of converter declared in configuration
 - fix converter tests
 - improve documentation (converters, ...)
 - update vendor packages (composer.lock)

## 0.3.0

 - configuration keys ("sources", "events") are not mandatory anymore (53c0dd7)
 - calendar builder configuration is now optional (0b741b8)
 - event sources, calendar events and converters can now be created by passing a classname (c655dae d94da67 b0b296b)
 - fix converter instances cache (0a2d598)
 - improved handling of services by internal container (4348960)
 - added and updated some unit tests
 - update vendor dependencies (54526b1)
 - update documentation

## 0.2.0

 - add unit tests (codeception)
 - fix transformation of events collection (to array)
 - missing of some (optional) configuration keys do not throw errors anymore
 - correct method name ("setPublished" --> "setPublic")
 - other small bug fixes and improvements

## 0.1.0

 - Add "iCalendar" converter (RFC2445) to convert calendars into VCALENDAR format
 - some documentation improvements

## 0.0.2

 - bug fixes and improvements

## 0.0.1

- Initial version
