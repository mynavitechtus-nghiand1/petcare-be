<?php

namespace App\Services\Logging;

use App\Enums\ErrorCode;
use Illuminate\Support\Facades\Http;
use Throwable;

class SlackErrorLogger implements ErrorLoggerInterface
{
    public function logError(ErrorCode $errorCode, string $message, array $context = []): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $level = $context['severity'] ?? 'error';
        
        // Only send critical/error level to Slack
        if (!in_array($level, ['error', 'critical'])) {
            return;
        }
        
        $webhookUrl = config('core.logging.slack.webhook_url');
        $payload = $this->buildSlackPayload($errorCode, $message, $context);
        
        try {
            Http::timeout(5)->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            // Don't let Slack logging failures break the application
            error_log("Slack logging failed: " . $e->getMessage());
        }
    }
    
    public function logException(Throwable $exception, array $context = []): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $webhookUrl = config('core.logging.slack.webhook_url');
        $payload = $this->buildSlackExceptionPayload($exception, $context);
        
        try {
            Http::timeout(5)->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            error_log("Slack logging failed: " . $e->getMessage());
        }
    }
    
    public function logValidationFailure(array $errors, array $context = []): void
    {
        // Usually don't send validation errors to Slack
        // Can be overridden if needed
    }
    
    public function isEnabled(): bool
    {
        return !empty(config('core.logging.slack.webhook_url'));
    }
    
    private function buildSlackPayload(ErrorCode $errorCode, string $message, array $context): array
    {
        $config = config('core.logging.slack');
        
        return [
            'channel' => $config['channel'] ?? '#errors',
            'username' => $config['username'] ?? 'Error Bot',
            'icon_emoji' => $config['emoji'] ?? ':warning:',
            'attachments' => [
                [
                    'color' => $this->getColorForSeverity($context['severity'] ?? 'error'),
                    'title' => 'Application Error',
                    'fields' => [
                        [
                            'title' => 'Error Code',
                            'value' => $errorCode->value,
                            'short' => true,
                        ],
                        [
                            'title' => 'Environment',
                            'value' => app()->environment(),
                            'short' => true,
                        ],
                        [
                            'title' => 'Message',
                            'value' => $message,
                            'short' => false,
                        ],
                        [
                            'title' => 'URL',
                            'value' => $context['url'] ?? request()->fullUrl(),
                            'short' => false,
                        ],
                    ],
                    'footer' => 'Error Tracking',
                    'ts' => time(),
                ]
            ]
        ];
    }
    
    private function buildSlackExceptionPayload(Throwable $exception, array $context): array
    {
        $config = config('core.logging.slack');
        
        return [
            'channel' => $config['channel'] ?? '#errors',
            'username' => $config['username'] ?? 'Error Bot',
            'icon_emoji' => $config['emoji'] ?? ':warning:',
            'attachments' => [
                [
                    'color' => 'danger',
                    'title' => 'Exception: ' . get_class($exception),
                    'fields' => [
                        [
                            'title' => 'Message',
                            'value' => $exception->getMessage(),
                            'short' => false,
                        ],
                        [
                            'title' => 'File',
                            'value' => $exception->getFile() . ':' . $exception->getLine(),
                            'short' => false,
                        ],
                        [
                            'title' => 'URL',
                            'value' => $context['url'] ?? request()->fullUrl(),
                            'short' => false,
                        ],
                    ],
                    'footer' => 'Exception Tracking',
                    'ts' => time(),
                ]
            ]
        ];
    }
    
    private function getColorForSeverity(string $severity): string
    {
        return match($severity) {
            'critical' => 'danger',
            'error' => 'warning',
            'warning' => 'warning',
            default => 'good',
        };
    }
}
