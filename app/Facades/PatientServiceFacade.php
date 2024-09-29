<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string doSomething()
 */
class PatientServiceFacade extends Facade {
    protected static function getFacadeAccessor() {
        return 'patient-service';
    }
}