<?php

namespace App\Services\Logging;

use App\Enums\ErrorCode;
use Throwable;

class MultiChannelErrorLogger implements ErrorLoggerInterface
{
    private array $loggers = [];
    
    public function __construct()
    {
        $channels = config('core.logging.multi_channels', ['laravel', 'sentry']);
        
        // Handle null configuration
        if ($channels === null) {
            $channels = ['laravel', 'sentry'];
        }
        
        foreach ($channels as $channel) {
            if ($channel === 'multi') {
                continue; // Avoid infinite recursion
            }
            
            try {
                $logger = LoggerFactory::create($channel);
                if ($logger->isEnabled()) {
                    $this->loggers[] = $logger;
                }
            } catch (\Exception $e) {
                // Skip failed logger initialization
                error_log("Failed to initialize logger: {$channel} - " . $e->getMessage());
            }
        }
    }
    
    public function logError(ErrorCode $errorCode, string $message, array $context = []): void
    {
        foreach ($this->loggers as $logger) {
            try {
                $logger->logError($errorCode, $message, $context);
            } catch (\Exception $e) {
                // Don't let logging failures break the application
                error_log("Logger failed: " . get_class($logger) . " - " . $e->getMessage());
            }
        }
    }
    
    public function logException(Throwable $exception, array $context = []): void
    {
        foreach ($this->loggers as $logger) {
            try {
                $logger->logException($exception, $context);
            } catch (\Exception $e) {
                error_log("Logger failed: " . get_class($logger) . " - " . $e->getMessage());
            }
        }
    }
    
    public function logValidationFailure(array $errors, array $context = []): void
    {
        foreach ($this->loggers as $logger) {
            try {
                $logger->logValidationFailure($errors, $context);
            } catch (\Exception $e) {
                error_log("Logger failed: " . get_class($logger) . " - " . $e->getMessage());
            }
        }
    }
    
    public function isEnabled(): bool
    {
        return !empty($this->loggers);
    }
}
