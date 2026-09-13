<?php

namespace App\Services;

class MessageService
{
    protected string $project;
    protected array $messages = [];

    public function __construct()
    {
        $this->project = config('app.project', 'default');
        $this->loadMessages();
    }

    protected function loadMessages(): void
    {
        $messageFiles = [
            'auth' => 'auth',
            'notifications' => 'notifications',
            'emails' => 'emails',
            'sms' => 'sms',
        ];

        foreach ($messageFiles as $key => $file) {
            $this->messages[$key] = $this->loadMessageFile($file);
        }
    }

    protected function loadMessageFile(string $file): array
    {
        $projectPath = resource_path("messages/{$this->project}/{$file}.php");
        $defaultPath = resource_path("messages/default/{$file}.php");

        if (file_exists($projectPath)) {
            return require $projectPath;
        }

        if (file_exists($defaultPath)) {
            return require $defaultPath;
        }

        return [];
    }

    public function get(string $category, string $key, array $replacements = []): string
    {
        // if the key is a dot, split it and get the last part
        $message = $this->messages[$category][$key] ?? '';

        if (str_contains($key, '.')) {
            // if $key is "notifcation.dot.key", get the values by $this->messages[$category]["notifcation"]["dot"]["key"]
            $keys = explode('.', $key);

            foreach ($keys as $i => $k) {
                if ($i === 0) {
                    $message = $this->messages[$category][$k] ?? '';
                    continue;
                }

                $message = $message[$k] ?? '';
            }
        }
        
        // Replace placeholders
        foreach ($replacements as $placeholder => $value) {
            $message = str_replace("{{$placeholder}}", $value, $message);
        }
        
        return $message;
    }

    public function getProject(): string
    {
        return $this->project;
    }

    public function setProject(string $project): void
    {
        $this->project = $project;
        $this->loadMessages();
    }

    public function has(string $category, string $key): bool
    {
        return isset($this->messages[$category][$key]);
    }

    public function getAllMessages(): array
    {
        return $this->messages;
    }
}
