
<p align="center"><img src="/laravel-missing-translation.jpg" alt="Social Card of Laravel Missing Tranlslation"></p>

# Laravel missing translations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/smknstd/laravel-missing-translation.svg?style=flat-square)](https://packagist.org/packages/smknstd/laravel-missing-translation)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/smknstd/laravel-missing-translation/run-tests?label=tests)](https://github.com/smknstd/laravel-missing-translation/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/smknstd/laravel-missing-translation/Check%20&%20fix%20styling?label=code%20style)](https://github.com/smknstd/laravel-missing-translation/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/smknstd/laravel-missing-translation.svg?style=flat-square)](https://packagist.org/packages/smknstd/laravel-missing-translation)

Laravel comes with a built-in [localization feature](https://laravel.com/docs/9.x/localization). 
But sometimes your translation files doesn't have a requested translation, and even if fallback locale is used
you generally doesn't notice those missing translations and they can be hard to detect. 

Using this package and its fallback functionality, you can decide what should happen. The idea of this package is
to setting up a callback function with your custom code that is called everytime a translation key is not found.

This package could be used in development, but also in a production environment.

## Installation

You can install the package via composer:

```bash
composer require smknstd/laravel-missing-translation
```

## Usage

To detect missing translations, you have to swap the Laravel TranslationServiceProvider with a custom provider.
In your `config/app.php`, comment out the original TranslationServiceProvider and add the one from this package:

```php
    'providers' => [
        ...
        //'Illuminate\Translation\TranslationServiceProvider',
        'Smknstd\LaravelMissingTranslation\TranslationServiceProvider',
    ]
```

Then to set up the fallback system you need to call static method on the facade Spatie\Translatable\Facades\Translatable.
Typically, you would put this in a service provider of your own.

You have to register some code you want to run, by passing a closure. It will be used as a callback function and will be
executed everytime a translation key is not found. It lets you execute some custom code like logging something or contact
a remote service for example:

```php
    // typically, in a service provider
        
    use Smknstd\LaravelMissingTranslation\Facades\MissingTranslation;
    
    MissingTranslation::fallback(function (
       string $translationKey, 
       string $locale, 
       ?string $fallbackLocale = null,
       ?string $fallbackTranslation = null, 
    ) {
    
        // do something (ex: logging, alerting, etc)
        
        Log::warning('Some translation key is missing', [
           'key' => $translationKey,
           'locale' => $locale,
           'fallback_locale' => $fallbackLocale,
           'fallback_translation' => $fallbackTranslation,
        ]);
    });
```

If the closure returns a string, it will be used as the fallback translation:

```php
    // typically, in a service provider
        
    use Smknstd\LaravelMissingTranslation\Facades\MissingTranslation;
    use App\Service\MyRemoteTranslationService;
    
    MissingTranslation::fallback(function (
       string $translationKey, 
       string $locale, 
       string $fallbackLocale,
       string $fallbackTranslation, 
    ) {
    
        return MyRemoteTranslationService::getAutomaticTranslation(
            $fallbackTranslation,
            $fallbackLocale,
            $locale
        );
    });
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Arnaud Becher](https://github.com/smknstd)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
