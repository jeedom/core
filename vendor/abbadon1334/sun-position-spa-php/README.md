# Sun-Position-SPA-php
SPA Sun Position Calc Library for PHP

PHPUNIT TEST PHP 5.6 - 7.0  ![Alt text](https://travis-ci.org/abbadon1334/sun-position-spa-php.svg?branch=master)

This library is based on the work of Ibrahim Reda and Afshin Andreas
(SPA) Solar Position Algorithm for Solar Radiation Applications ( 2008 National Renewable Energy Laboratory )

## Abstract from the original study
There have been many published articles describing solar position algorithms for solar radiation
applications. The best uncertainty achieved in most of these articles is greater than ±0.01 / in
calculating the solar zenith and azimuth angles. For some, the algorithm is valid for a limited
number of years varying from 15 years to a hundred years. This report is a step by step procedure
for implementing an algorithm to calculate the solar zenith and azimuth angles in the period from
the year -2000 to 6000, with uncertainties of ±0.0003°

### PHPUNIT TEST
Library test data vs Table A.4 of the original study

### C Source code for Solar Position Algorithm (SPA) 
http://www.nrel.gov/midc/spa/

### Requirements
 - PHP 5.6
 - PHP 7

### Composer install
```
composer require abbadon1334/sun-position-spa-php
```

### Simple Usage
```
$SD = new SolarData\SolarData();

/* ARGS : observer latitude, observer longitude, observer altitude */
$SD->setObserverPosition(39.742476,-105.1786,1830.14);

/* ARGS : Observer Date : Year, Month, Day */
$SD->setObserverDate(2003, 10, 17);

/* ARGS : Observer Time : Hours, Minutes, Seconds */
$SD->setObserverTime(12, 30,30);

/* ARGS : difference in seconds between the Earth rotation time and the Terrestrial Time (TT) */
$SD->setDeltaTime(67);
/* ARGS : Observer Timezone */
$SD->setObserverTimezone(-7);

/* ARGS : Observer mean pressure in Millibar */
$SD->object->setObserverAtmosphericPressure(820);

/* ARGS : Observer mean temperature in Celsius */
$SD->object->setObserverAtmosphericTemperature(11.0);

/* calculate sun position */
$SunPosition = $SD->calculate();
```
#### Available attributes after calculate() :

*I know this attributes names are not so ortodox.*
*Formulas that are present in the original document are really complex and using the same name for variables is a big aid for debugging*

* `L°` Earth heliocentric longitude (degrees)
* `B°` Earth heliocentric latitude (degrees)
* `R` Earth radius vector, R (in Astronomical Units, AU)
* `Θ°` geocentric longitude (degrees)
* `β°` geocentric longitude (degrees)
* `X` nutation in longitude and obliquity
* `ε°` true obliquity of the ecliptic (degrees)
* `Δτ` aberration correction (degrees)
* `λ°` apparent sun longitude (degrees)
* `ν°` apparent sidereal time at Greenwich (degrees)
* `ν0°` apparent mean sidereal time at Greenwich (degrees)
* `α°` geocentric sun right ascension (degrees)
* `α´°` topocentric sun right ascension (degrees)
* `δ°` geocentric sun declination (degrees)
* `δ´°` topocentric sun declination (degrees)
* `H°` Observer hour angle (degrees)
* `H´°` topocentric hour angle (degrees)
* `ξ°` equatorial horizontal parallax of the sun (degrees)
* `Z°` topocentric zenith angle (degrees)
* `Γ°` topocentric astronomers azimuth angle (degrees)
* `Φ°` topocentric azimuth angle, M for navigators and solar radiation users (in degrees)
* `e0°` topocentric elevation angle without atmospheric refraction (in degrees)
* `e°` topocentric elevation angle (in degrees)
* `Eot` Equation Of Time

*Example to get angle H° - Observer hour angle (degrees)*
```
$SD = new SolarData\SolarData();

/* ARGS : observer latitude, observer longitude, observer altitude */
$SD->setObserverPosition(39.742476,-105.1786,1830.14);

/* ARGS : Observer Date : Year, Month, Day */
$SD->setObserverDate(2003, 10, 17);

/* ARGS : Observer Time : Hours, Minutes, Seconds */
$SD->setObserverTime(12, 30,30);

/* ARGS : difference in seconds between the Earth rotation time and the Terrestrial Time (TT) */
$SD->setDeltaTime(67);
/* ARGS : Observer Timezone */
$SD->setObserverTimezone(-7);

/* ARGS : Observer mean pressure in Millibar */
$SD->object->setObserverAtmosphericPressure(820);

/* ARGS : Observer mean temperature in Celsius */
$SD->object->setObserverAtmosphericTemperature(11.0);

/* calculate sun position */
$SunPosition = $SD->calculate();
```
to get H° Observer hour angle (degrees)
```
echo $SunPosition->H°;
```

*Example to get fraction day for sunrise - transit - sunset *
```

$SD = new SolarData\SolarData();

/* ARGS : observer latitude, observer longitude, observer altitude */
$SD->setObserverPosition(39.742476,-105.1786,1830.14);

/* ARGS : Observer Date : Year, Month, Day */
$SD->setObserverDate(2003, 10, 17);

/* ARGS : Observer Time : Hours, Minutes, Seconds */
$SD->setObserverTime(12, 30,30);

/* ARGS : difference in seconds between the Earth rotation time and the Terrestrial Time (TT) */
$SD->setDeltaTime(67);
/* ARGS : Observer Timezone */
$SD->setObserverTimezone(-7);

/* ARGS : Observer mean pressure in Millibar */
$SD->object->setObserverAtmosphericPressure(820);

/* ARGS : Observer mean temperature in Celsius */
$SD->object->setObserverAtmosphericTemperature(11.0);

/* calculate sun position and calculate sun rise transit set angles 
ARGS : true = call ->calculate()
*/
$SunPosition = $SD->calculateSunRiseTransitSet(true);

$SunRiseDayFraction = $SunPosition->DayFractionSunrise;
$TransitDayFraction = $SunPosition->DayFractionTransit;
$SunsetDayFraction  = $SunPosition->DayFractionSunset;

```
### Get Sun Incidence Angle
```

$SD = new SolarData\SolarData();

/* ARGS : observer latitude, observer longitude, observer altitude */
$SD->setObserverPosition(39.742476,-105.1786,1830.14);

/* ARGS : Observer Date : Year, Month, Day */
$SD->setObserverDate(2003, 10, 17);

/* ARGS : Observer Time : Hours, Minutes, Seconds */
$SD->setObserverTime(12, 30,30);

/* ARGS : difference in seconds between the Earth rotation time and the Terrestrial Time (TT) */
$SD->setDeltaTime(67);
/* ARGS : Observer Timezone */
$SD->setObserverTimezone(-7);

/* ARGS : Observer mean pressure in Millibar */
$SD->object->setObserverAtmosphericPressure(820);

/* ARGS : Observer mean temperature in Celsius */
$SD->object->setObserverAtmosphericTemperature(11.0);

```
* no need of calling calculate *
```
/* ARGS : tilt angle from horizontal plane, rotation angle from real south  */
$Surface2SunAngleOfIncidence = $SD->getSurfaceIncidenceAngle(30,-10)
```
