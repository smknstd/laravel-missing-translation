<?php

namespace Smknstd\LaravelMissingTranslation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Smknstd\LaravelMissingTranslation\MissingTranslation
 */
class MissingTranslation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'missing-translation';
    }
}
