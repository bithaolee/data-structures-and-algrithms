<?php

class SimpleFactory
{

    public function create($type)
    {
        $instance = null;
        switch ($type) {
            'car':
                $instance = new Car();
                break;
            'airplan':
                $instance = new Airplan();
                break;
            default:
                throw new Exception('class ' . $type . ' is not exists.');
                break;
        }

        return $instance;
    }
}

class Car
{}

class Airplan
{}

$factory = new SimpleFactory();
$car = $factory->create('car');
$airplan = $factory->create('airplan');


