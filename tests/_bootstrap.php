<?php
// This is global bootstrap for autoloading

$autoload = __DIR__ . '/../vendor/autoload.php';
if (\is_file($autoload)) {
    $loader = require($autoload);
    $loader->add('Elchristo\\Calendar\\', __DIR__ . '/../src/');
}
