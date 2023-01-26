<?php

namespace SolarData\Observer;

class ObserverTime
{
    public $Year;
    public $Month;
    public $Day;
    public $Hour;
    public $Minute;
    public $Second;
    public $Timezone;
    public $JulianDay = 0;
    public $JulianCentury = 0;

    /**
     * ΔT is the difference between the Earth rotation time and the Terrestrial Time (TT).
     * It is derived from observation only and reported yearly in the Astronomical Almanac.
     *
     * @param float $ΔT
     */
    public $ΔT = 0;
    public $JulianEphemerisDay = 0;
    public $JulianEphemerisCentury = 0;
    public $JulianEphemerisMillenium = 0;

    private function calcJulianDay()
    {

// is the month of the year (e.g. 1 for January, ..etc.).
        // !!! Note that if M > 2, then Y and M are not changed
        // !!! ,but if M = 1 or 2, then Y = Y -1 and M = M + 12.

        $Year = $this->Year;
        $Month = $this->Month;

        if ($Month <= 2) {
            $Month += 12;
            $Year -= 1;
        }

        // is the day of the month with decimal time (e.g. for the second day of the
        // month at 12:30:30 UT, D = 2.521180556).
        $Day = $this->Day + $this->Hour / 24.0 - $this->Timezone / 24 + $this->Minute / (24.0 * 60.0) + $this->Second / (24.0 * 60.0 * 60.0);
        // is equal to 0, for the Julian calendar {i.e. by using B = 0 in Equation 4, JD < 2299160}
        // , and equal to (2 - A + INT (A/4)) for the Gregorian calendar

        $A = (int) ($Year / 100.0);
        $B = 2 - $A + (int) ($A / 4.0);

        $JD = (int) (365.25 * ($Year + 4716.0)) + (int) (30.6001 * ($Month + 1.0)) + $Day + $B - 1524.5;

        if ($JD < 2299160) {
            $B = 0;

            $JD = (int) (365.25 * ($Year + 4716.0)) + (int) (30.6001 * ($Month + 1.0)) + $Day + $B - 1524.5;
        }

        $this->JulianDay = $JD;

        return $this->JulianDay;
    }

    /**
     *  Calculate the Julian Century (JC).
     */
    private function calcJulianCentury()
    {
        $this->JulianCentury = ($this->JulianDay - 2451545) / 36525;
    }

    private function calcJulianEphemerisDay()
    {
        $this->JulianEphemerisDay = $this->JulianDay + ($this->ΔT / 86400);

        return $this->JulianEphemerisDay;
    }

    /**
     *  Calculate the Julian Ephemeris Century (JCE).
     */
    private function calcJulianEphemerisCentury()
    {
        $this->JulianEphemerisCentury = ($this->JulianEphemerisDay - 2451545) / 36525;
    }

    /**
     *  Calculate the Julian Ephemeris Millennium (JME) for the 2000 standard epoch,.
     */
    private function calcJulianEphemerisMillenium()
    {
        $this->JulianEphemerisMillenium = $this->JulianEphemerisCentury / 10;
    }

    public function calculate()
    {
        $this->calcJulianDay();
        $this->calcJulianCentury();
        $this->calcJulianEphemerisDay();
        $this->calcJulianEphemerisCentury();
        $this->calcJulianEphemerisMillenium();
    }
}
