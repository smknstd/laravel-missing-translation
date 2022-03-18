<?php

namespace Smknstd\LaravelMissingTranslation;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Smknstd\LaravelMissingTranslation\Commands\LaravelMissingTranslationCommand;
use Spatie\Translatable\Translatable;

class MissingTranslationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('missing-translation');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(MissingTranslation::class, fn () => new MissingTranslation());
        $this->app->bind('missing-translation', MissingTranslation::class);
    }
}
