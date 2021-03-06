<?php

namespace Smknstd\LaravelMissingTranslation;

use Illuminate\Translation\Translator as LaravelTranslator;

class Translator extends LaravelTranslator
{
    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|array
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $locale = $locale ?: $this->locale;

        // For JSON translations, there is only one file per locale, so we will simply load
        // that file and then we will be ready to check the array for the key. These are
        // only one level deep so we do not need to do any fancy searching through it.
        $this->load('*', '*', $locale);

        $line = $this->loaded['*']['*'][$locale][$key] ?? null;

        // If we can't find a translation for the JSON key, we will attempt to translate it
        // using the typical translation file. This way developers can always just use a
        // helper such as __ instead of having to pick between trans or __ with views.
        if (! isset($line)) {
            [$namespace, $group, $item] = $this->parseKey($key);

            // Here we will get the locale that should be used for the language line. If one
            // was not passed, we will use the default locales which was given to us when
            // the translator was instantiated. Then, we can load the lines and return.
            if ($locale) {
                if (! is_null($line = $this->getLine(
                    $namespace,
                    $group,
                    $locale,
                    $item,
                    $replace
                ))) {
                    return $line;
                }

                if (app(MissingTranslation::class)->missingKeyCallback) {
                    try {
                        $callbackReturnValue = (app(MissingTranslation::class)->missingKeyCallback)(
                            $key,
                            $locale,
                            $this->fallback,
                            $this->getLine(
                                $namespace,
                                $group,
                                $this->fallback,
                                $item,
                                $replace
                            )
                        );
                        if (is_string($callbackReturnValue)) {
                            return $callbackReturnValue;
                        }
                    } catch (\Exception $e) {
                        //prevent the fallback to crash
                    }
                }

                if ($fallback && ! is_null($line = $this->getLine(
                    $namespace,
                    $group,
                    $this->fallback,
                    $item,
                    $replace
                ))) {
                    return $line;
                }
            } else {
                if (! is_null($line = $this->getLine(
                    $namespace,
                    $group,
                    $this->fallback,
                    $item,
                    $replace
                ))) {
                    return $line;
                }

                if (app(MissingTranslation::class)->missingKeyCallback) {
                    try {
                        $callbackReturnValue = (app(MissingTranslation::class)->missingKeyCallback)(
                            $key,
                            $this->fallback,
                        );
                        if (is_string($callbackReturnValue)) {
                            return $callbackReturnValue;
                        }
                    } catch (\Exception $e) {
                        //prevent the fallback to crash
                    }
                }
            }
        }

        // If the line doesn't exist, we will return back the key which was requested as
        // that will be quick to spot in the UI if language keys are wrong or missing
        // from the application's language files. Otherwise we can return the line.
        return $this->makeReplacements($line ?: $key, $replace);
    }
}
