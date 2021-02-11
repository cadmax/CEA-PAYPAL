<?php

namespace cea\Paypal;
use Illuminate\Support\ServiceProvider;

class PaypalServiceProvider extends ServiceProvider {
    public function boot() {
        $this->publishes([
            __DIR__ . '/resources/views' => base_path('resources/views'),
            __DIR__ . '/Database/migrations' => base_path('database/migrations'),
            __DIR__ . '/Models' => base_path('app'),
            __DIR__ . '/Http/Controllers' => base_path('app/Http/Controllers'),
            __DIR__ . '/Helpers' => base_path('app/Helpers'),
            __DIR__ . '/config' => base_path('config'),
        ]);
    }

    public function register() {
        //
    }
}
?>
