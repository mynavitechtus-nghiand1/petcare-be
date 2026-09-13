<?php

namespace App\Services\Logging;

class LoggerFactory
{
    public static function create(?string $driver = null): ErrorLoggerInterface
    {
        $driver = $driver ?? config('core.logging.error_driver', 'laravel');
        
        return match($driver) {
            'laravel' => new LaravelErrorLogger(),
            'sentry' => new SentryErrorLogger(),
            'slack' => new SlackErrorLogger(),
            'syslog' => new SyslogErrorLogger(),
            'multi' => new MultiChannelErrorLogger(),
            'none' => new NullErrorLogger(),
            default => new LaravelErrorLogger(),
        };
    }
    
    public static function createDefault(): ErrorLoggerInterface
    {
        return self::create();
    }
}
