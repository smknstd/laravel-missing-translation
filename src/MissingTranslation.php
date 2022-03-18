<?php

namespace Smknstd\LaravelMissingTranslation;

class MissingTranslation
{
    public $missingKeyCallback;

    public function fallback($missingKeyCallback): self
    {
        $this->missingKeyCallback = $missingKeyCallback;

        return $this;
    }
}
