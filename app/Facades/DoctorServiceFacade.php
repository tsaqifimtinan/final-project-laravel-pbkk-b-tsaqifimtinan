<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string doSomething()
 */
class DoctorServiceFacade extends Facade {
    protected static function getFacadeAccessor() {
        return 'doctor-service';
    }
}