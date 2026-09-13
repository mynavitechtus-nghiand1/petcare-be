<?php

namespace App\Services\Logging;

use App\Enums\ErrorCode;
use Throwable;

class SyslogErrorLogger implements ErrorLoggerInterface
{
    public function logError(ErrorCode $errorCode, string $message, array $context = []): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $priority = $this->mapSeverityToPriority($errorCode->getSeverity());
        $logMessage = $this->formatLogMessage($errorCode, $message, $context);
        
        syslog($priority, $logMessage);
    }
    
    public function logException(Throwable $exception, array $context = []): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $priority = LOG_ERR;
        $logMessage = $this->formatExceptionMessage($exception, $context);
        
        syslog($priority, $logMessage);
    }
    
    public function logValidationFailure(array $errors, array $context = []): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $priority = LOG_INFO;
        $logMessage = $this->formatValidationMessage($errors, $context);
        
        syslog($priority, $logMessage);
    }
    
    public function isEnabled(): bool
    {
        return function_exists('syslog') && function_exists('openlog');
    }
    
    private function mapSeverityToPriority(string $severity): int
    {
        return match($severity) {
            'critical' => LOG_CRIT,
            'error' => LOG_ERR,
            'warning' => LOG_WARNING,
            'info' => LOG_INFO,
            default => LOG_ERR,
        };
    }
    
    private function formatLogMessage(ErrorCode $errorCode, string $message, array $context): string
    {
        $formatted = "ERROR_CODE={$errorCode->value} MESSAGE=\"{$message}\"";
        
        if (isset($context['user_id'])) {
            $formatted .= " USER_ID={$context['user_id']}";
        }
        
        if (isset($context['url'])) {
            $formatted .= " URL=\"{$context['url']}\"";
        }
        
        return $formatted;
    }
    
    private function formatExceptionMessage(Throwable $exception, array $context): string
    {
        $formatted = "EXCEPTION=\"" . get_class($exception) . "\" MESSAGE=\"{$exception->getMessage()}\"";
        $formatted .= " FILE=\"{$exception->getFile()}:{$exception->getLine()}\"";
        
        if (isset($context['url'])) {
            $formatted .= " URL=\"{$context['url']}\"";
        }
        
        return $formatted;
    }
    
    private function formatValidationMessage(array $errors, array $context): string
    {
        $errorCount = count($errors);
        $formatted = "VALIDATION_FAILED ERRORS_COUNT={$errorCount}";
        
        if (isset($context['url'])) {
            $formatted .= " URL=\"{$context['url']}\"";
        }
        
        return $formatted;
    }
}
