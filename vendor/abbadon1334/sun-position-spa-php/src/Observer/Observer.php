<?php

namespace SolarData\Observer;

class Observer
{
    public $ObserverPosition;
    public $ObserverTime;
    public $ObserverWeather;

    public function __construct()
    {
        $this->ObserverPosition = new ObserverPosition();
        $this->ObserverTime = new ObserverTime();
        $this->ObserverWeather = new ObserverWeather();
    }

    public function setPosition($latitude, $longitude, $altitude)
    {
        $this->ObserverPosition->latitude = (float) $latitude;
        $this->ObserverPosition->longitude = (float) $longitude;
        $this->ObserverPosition->altitude = (float) $altitude;
    }

    public function setDate($Year, $Month, $Day)
    {
        $this->ObserverTime->Year = (float) $Year;
        $this->ObserverTime->Month = (float) $Month;
        $this->ObserverTime->Day = (float) $Day;
    }

    public function setTime($Hour, $Minute, $Second)
    {
        $this->ObserverTime->Hour = (float) $Hour;
        $this->ObserverTime->Minute = (float) $Minute;
        $this->ObserverTime->Second = (float) $Second;
    }

    public function setTimezone($TZ)
    {
        $this->ObserverTime->Timezone = (float) $TZ;
    }

    public function setDeltaTime($ΔT)
    {
        $this->ObserverTime->ΔT = (float) $ΔT;
    }

    public function setAtmosphericPressure($pressure)
    {
        $this->ObserverWeather->pressure = (float) $pressure;
    }

    public function setAtmosphericTemperature($temperature)
    {
        $this->ObserverWeather->temperature = (float) $temperature;
    }

    public function calculate()
    {
        $this->ObserverTime->calculate();
    }

    public function __clone()
    {
        $this->ObserverTime = clone $this->ObserverTime;
        $this->ObserverPosition = clone $this->ObserverPosition;
        $this->ObserverWeather = clone $this->ObserverWeather;
    }
}
