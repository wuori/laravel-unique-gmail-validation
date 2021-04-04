<?php

namespace Wuori\UniqueGmail;

use Illuminate\Support\ServiceProvider;

class UniqueGmailServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/validationRules'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'validationRules');
    }
}
