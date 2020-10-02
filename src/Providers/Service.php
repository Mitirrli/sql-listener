<?php

declare(strict_types=1);

namespace Mitirrli\SqlListener\Providers;

use Illuminate\Support\ServiceProvider;

class Service extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $configPath = __DIR__ . '/../../config/sql-listener.php';

        $this->publishes([
            $configPath => config_path('sql-listener.php')
        ]);

        $this->logSql();
    }

    public function logSql()
    {
        if (config('app.env') === config('sql-listener.env')) {
            \DB::listen(function ($query) {
                $format = str_replace('?', '%s', $query->sql);

                \Log::info('time: ' . $query->time . 'ms; ' . sprintf($format, ...$query->bindings));
            });
        }
    }
}
