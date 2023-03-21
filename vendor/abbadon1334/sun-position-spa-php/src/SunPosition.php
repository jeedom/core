<?php

namespace SolarData;

class SunPosition
{
    /**
     * @var \SolarData\Observer\Observer
     */
    private $Observer;

    public $JD;
    public $JC;
    public $JDE;
    public $JME;
    public $JCE;
    public $ObsLatitudeDegrees = 0;
    public $ObsLongitudeDegrees = 0;
    public $ObsLatitude = 0;
    public $ObsLongitude = 0;
    public $ObsAltitude = 0;
    public $ObsPressure = 0;
    public $ObsTemperature = 0;

    /**
     * Atmospheric refraction at sunrise and sunset (0.5667 deg is typical)
     * valid range: -5   to   5 degrees, error code: 16.
     *
     * @var float
     */
    public $PrimeHourAtmosfericRefraction = 0.5667;
    public $PrimeHourAngle = 0;

    public $DEBUG = false; // set true to collect more data for debugging

    /**
     * Earth heliocentric longitude (degrees).
     *
     * @var float
     */
    public $L° = 0;
    /**
     * Earth heliocentric latitude (degrees).
     *
     * @var float
     */
    public $B° = 0;
    /**
     * Earth radius vector, R (in Astronomical Units, AU).
     *
     * @var float
     */
    public $R = 0;

    /**
     * geocentric longitude (degrees).
     *
     * @var float
     */
    public $Θ° = 0;

    /**
     * geocentric longitude (degrees).
     *
     * @var float
     */
    public $β° = 0;

    /**
     * nutation in longitude and obliquity.
     *
     * @var array
     */
    public $X = 0;

    /**
     * in degrees.
     *
     * @var float
     */
    public $Δψ° = 0;

    /**
     * in degrees.
     *
     * @var float
     */
    public $Δε° = 0;

    /**
     * true obliquity of the ecliptic (degrees).
     *
     * @var float
     */
    public $ε° = 0;

    /**
     * aberration correction (degrees).
     *
     * @var float
     */
    public $Δτ = 0;

    /**
     * apparent sun longitude (degrees).
     *
     * @var float
     */
    public $λ° = 0;

    /**
     * apparent sidereal time at Greenwich.
     *
     * @var float
     */
    public $ν° = 0;

    /**
     * apparent mean sidereal time at Greenwich.
     *
     * @var float
     */
    public $ν0° = 0;

    /**
     * geocentric sun right ascension (degrees).
     *
     * @var float
     */
    public $α° = 0;

    /**
     * topocentric sun right ascension (degrees).
     *
     * @var float
     */
    public $α´° = 0;

    /**
     * geocentric sun declination.
     *
     * @var float
     */
    public $δ° = 0;

    /**
     * topocentric sun declination.
     *
     * @var float
     */
    public $δ´° = 0;

    /**
     * Observer hour angle (degrees).
     *
     * @var float
     */
    public $H° = 0;

    /**
     * topocentric hour angle (degrees).
     *
     * @var float
     */
    public $H´° = 0;

    /**
     * equatorial horizontal parallax of the sun (degrees).
     *
     * @var float
     */
    public $ξ° = 0;

    /**
     * topocentric zenith angle.
     *
     * @var float
     */
    public $Z° = 0;

    /**
     * topocentric astronomers azimuth angle.
     *
     * @var float
     */
    public $Γ° = 0;

    /**
     * topocentric azimuth angle, M for navigators and solar radiation users (in degrees).
     *
     * @var float
     */
    public $Φ° = 0;
    /**
     * topocentric elevation angle without atmospheric refraction.
     *
     * @var float
     */
    public $e0° = 0;

    /**
     * topocentric elevation angle.
     *
     * @var float
     */
    public $e° = 0;

    /**
     * Observer Altitude.
     *
     * @var float
     */
    public $E = 0;

    /**
     * Equation Of Time.
     *
     * @var float
     */
    public $Eot = 0;
    /**
     * Observer Latitude.
     *
     * @var float
     */
    public $φ° = 0;

    /**
     * Observer Longitude.
     *
     * @var float
     */
    public $σ° = 0;

    /**
     * Observer Delta
     * is the difference between the Earth rotation time and the Terrestrial Time (TT).
     * It is derived from observation only and reported yearly in the Astronomical Almanac.
     *
     * @var float
     */
    public $ΔT = 0;

    public $DayFractionSunrise = false;
    public $DayFractionTransit = false;
    public $DayFractionSunset = false;

    public $SunHourAngles;
    public $SunHourAltitude;

    public $TEST_UNIT_L = []; // just for PHUNIT TEST
    public $TEST_UNIT_B = []; // just for PHUNIT TEST
    public $TEST_UNIT_R = []; // just for PHUNIT TEST

    public function setObserver(\SolarData\Observer\Observer $Observer)
    {
        $this->Observer = $Observer;

        $this->ΔT = $this->Observer->ObserverTime->ΔT;

        $this->JD = $this->Observer->ObserverTime->JulianDay;
        $this->JC = $this->Observer->ObserverTime->JulianCentury;
        $this->JDE = $this->Observer->ObserverTime->JulianEphemerisDay;
        $this->JCE = $this->Observer->ObserverTime->JulianEphemerisCentury;
        $this->JME = $this->Observer->ObserverTime->JulianEphemerisMillenium;

        $this->ObsLatitudeDegrees = $this->Observer->ObserverPosition->latitude;
        $this->ObsLatitude = $this->_toRadians($this->ObsLatitudeDegrees);

        $this->ObsLongitudeDegrees = $this->Observer->ObserverPosition->longitude;
        $this->ObsLongitude = $this->_toRadians($this->Observer->ObserverPosition->longitude);

        $this->ObsAltitude = $this->Observer->ObserverPosition->altitude;

        $this->ObsPressure = $this->Observer->ObserverWeather->pressure;
        $this->ObsTemperature = $this->Observer->ObserverWeather->temperature;
    }

    public function getObserver()
    {
        return $this->Observer;
    }

    public function calculate()
    {
        $this->PrimeHourAngle = -1 * (self::SUN_RADIUS + $this->PrimeHourAtmosfericRefraction);
        /**
         * 3.2. Calculate the Earth heliocentric longitude, latitude, and radius vector (L, B, and R):
         *   “Heliocentric” means that the Earth position is calculated with respect to the center of the sun.
         */

        // Earth Heliocentric Longitude
        $L° = $this->_calculateEarthHeliocentricLongitude($this->JME);

        //Earth Heliocentric Latitude
        $B° = $this->_calculateEarthHeliocentricLatitude($this->JME);

        //Earth Radius Vector (AU)
        $R = $this->_calculateEarthRadiusVector($this->JME);

        // calculate geocentric longitude, theta Θ
        // Limit Theta to the range from 0 / to 360
        $Θ° = $this->_limitTo($L° + 180.0, 360.0);

        // calculate geocentric latitude, beta
        $β° = -$B°;
        $β = $this->_toRadians($β°);

        //3.4.1. Calculate the mean elongation of the moon from the sun, X 0 (in degrees)
        $X = array_fill(0, 5, 0.0);
        // mean elongation of the moon from the sun, X 0 (in degrees)
        $X[0] = $this->_calculatePolynomial($this->JCE, self::NUTATION_COEFFS[0]);
        // mean anomaly of the sun (Earth), X 1 (in degrees)
        $X[1] = $this->_calculatePolynomial($this->JCE, self::NUTATION_COEFFS[1]);
        // mean anomaly of the moon, X 2 (in degrees)
        $X[2] = $this->_calculatePolynomial($this->JCE, self::NUTATION_COEFFS[2]);
        // he moon’s argument of latitude, X 3 (in degrees)
        $X[3] = $this->_calculatePolynomial($this->JCE, self::NUTATION_COEFFS[3]);
        // longitude of the ascending node of the moon’s mean orbit on the
        // ecliptic, measured from the mean equinox of the date, X 4 (in degrees)
        $X[4] = $this->_calculatePolynomial($this->JCE, self::NUTATION_COEFFS[4]);

        // 3.4.7 Calculate the nutation in longitude, Δψ (in degrees),
        $Δψi = $this->_calculateΔPsiI($X);
        $Δψ° = array_sum($Δψi) / 36000000.0;

        // 3.4.8 Calculate the nutation in obliquity, Δε (in degrees),
        $Δεi = $this->_calculateΔEpsilonI($X);
        $Δε° = array_sum($Δεi) / 36000000.0;

        // 3.5.1 Calculate the true obliquity of the ecliptic, ε (in degrees):
        $ε0 = $this->_calculatePolynomial($this->JME / 10.0, self::OBLIQUITY_COEFFS);

        // 3.5.2. Calculate the true obliquity of the ecliptic, g (in degrees)
        $ε° = ($ε0 / 3600.0) + $Δε°;
        $ε = $this->_toRadians($ε°);

        // 3.6 Calculate the aberration correction, )J (in degrees):
        $Δτ = -20.4898 / (3600.0 * $R);

        // 3.7 Calculate the apparent sun longitude, 8 (in degrees):
        $λ° = $Θ° + $Δψ° + $Δτ;
        $λ = $this->_toRadians($λ°);

        // 3.8 Calculate the apparent sidereal time at Greenwich at any given time
        // ν (in degrees):
        $ν0° = $this->_calculateGreenwichSiderealTime();

        // 3.8.2. Limit < 0 to the range from 0 / to 360 / as described in step 3.2.6.
        $ν0° = $this->_limitTo($ν0°, 360.0);
        $ν° = $ν0° + $Δψ° * cos($ε);

        // 3.9. Calculate the geocentric sun right ascension, α (in degrees):
        $α = $this->_calculateGeocentricSunRightAscension($β, $ε, $λ);
        $α° = $this->_limitTo($this->_toDegrees($α), 360.0);

        // 3.10 Calculate the geocentric sun declination, δ (in degrees):
        $δ = $this->_calculateGeocentricSunDeclination($β, $ε, $λ);
        $δ° = $this->_toDegrees($δ);

        // 3.11. Calculate the observer local hour angle, H (in degrees):
        $H° = $ν° + $this->ObsLongitudeDegrees - $α°;
        $H° = $this->_limitTo($H°, 360.0);
        $H = $this->_toRadians($H°);

        // 3.12. Calculate the topocentric sun right ascension " ’ (in degrees):
        // “Topocentric” means that the sun position is calculated with respect to the observer local position
        // at the Earth surface.
        // 3.12.1. Calculate the equatorial horizontal parallax of the sun, > (in degrees),
        $ξ° = 8.794 / (3600.0 * $R);
        $ξ = $this->_toRadians($ξ°);

        $E = $this->ObsAltitude;
        $φ = $this->ObsLatitude;
        $φ° = $this->ObsLatitudeDegrees;

        // 3.12.2. Calculate the term u (in radians)
        //
        // where φ is the observer geographical latitude, positive or negative if north or south of the
        // equator, respectively. Note that the 0.99664719 number equals (1 - f ), where f is the
        // Earth’s flattening.
        // 3.12.3. Calculate the term x,
        //
        // where E is the observer elevation (in meters). Note that x equals D * cos φ’ where D is
        // the observer’s distance to the center of Earth, and φ’ is the observer’s geocentric latitude.
        // 3.12.4. Calculate the term y,
        $u = atan(0.99664719 * tan($φ));
        $x = cos($u) + $E * cos($φ) / 6378140.0;
        $y = 0.99664719 * sin($u) + $E * sin($φ) / 6378140.0;

        // 3.12.5. Calculate the parallax in the sun right ascension, )" (in degrees),
        $Δα = atan2(
            -$x * sin($ξ) * sin($H),
            cos($δ) - $x * sin($ξ) * cos($H)
        );
        $Δα° = $this->_toDegrees($Δα);

        // 3.12.6. Calculate the topocentric sun right ascension α´° (in degrees),
        $α´° = $α° + $Δα°;
        $α´ = $this->_toRadians($α´°);

        // 3.12.7. Calculate the topocentric sun declination, * ’ (in degrees),
        $δ´ = atan2(
            (sin($δ) - $y * sin($ξ)) * cos($Δα),
            cos($δ) - $x * sin($ξ) * cos($H)
        );
        $δ´° = $this->_toDegrees($δ´);

        // 3.13. Calculate the topocentric local hour angle, H’ (in degrees),
        $H´ = $H - $Δα;
        $H´° = $this->_toDegrees($H´);

        // 3.14. Calculate the topocentric zenith angle, 2 (in degrees):
        // 3.14.1. Calculate the topocentric elevation angle without atmospheric refraction
        // correction, e0 (in degrees),

        $e0 = asin(sin($φ) * sin($δ´) + cos($φ) * cos($δ´) * cos($H´));
        $e0° = $this->_toDegrees($e0);

        // 3.14.2. Calculate the atmospheric refraction correction, ) e (in degrees),
        $P = $this->ObsPressure;
        $T = $this->ObsTemperature;
        $Δe° = ($P / 1010.0);
        $Δe° *= (283.0 / (273.0 + $T));
        $Δe° *= (1.02 / (60.0 * tan(
                // e0° is in degrees. Calculate the tangent argument in degrees,
                // then convert to radians if required by calculator or computer.
                    $this->_toRadians($e0° + 10.3 / ($e0° + 5.11)))
            )
        );

        // 3.14.3. Calculate the topocentric elevation angle,(in degrees),
        $e° = $e0° + $Δe°;

        // 3.14.4. Calculate the topocentric zenith angle, (in degrees),
        $Z° = 90.0 - $e°;

        // 3.15. Calculate the topocentric azimuth angle, Φ (in degrees):
        //
        // Change Γ to degrees using Equation 12, then limit it to the range from 0 / to 360 / using
        // step 3.2.6.
        // Note that Γ is measured westward from south.
        //
        // 3.15.1. Calculate the topocentric astronomers azimuth angle, ' (in degrees),
        $Γ° = $this->_toDegrees(atan2(sin($H´), cos($H´) * sin($φ) - tan($δ´) * cos($φ)));
        $Γ° = $this->_limitTo($Γ°, 360.0);

        // 3.15.2. Calculate the topocentric azimuth angle, Φ for navigators and
        // solar radiation users (in degrees),
        // Limit Φ to the range from 0 / to 360 / using step 3.2.6.
        // Note that Φ is measured eastward from north.
        $Φ° = $Γ° + 180.0;
        $Φ° = $this->_limitTo($Φ°, 360.0);

        $this->L° = $L°;
        $this->B° = $B°;
        $this->R = $R;

        $this->Θ° = $Θ°;

        $this->X = $X;

        $this->α° = $α°;
        $this->α´° = $α´°;
        $this->Φ° = $Φ°;
        $this->Γ° = $Γ°;
        $this->e0° = $e0°;
        $this->Δe° = $Δe°;
        $this->e° = $e°;
        $this->Δψ° = $Δψ°;
        $this->Δε° = $Δε°;
        $this->Δτ = $Δτ;
        $this->ε° = $ε°;

        $this->δ° = $δ°;
        $this->δ´° = $δ´°;

        $this->ν° = $ν°;
        $this->ν0° = $ν0°;

        $this->λ° = $λ°;

        $this->β° = $β°;

        $this->E = $this->ObsAltitude;
        $this->φ° = $this->ObsLatitudeDegrees;
        $this->σ° = $this->ObsLongitudeDegrees;

        $this->H´° = $H´°;
        $this->H° = $H°;

        $this->ξ° = $ξ°;
        $this->Z° = $Z°;
        $this->Δα° = $Δα°;

        $this->Eot = $this->getEquationOfTime();
    }

    // A.1. Equation of Time
    // The Equation of Time, E, is the difference between solar apparent and mean time.
    // Use the following equation to calculate E (in degrees)
    public function getEquationOfTime()
    {
        $M° = $this->getSunMeanLongitude();
        $E° = $M° - 0.0057183 - $this->α° + $this->Δψ° * cos($this->_toRadians($this->ε°));

        // Multiply E by 4 to change its unit from degrees to minutes of time.
        // Limit E if its absolute value is greater than 20 minutes, by adding or subtracting 1440.

        $Eot = $E° * 4;
        if ($Eot < -20.0) {
            $Eot += 1440.0;
        } elseif ($Eot > 20.0) {
            $Eot -= 1440.0;
        }

        return $Eot;
    }

    public function getSunMeanLongitude()
    {
        $M° = $this->_calculatePolynomial($this->JME, self::EQUATION_OF_TIME);
        //M is limited to the range from 0 / to 360 / using step 3.2.6
        $M° = $this->_limitTo($M°, 360.0);

        return $M°;
    }

    // A.2.Sunrise, Sun Transit, and Sunset
    // The value of 0.5667 / is typically adopted for the atmospheric refraction at sunrise and sunset
    // times. Thus for the sun radius of 0.26667 / , the value -0.8333 / of sun elevation (h’ 0 ) is chosen to
    // calculate the times of sunrise and sunset. On the other hand, the sun transit is the time when the
    // center of the sun reaches the local meridian.
    public function calcSunRiseTransitSet()
    {
        $observer = clone $this->Observer;
        $observer->ObserverTime->Hour = 0;
        $observer->ObserverTime->Minute = 0;
        $observer->ObserverTime->Second = 0;
        $observer->ObserverTime->MilliSecond = 0;
        $observer->ObserverTime->Timezone = 0;
        $observer->ObserverTime->calculate();

        $SPDayScope = new self();
        $SPDayScope->setObserver($observer);
        $SPDayScope->calculate();

        $ν° = $SPDayScope->ν°;

        $observer->ObserverTime->ΔT = 0;
        $observer->ObserverTime->calculate();

        $SPDayScope = new self();
        $SPDayScope->setObserver($observer);
        $SPDayScope->calculate();

        // Prepare Observer for previous day
        $observer = clone $this->Observer;
        $observer->ObserverTime->Day -= 1;
        $observer->ObserverTime->Hour = 0;
        $observer->ObserverTime->Minute = 0;
        $observer->ObserverTime->Second = 0;
        $observer->ObserverTime->MilliSecond = 0;
        $observer->ObserverTime->Timezone = 0;
        $observer->ObserverTime->ΔT = 0;
        $observer->ObserverTime->calculate();

        $SPDayBefore = new self();
        $SPDayBefore->setObserver($observer);
        $SPDayBefore->calculate();

        $observer = clone $this->Observer;
        $observer->ObserverTime->Day += 1;
        $observer->ObserverTime->Hour = 0;
        $observer->ObserverTime->Minute = 0;
        $observer->ObserverTime->Second = 0;
        $observer->ObserverTime->MilliSecond = 0;
        $observer->ObserverTime->Timezone = 0;
        $observer->ObserverTime->ΔT = 0;
        $observer->ObserverTime->calculate();

        $SPDayAfter = new self();
        $SPDayAfter->setObserver($observer);
        $SPDayAfter->calculate();

        $α = [];
        $α[-1] = $SPDayBefore->α°;
        $α[0] = $SPDayScope->α°;
        $α[1] = $SPDayAfter->α°;

        $δ = [];
        $δ[-1] = $SPDayBefore->δ°;
        $δ[0] = $SPDayScope->δ°;
        $δ[1] = $SPDayAfter->δ°;

        // d($α,$δ);
        // d($this->Observer->ObserverTime->Day,$SPDayBefore->Observer->ObserverTime->Day);
        // d($this->Observer->ObserverTime->Day,$SPDayScope->Observer->ObserverTime->Day);
        // d($this->Observer->ObserverTime->Day,$SPDayAfter->Observer->ObserverTime->Day);

        $σ° = $SPDayScope->σ°;

        // A.2.3. Calculate the approximate sun transit time, m 0 , in fraction of day,
        $m = [];
        $m[0] = $α[0] - $σ° - $ν°;
        $m[0] /= 360.0;

        //d($α[0],$σ°,$ν°);
        // A.2.4. Calculate the local hour angle corresponding to the sun elevation equals
        // h'0 0.8333 / , H0 ,

        $PrimeHourAngleRadians = $this->_toRadians($this->PrimeHourAngle);

        $H0° = sin($PrimeHourAngleRadians) - sin($this->_toRadians($SPDayScope->φ°)) * sin($this->_toRadians($δ[0]));
        $H0° /= cos($this->_toRadians($SPDayScope->φ°)) * cos($this->_toRadians($δ[0]));

        // Note that if the argument of the Arccosine is not in the range from -1 to 1,
        // it means that the sun is always above or below the horizon for that day.

        $noSunriseSunset = false;
        if (abs($H0°) <= 1) {
            $H0° = $this->_limitTo($this->_toDegrees(acos($H0°)), 180.0);
        } else {
            $noSunriseSunset = true;
            $H0° = -99999;
        }

        //d($H0°);
        // A.2.5. Calculate the approximate sunrise time, m1 , in fraction of day,
        // A.2.6. Calculate the approximate sunset time, m2 , in fraction of day,
        // A.2.7. Limit the values of m 0 , m 1 , and m 2 to a value between 0 and 1 fraction of day
        // using step 3.2.6 and replacing 360 by 1.
        $DFrac = ($H0° / 360.0);
        $m[-1] = $this->_limitFromZeroToOne($m[0] - $DFrac);
        $m[1] = $this->_limitFromZeroToOne($m[0] + $DFrac);
        $m[0] = $this->_limitFromZeroToOne($m[0]);

        $aα = $α[0] - $α[-1];
        if (abs($aα) >= 2.0) {
            $aα = $this->_limitFromZeroToOne($aα);
        }

        $bα = $α[1] - $α[0];
        if (abs($bα) >= 2.0) {
            $bα = $this->_limitFromZeroToOne($bα);
        }

        $cα = $bα - $aα;

        $aδ = $δ[0] - $δ[-1];
        if (abs($aδ) >= 2.0) {
            $aδ = $this->_limitFromZeroToOne($aδ);
        }

        $bδ = $δ[1] - $δ[0];
        if (abs($bδ) >= 2.0) {
            $bδ = $this->_limitFromZeroToOne($bδ);
        }

        $cδ = $bδ - $aδ;

        $DayFraction = [];

        $φ = $this->_toRadians($this->φ°);

        $this->SunHourNu = $ν°;
        foreach ($m as $DayIndex => $mVal) {
            $ν = $ν° + 360.985647 * $m[$DayIndex];
            $n = $m[$DayIndex] + $this->ΔT / 86400.0;

            $α´° = $α[0] + ($n * ($aα + $bα + $cα * $n)) / 2.0;

            $δ´° = $δ[0] + ($n * ($aδ + $bδ + $cδ * $n)) / 2.0;
            $δ´ = $this->_toRadians($δ´°);

            $H´° = $this->_limitTo180pm($ν + $σ° - $α´°);
            $H´ = $this->_toRadians($H´°);

            $h = $this->_toDegrees(asin(sin($φ) * sin($δ´) + cos($φ) * cos($δ´) * cos($H´)));

            if ($this->DEBUG) {
                $this->SunHourNuPrime[$DayIndex] = $ν;
            }
            if ($this->DEBUG) {
                $this->SunHourAlphaPrime[$DayIndex] = $α´°;
            }
            if ($this->DEBUG) {
                $this->SunHourDeltaPrime[$DayIndex] = $δ´°;
            }

            $this->SunHourAngles[$DayIndex] = $H´°;
            $this->SunHourAltitude[$DayIndex] = $h;

            //d($ν,$n,$α´°,$δ´°,$H´°,$h);

            switch ($DayIndex) {
                case  1:
                case -1:
                    $DayFraction[$DayIndex] = $m[$DayIndex] + ($h - $this->PrimeHourAngle) / (360.0 * cos($δ´) * cos($φ) * sin($H´));
                    break;
                case 0:
                    $DayFraction[$DayIndex] = $m[0] - $H´° / 360.0; // TRANSIT
                    break;
            }
        }

        $this->DayFractionSunrise = ($noSunriseSunset) ? null : $DayFraction[-1];
        $this->DayFractionTransit = $DayFraction[0];
        $this->DayFractionSunset = ($noSunriseSunset) ? null : $DayFraction[1];
    }

    public function _DayFracToHours($dayfrac, $local = false, $daylightsaving = false)
    {
        $timezone = 0.0;
        if ($local) {
            $timezone = (float) $this->Observer->ObserverTime->Timezone;
        }
        if ($daylightsaving) {
            $timezone += 1.0;
        }

        return 24.0 * $this->_limitFromZeroToOne($dayfrac + $timezone / 24.0);
    }

    public function _DayFracToTime($dayfrac, $local = false, $daylightsaving = false, $floatSeconds = false)
    {
        $dayfrac = $this->_DayFracToHours($dayfrac, $local, $daylightsaving);

        return $this->_convertHoursToTime($dayfrac, $floatSeconds);
    }

    private function _convertHoursToTime($hours, $withMillisec = false)
    {
        $minutes = 60.0 * ($hours - (int) $hours);
        $seconds = 60.0 * ($minutes - (int) $minutes);
        if (!$withMillisec) {
            $seconds = (int) $seconds;
        }

        return $this->_lzTime((int) $hours).':'.$this->_lzTime((int) $minutes).':'.$this->_lzTime($seconds);
    }

    // lz = leading zero
    private function _lzTime($num)
    {
        return ($num < 10) ? "0{$num}" : $num;
    }

    // 3.16. Calculate the incidence angle for a surface oriented in any direction,
    // I (in degrees):
    // - ω is the slope of the surface measured from the horizontal plane.
    // - y ( is the surface azimuth rotation angle, measured from south to the projection
    // of the surface normal on the horizontal plane, positive or negative if oriented
    // west or east from south, respectively.
    public function getSurfaceIncidenceAngle($ω°, $γ°)
    {
        $z = $this->_toRadians($this->Z°);
        $Γ = $this->_toRadians($this->Γ°);
        $ω = $this->_toRadians($ω°);
        $γ = $this->_toRadians($γ°);

        return $this->_toDegrees(acos(cos($z) * cos($ω) + sin($ω) * sin($z) * cos(deg2rad($this->Γ° - $γ°))));
    }

    // Earth Heliocentric Longitude
    private function _calculateEarthHeliocentricLongitude($JME)
    {
        $EHL = $this->_calculateEarthTerms($JME, self::EarthPeriodicTerms_L);
        if ($this->DEBUG) {
            $this->TEST_UNIT_L = $EHL;
        }
        $EHLPoly = $this->_calculateEarthTermsPolynomial($JME, $EHL);

        // Limit range from 0 / to 360
        $L° = $this->_limitTo($this->_toDegrees($EHLPoly), 360);

        return $L°;
    }

    // Earth Heliocentric Latitude
    private function _calculateEarthHeliocentricLatitude($JME)
    {
        $EHB = $this->_calculateEarthTerms($JME, self::EarthPeriodicTerms_B);
        if ($this->DEBUG) {
            $this->TEST_UNIT_B = $EHB;
        }
        $EHBPoly = $this->_calculateEarthTermsPolynomial($JME, $EHB);

        //$B° = $this->_limitTo($this->_toDegrees($EHBPoly), 360);
        $B° = $this->_toDegrees($EHBPoly);

        return $B°;
    }

    private function _calculateEarthRadiusVector($JME)
    {
        $R = $this->_calculateEarthTerms($JME, self::EarthPeriodicTerms_R);
        if ($this->DEBUG) {
            $this->TEST_UNIT_R = $R;
        }
        $r = $this->_calculateEarthTermsPolynomial($JME, $R);

        return $r;
    }

    private function _toDegrees($radians)
    {
        $radians = (float) $radians;

        return (180.0 / pi()) * $radians;
    }

    private function _toRadians($degrees)
    {
        $degrees = (float) $degrees;

        return (pi() / 180.0) * $degrees;
    }

    private function _limitFromZeroToOne($value)
    {
        $value = (float) $value;

        $limited = $value - floor($value);

        if ($limited < 0) {
            $limited += 1.0;
        }

        return $limited;
    }

    private function _limitTo180pm($degrees)
    {
        $degrees = (float) $degrees;
        $degrees /= 360.0;

        $limited = 360.0 * ($degrees - floor($degrees));

        if ($limited < -180.0) {
            $limited += 360.0;
        } elseif ($limited > 180.0) {
            $limited -= 360.0;
        }

        return $limited;
    }

    private function _limitTo($degrees, $limit)
    {
        $degrees = (float) $degrees;
        $limit = (float) $limit;

        $degrees /= $limit;
        $limited = $limit * ($degrees - floor($degrees));

        if ($limited < 0) {
            $limited += $limit;
        }

        return $limited;
    }

    private function _calculateGeocentricSunRightAscension($β, $ε, $λ)
    {
        $α = atan2(sin($λ) * cos($ε) - tan($β) * sin($ε), cos($λ));

        //d($β, $ε, $λ,$this->_toDegrees($α));
        return $α;
    }

    private function _calculateGeocentricSunDeclination($β, $ε, $λ)
    {
        $δ = asin(sin($β) * cos($ε) + cos($β) * sin($ε) * sin($λ));

        return $δ;
    }

    private function _calculateGreenwichSiderealTime()
    {
        return 280.46061837 + 360.98564736629 * ($this->JD - 2451545.0) + 0.000387933 * pow($this->JC, 2) - pow($this->JC, 3) / 38710000.0;
    }

    private function _calculateΔPsiI($X)
    {
        $ΔPsiI = array_fill(0, count(self::COEFF_PSI_EPSILON), 0);

        foreach (self::COEFF_PSI_EPSILON as $idx => $values) {
            $a = $values[0];
            $b = $values[1];
            $ΔPsiI[$idx] = ($a + $b * $this->JCE) * sin($this->_toRadians($this->_calculateXjYtermSum($idx, $X)));
        }

        return $ΔPsiI;
    }

    private function _calculateΔEpsilonI($X)
    {
        $ΔEpsI = array_fill(0, count(self::COEFF_PSI_EPSILON), 0);

        foreach (self::COEFF_PSI_EPSILON as $idx => $values) {
            $c = $values[2];
            $d = $values[3];
            $ΔEpsI[$idx] = ($c + $d * $this->JCE) * cos($this->_toRadians($this->_calculateXjYtermSum($idx, $X)));
        }

        return $ΔEpsI;
    }

    private function _calculateXjYtermSum($masterIdx, $X)
    {
        $sum = 0;
        foreach ($X as $idx => $val) {
            $sum += $val * self::SIN_TERMS[$masterIdx][$idx];
        }

        return $sum;
    }

    private function _calculateEarthTerms($JME, $arrayTerms)
    {
        $result_length = count($arrayTerms);

        $resultant_sum = array_fill(0, $result_length, 0);

        foreach ($arrayTerms as $idxTerm => $arrayTermCoeffs) {
            $sum = 0;
            foreach ($arrayTermCoeffs as $coeffs) {
                $A = $coeffs[0];
                $B = $coeffs[1];
                $C = $coeffs[2];

                $sum += $A * cos($B + $C * $JME);
            }
            $resultant_sum[$idxTerm] = $sum;
        }

        return $resultant_sum;
    }

    private function _calculateEarthTermsPolynomial($JME, $arrayTerms)
    {
        return $this->_calculatePolynomial($JME, $arrayTerms) / 1e8;
    }

    private function _calculatePolynomial($x, $array)
    {
        $pSum = 0;
        foreach ($array as $index => $v) {
            $pSum += $v * pow($x, $index);
        }

        return $pSum;
    }

    /* Table A4.2. Earth Periodic Terms */
    // Earth longitude calculation

    const EarthPeriodicTerms_L = [
//L0
        [
            [175347046.0, 0, 0],
            [3341656.0, 4.6692568, 6283.07585],
            [34894.0, 4.6261, 12566.1517],
            [3497.0, 2.7441, 5753.3849],
            [3418.0, 2.8289, 3.5231],
            [3136.0, 3.6277, 77713.7715],
            [2676.0, 4.4181, 7860.4194],
            [2343.0, 6.1352, 3930.2097],
            [1324.0, 0.7425, 11506.7698],
            [1273.0, 2.0371, 529.691],
            [1199.0, 1.1096, 1577.3435],
            [990, 5.233, 5884.927],
            [902, 2.045, 26.298],
            [857, 3.508, 398.149],
            [780, 1.179, 5223.694],
            [753, 2.533, 5507.553],
            [505, 4.583, 18849.228],
            [492, 4.205, 775.523],
            [357, 2.92, 0.067],
            [317, 5.849, 11790.629],
            [284, 1.899, 796.298],
            [271, 0.315, 10977.079],
            [243, 0.345, 5486.778],
            [206, 4.806, 2544.314],
            [205, 1.869, 5573.143],
            [202, 2.458, 6069.777], //Corrected from 202,2.4458,6069.777
            [156, 0.833, 213.299],
            [132, 3.411, 2942.463],
            [126, 1.083, 20.775],
            [115, 0.645, 0.98],
            [103, 0.636, 4694.003],
            [102, 0.976, 15720.839],
            [102, 4.267, 7.114],
            [99, 6.21, 2146.17],
            [98, 0.68, 155.42],
            [86, 5.98, 161000.69],
            [85, 1.3, 6275.96],
            [85, 3.67, 71430.7],
            [80, 1.81, 17260.15],
            [79, 3.04, 12036.46],
            [75, 1.76, 5088.63], //Corrected from  71 1.76 5088.63
            [74, 3.5, 3154.69],
            [74, 4.68, 801.82],
            [70, 0.83, 9437.76],
            [62, 3.98, 8827.39],
            [61, 1.82, 7084.9],
            [57, 2.78, 6286.6],
            [56, 4.39, 14143.5],
            [56, 3.47, 6279.55],
            [52, 0.19, 12139.55],
            [52, 1.33, 1748.02],
            [51, 0.28, 5856.48],
            [49, 0.49, 1194.45],
            [41, 5.37, 8429.24],
            [41, 2.4, 19651.05],
            [39, 6.17, 10447.39],
            [37, 6.04, 10213.29],
            [37, 2.57, 1059.38],
            [36, 1.71, 2352.87],
            [36, 1.78, 6812.77],
            [33, 0.59, 17789.85],
            [30, 0.44, 83996.85],
            [30, 2.74, 1349.87],
            [25, 3.16, 4690.48],
        ],
//L1
        [
            [628331966747.0, 0, 0],
            [206059.0, 2.678235, 6283.07585],
            [4303.0, 2.6351, 12566.1517],
            [425.0, 1.59, 3.523],
            [119.0, 5.796, 26.298],
            [109.0, 2.966, 1577.344],
            [93, 2.59, 18849.23],
            [72, 1.14, 529.69],
            [68, 1.87, 398.15],
            [67, 4.41, 5507.55],
            [59, 2.89, 5223.69],
            [56, 2.17, 155.42],
            [45, 0.4, 796.3],
            [36, 0.47, 775.52],
            [29, 2.65, 7.11],
            [21, 5.34, 0.98],
            [19, 1.85, 5486.78],
            [19, 4.97, 213.3],
            [17, 2.99, 6275.96],
            [16, 0.03, 2544.31],
            [16, 1.43, 2146.17],
            [15, 1.21, 10977.08],
            [12, 2.83, 1748.02],
            [12, 3.26, 5088.63],
            [12, 5.27, 1194.45],
            [12, 2.08, 4694],
            [11, 0.77, 553.57],
            [10, 1.3, 6286.6], //Corrected from   10 1.3 3286.6
            [10, 4.24, 1349.87],
            [9, 2.7, 242.73],
            [9, 5.64, 951.72],
            [8, 5.3, 2352.87],
            [6, 2.65, 9437.76],
            [6, 4.67, 4690.48],
        ],
//L2
        [
            [52919.0, 0, 0],
            [8720.0, 1.0721, 6283.0758],
            [309.0, 0.867, 12566.152],
            [27, 0.05, 3.52],
            [16, 5.19, 26.3],
            [16, 3.68, 155.42],
            [10, 0.76, 18849.23],
            [9, 2.06, 77713.77],
            [7, 0.83, 775.52],
            [5, 4.66, 1577.34],
            [4, 1.03, 7.11],
            [4, 3.44, 5573.14],
            [3, 5.14, 796.3],
            [3, 6.05, 5507.55],
            [3, 1.19, 242.73],
            [3, 6.12, 529.69],
            [3, 0.31, 398.15],
            [3, 2.28, 553.57],
            [2, 4.38, 5223.69],
            [2, 3.75, 0.98],
        ],
//L3
        [
            [289.0, 5.844, 6283.076],
            [35, 0, 0],
            [17, 5.49, 12566.15],
            [3, 5.2, 155.42],
            [1, 4.72, 3.52],
            [1, 5.3, 18849.23],
            [1, 5.97, 242.73],
        ],
//L4
        [
            [114.0, 3.142, 0],
            [8, 4.13, 6283.08],
            [1, 3.84, 12566.15],
        ],
//L5
        [
            [1, 3.14, 0],
        ],
    ];

    // earth heliocentric latitude.
    const EarthPeriodicTerms_B = [
//B0
        [
            [280.0, 3.199, 84334.662],
            [102.0, 5.422, 5507.553],
            [80, 3.88, 5223.69],
            [44, 3.7, 2352.87],
            [32, 4, 1577.34],
        ],
//B1
        [
            [9, 3.9, 5507.55],
            [6, 1.73, 5223.69],
        ],
    ];

    // earth radius vector.
    const EarthPeriodicTerms_R = [
//R0
        [
            [100013989.0, 0, 0],
            [1670700.0, 3.0984635, 6283.07585],
            [13956.0, 3.05525, 12566.1517],
            [3084.0, 5.1985, 77713.7715],
            [1628.0, 1.1739, 5753.3849],
            [1576.0, 2.8469, 7860.4194],
            [925.0, 5.453, 11506.77],
            [542.0, 4.564, 3930.21],
            [472.0, 3.661, 5884.927],
            [346.0, 0.964, 5507.553],
            [329.0, 5.9, 5223.694],
            [307.0, 0.299, 5573.143],
            [243.0, 4.273, 11790.629],
            [212.0, 5.847, 1577.344],
            [186.0, 5.022, 10977.079],
            [175.0, 3.012, 18849.228],
            [110.0, 5.055, 5486.778],
            [98, 0.89, 6069.78],
            [86, 5.69, 15720.84],
            [86, 1.27, 161000.69],
            [65, 0.27, 17260.15], //Corrected from  85 0.27 17260.15
            [63, 0.92, 529.69],
            [57, 2.01, 83996.85],
            [56, 5.24, 71430.7],
            [49, 3.25, 2544.31],
            [47, 2.58, 775.52],
            [45, 5.54, 9437.76],
            [43, 6.01, 6275.96],
            [39, 5.36, 4694],
            [38, 2.39, 8827.39],
            [37, 0.83, 19651.05],
            [37, 4.9, 12139.55],
            [36, 1.67, 12036.46],
            [35, 1.84, 2942.46],
            [33, 0.24, 7084.9],
            [32, 0.18, 5088.63],
            [32, 1.78, 398.15],
            [28, 1.21, 6286.6],
            [28, 1.9, 6279.55],
            [26, 4.59, 10447.39],
        ],
//R1
        [
            [103019.0, 1.10749, 6283.07585],
            [1721.0, 1.0644, 12566.1517],
            [702.0, 3.142, 0],
            [32, 1.02, 18849.23],
            [31, 2.84, 5507.55],
            [25, 1.32, 5223.69],
            [18, 1.42, 1577.34],
            [10, 5.91, 10977.08],
            [9, 1.42, 6275.96],
            [9, 0.27, 5486.78],
        ],
//R2
        [
            [4359.0, 5.7846, 6283.0758],
            [124.0, 5.579, 12566.152],
            [12, 3.14, 0],
            [9, 3.63, 77713.77],
            [6, 1.87, 5573.14],
            [3, 5.47, 18849.23], //Corrected from 3 5.47 18849
        ],
//R3
        [
            [145.0, 4.273, 6283.076],
            [7, 3.92, 12566.15],
        ],
//R4
        [
            [4, 2.56, 6283.08],
        ],
    ];
    const NUTATION_COEFFS = [
        [297.85036, 445267.111480, -0.0019142, 1.0 / 189474],
        [357.52772, 35999.050340, -0.0001603, -1.0 / 300000],
        [134.96298, 477198.867398, 0.0086972, 1.0 / 56250],
        [93.27191, 483202.017538, -0.0036825, 1.0 / 327270],
        [125.04452, -1934.136261, 0.0020708, 1.0 / 450000],
    ];
    const SIN_TERMS = [[0, 0, 0, 0, 1],
        [-2, 0, 0, 2, 2],
        [0, 0, 0, 2, 2],
        [0, 0, 0, 0, 2],
        [0, 1, 0, 0, 0],
        [0, 0, 1, 0, 0],
        [-2, 1, 0, 2, 2],
        [0, 0, 0, 2, 1],
        [0, 0, 1, 2, 2],
        [-2, -1, 0, 2, 2],
        [-2, 0, 1, 0, 0],
        [-2, 0, 0, 2, 1],
        [0, 0, -1, 2, 2],
        [2, 0, 0, 0, 0],
        [0, 0, 1, 0, 1],
        [2, 0, -1, 2, 2],
        [0, 0, -1, 0, 1],
        [0, 0, 1, 2, 1],
        [-2, 0, 2, 0, 0],
        [0, 0, -2, 2, 1],
        [2, 0, 0, 2, 2],
        [0, 0, 2, 2, 2],
        [0, 0, 2, 0, 0],
        [-2, 0, 1, 2, 2],
        [0, 0, 0, 2, 0],
        [-2, 0, 0, 2, 0],
        [0, 0, -1, 2, 1],
        [0, 2, 0, 0, 0],
        [2, 0, -1, 0, 1],
        [-2, 2, 0, 2, 2],
        [0, 1, 0, 0, 1],
        [-2, 0, 1, 0, 1],
        [0, -1, 0, 0, 1],
        [0, 0, 2, -2, 0],
        [2, 0, -1, 2, 1],
        [2, 0, 1, 2, 2],
        [0, 1, 0, 2, 2],
        [-2, 1, 1, 0, 0],
        [0, -1, 0, 2, 2],
        [2, 0, 0, 2, 1],
        [2, 0, 1, 0, 0],
        [-2, 0, 2, 2, 2],
        [-2, 0, 1, 2, 1],
        [2, 0, -2, 0, 1],
        [2, 0, 0, 0, 1],
        [0, -1, 1, 0, 0],
        [-2, -1, 0, 2, 1],
        [-2, 0, 0, 0, 1],
        [0, 0, 2, 2, 1],
        [-2, 0, 2, 0, 1],
        [-2, 1, 0, 2, 1],
        [0, 0, 1, -2, 0],
        [-1, 0, 1, 0, 0],
        [-2, 1, 0, 0, 0],
        [1, 0, 0, 0, 0],
        [0, 0, 1, 2, 0],
        [0, 0, -2, 2, 2],
        [-1, -1, 1, 0, 0],
        [0, 1, 1, 0, 0],
        [0, -1, 1, 2, 2],
        [2, -1, -1, 2, 2],
        [0, 0, 3, 2, 2],
        [2, -1, 0, 2, 2],
    ];
    const COEFF_PSI_EPSILON = [
        [-171996, -174.2, 92025, 8.9],
        [-13187, -1.6, 5736, -3.1],
        [-2274, -0.2, 977, -0.5],
        [2062, 0.2, -895, 0.5],
        [1426, -3.4, 54, -0.1],
        [712, 0.1, -7, 0],
        [-517, 1.2, 224, -0.6],
        [-386, -0.4, 200, 0],
        [-301, 0, 129, -0.1],
        [217, -0.5, -95, 0.3],
        [-158, 0, 0, 0],
        [129, 0.1, -70, 0],
        [123, 0, -53, 0],
        [63, 0, 0, 0],
        [63, 0.1, -33, 0],
        [-59, 0, 26, 0],
        [-58, -0.1, 32, 0],
        [-51, 0, 27, 0],
        [48, 0, 0, 0],
        [46, 0, -24, 0],
        [-38, 0, 16, 0],
        [-31, 0, 13, 0],
        [29, 0, 0, 0],
        [29, 0, -12, 0],
        [26, 0, 0, 0],
        [-22, 0, 0, 0],
        [21, 0, -10, 0],
        [17, -0.1, 0, 0],
        [16, 0, -8, 0],
        [-16, 0.1, 7, 0],
        [-15, 0, 9, 0],
        [-13, 0, 7, 0],
        [-12, 0, 6, 0],
        [11, 0, 0, 0],
        [-10, 0, 5, 0],
        [-8, 0, 3, 0],
        [7, 0, -3, 0],
        [-7, 0, 0, 0],
        [-7, 0, 3, 0],
        [-7, 0, 3, 0],
        [6, 0, 0, 0],
        [6, 0, -3, 0],
        [6, 0, -3, 0],
        [-6, 0, 3, 0],
        [-6, 0, 3, 0],
        [5, 0, 0, 0],
        [-5, 0, 3, 0],
        [-5, 0, 3, 0],
        [-5, 0, 3, 0],
        [4, 0, 0, 0],
        [4, 0, 0, 0],
        [4, 0, 0, 0],
        [-4, 0, 0, 0],
        [-4, 0, 0, 0],
        [-4, 0, 0, 0],
        [3, 0, 0, 0],
        [-3, 0, 0, 0],
        [-3, 0, 0, 0],
        [-3, 0, 0, 0],
        [-3, 0, 0, 0],
        [-3, 0, 0, 0],
        [-3, 0, 0, 0],
        [-3, 0, 0, 0],
    ];

    const OBLIQUITY_COEFFS = [
        84381.448,
        -4680.93,
        -1.55,
        1999.25,
        51.38,
        -249.67,
        -39.05,
        7.12,
        27.87,
        5.79,
        2.45,
    ];

    const EQUATION_OF_TIME = [
        280.4664567,
        360007.6982779,
        0.03032028,
        1 / 49913,
        -1 / 15300,
        -1 / 2000000,
    ];

    const SUN_RADIUS = 0.26667;
}
