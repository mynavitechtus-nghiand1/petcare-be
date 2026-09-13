<?php

if (!function_exists('message')) {
    /**
     * Get a message from the message template system
     */
    function message(string $category, string $key, array $replacements = []): string
    {
        return app(\App\Services\MessageService::class)->get($category, $key, $replacements);
    }
}

if (!function_exists('message_exists')) {
    /**
     * Check if a message exists in the message template system
     */
    function message_exists(string $category, string $key): bool
    {
        return app(\App\Services\MessageService::class)->has($category, $key);
    }
}

if (!function_exists('message_project')) {
    /**
     * Get the current project name
     */
    function message_project(): string
    {
        return app(\App\Services\MessageService::class)->getProject();
    }
}

if (!function_exists('escape_like_pattern')) {
    /**
     * Escape special characters in SQL LIKE pattern to prevent unintended wildcard matching
     * Escapes: % and _ (PostgreSQL LIKE wildcards)
     * Uses addcslashes which is safe with Laravel's parameterized queries
     * 
     * @param string $pattern The pattern to escape
     * @return string The escaped pattern
     */
    function escape_like_pattern(string $pattern): string
    {
        // Escape % and _ wildcards using addcslashes (safe with parameterized queries)
        // This ensures that if user types "employ%" literally, it will search for "employ%" not "employ" + wildcard
        return addcslashes($pattern, '%_\\');
    }
}

if (!function_exists('convert_csv_to_utf8_bom')) {
    /**
     * Convert CSV file content from various encodings to UTF-8-BOM
     * Supports: UTF16LE-BOM, UTF16LE, UTF16BE, UTF8-BOM, UTF8, Shift-JIS, EUC-JP, etc.
     * 
     * @param string $fileContent Original file content
     * @return string Converted file content in UTF-8-BOM encoding
     * @throws \App\Exceptions\ValidationException If conversion fails
     */
    function convert_csv_to_utf8_bom(string $fileContent): string
    {
        return app(\App\Services\CsvEncodingConverterService::class)->convertToUtf8Bom($fileContent);
    }
}

if (!function_exists('to_japan_timezone')) {
    /**
     * Convert timestamp to Japan timezone (Asia/Tokyo)
     * 
     * @param string|\DateTimeInterface|\Carbon\Carbon|null $timestamp Timestamp to convert (can be string, DateTime, Carbon, or null)
     * @param string|null $format Optional format string. If provided, returns formatted string. Otherwise returns Carbon instance.
     * @return \Carbon\Carbon|string|null Returns Carbon instance (or formatted string if format provided), or null if input is null
     * @throws \InvalidArgumentException If timestamp format is invalid
     */
    function to_japan_timezone($timestamp = null, ?string $format = null)
    {
        // If null, return null
        if ($timestamp === null) {
            return null;
        }
        
        // Convert to Carbon instance
        $carbon = \Carbon\Carbon::parse($timestamp);
        
        // Set timezone to Japan (Asia/Tokyo)
        $carbon->setTimezone('Asia/Tokyo');
        
        // Return formatted string if format is provided
        if ($format !== null) {
            return $carbon->format($format);
        }
        
        // Return Carbon instance
        return $carbon;
    }
}

if (!function_exists('format_two_digits')) {
    /**
     * Format a number to two digits
     * 
     * @param int $number The number to format
     * @return string The formatted number
     */
    function format_two_digits($number): string {
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }
}
