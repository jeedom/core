<?php

if (!function_exists('cast')) {
    function cast($sourceObject, $destination) {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }
}

$sql = 'UPDATE cmd SET eqType=:eqType WHERE id=:id';
foreach (cmd::all() as $cmd) {
    $values = array(
        'id' => $cmd->getId(),
        'eqType' => $cmd->getEqLogic()->getEqType_name(),
    );
    DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
}