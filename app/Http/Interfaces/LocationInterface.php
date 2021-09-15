<?php


namespace App\Http\Interfaces;

use InvalidArgumentException;

interface LocationInterface
{
    public function distance($currentLocation, $targetLocation);
}