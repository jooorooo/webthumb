<?php namespace Simexis\Webthumb\Facades;

use Illuminate\Support\Facades\Facade;

class Webthumb extends Facade {
	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return '\Simexis\Webthumb\Webthumb'; }

}