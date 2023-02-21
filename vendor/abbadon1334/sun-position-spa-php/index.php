<?php

$autoload = include 'vendor/autoload.php';

$object = new SolarData\SolarData();

$object->setObserverPosition(39.742476, -105.1786, 1830.14);
$object->setObserverDate(2003, 10, 17);
$object->setObserverTime(12, 30, 30);
$object->setDeltaTime(67);
$object->setObserverTimezone(-7);
$object->calculate();
$object->calculateSunRiseTransitSet();

d($object->SunPosition);
