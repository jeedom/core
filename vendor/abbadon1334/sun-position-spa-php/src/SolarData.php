<?php

// The MIT License (MIT)
//
// Copyright (c) 2017 Francesco Danti <fdanti@gmail.com>
//
// =============================================================================
//
// Formulas and Tests by :
//
// NREL/TP-560-34302
// Solar Position Algorithm for Solar Radiation Applications
// Revised January 2008
//
// Ibrahim Reda and Afshin Andreas
// Prepared under Task No. WU1D5600
//
// National Renewable Energy Laboratory
// 1617 Cole Boulevard
// Golden, Colorado 80401-3393
// NREL is a U.S. Department of Energy Laboratory
// Operated by Midwest Research Institute • Battelle • Bechtel
// Contract No. DE-AC36-99-GO10337
//
// There have been many published articles describing solar position algorithms
// for solar radiation applications. The best uncertainty achieved in most of
// these articles is greater than ±0.01 / in calculating the solar zenith and
// azimuth angles. For some, the algorithm is valid for a limited number of years
// varying from 15 years to a hundred years. This report is a step by step
// procedure for implementing an algorithm to calculate the solar zenith and
// azimuth angles in the period from the year -2000 to 6000, with uncertainties
// of ±0.0003 / . The algorithm is described by Jean Meeus [3]. This report is
// written in a step by step format to simplify the complicated steps described
// in the book, with a focus on the sun instead of the planets and stars in
// general. It also introduces some changes to accommodate for solar radiation
// applications. The changes include changing the direction of measuring azimuth
// angles to be measured from north and eastward instead of being measured from
// south and eastward, and the direction of measuring the observer’s geographical
// longitude to be measured as positive eastward from Greenwich meridian instead
// of negative. This report also includes the calculation of incidence angle for
// a surface that is tilted to any horizontal and vertical angle, as described
// by Iqbal [4].
//
// =============================================================================
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.

namespace SolarData;

use SolarData\Observer\Observer;

/**
 * Class SolarData.
 */
class SolarData
{
    private $Observer;

    /**
     * @var SunPosition
     */
    public $SunPosition;
    public $DEBUG = false;

    public function __construct()
    {
        $this->Observer = new Observer();
    }

    public function setDebug($active)
    {
        $this->DEBUG = $active;
    }

    public function getObserver()
    {
        return $this->Observer;
    }

    public function setObserverPosition($latitude, $longitude, $altitude)
    {
        $this->Observer->setPosition($latitude, $longitude, $altitude);
    }

    public function setObserverDate($Year, $Month, $Day)
    {
        $this->Observer->setDate($Year, $Month, $Day);
    }

    public function setObserverTime($Hour, $Minute, $Second = 0)
    {
        $this->Observer->setTime($Hour, $Minute, $Second);
    }

    public function setObserverTimezone($TZ)
    {
        $this->Observer->setTimezone($TZ);
    }

    public function setDeltaTime($ΔT)
    {
        $this->Observer->setDeltaTime($ΔT);
    }

    public function setObserverAtmosphericPressure($Pressure)
    {
        $this->Observer->setAtmosphericPressure($Pressure);
    }

    public function setObserverAtmosphericTemperature($Temperature)
    {
        $this->Observer->setAtmosphericTemperature($Temperature);
    }

    /*
     * @return SunPosition
     */
    public function calculate()
    {
        $this->Observer->calculate();
        $this->SunPosition = new SunPosition();
        $this->SunPosition->DEBUG = $this->DEBUG;
        $this->SunPosition->setObserver($this->Observer);
        $this->SunPosition->calculate();

        return $this->SunPosition;
    }

    public function calculateSunRiseTransitSet($recalculate = false)
    {
        if ($recalculate) {
            $this->calculate();
        }
        $this->SunPosition->calcSunRiseTransitSet();

        return $this->SunPosition;
    }

    public function getEquationOfTime()
    {
        $this->calculate();

        return $this->SunPosition->getEquationOfTime();
    }

    public function getSurfaceIncidenceAngle($TiltDegreesFromHorizontalPlane, $RotationDegreesFromSouth)
    {
        $this->calculate();

        return $this->SunPosition->getSurfaceIncidenceAngle($TiltDegreesFromHorizontalPlane, $RotationDegreesFromSouth);
    }
}
