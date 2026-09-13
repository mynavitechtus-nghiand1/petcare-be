<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException as LaravelValidationException;
use App\Enums\ErrorCode;
use Throwable;

class ValidationException extends BaseException
{
    protected array $errors = [];
    
    public function __construct(
        ErrorCode $errorCode = ErrorCode::VALIDATION_ERROR,
        ?string $message = null,
        array $errors = [],
        array $context = [],
        ?Throwable $previous = null
    ) {
        $this->errors = $errors;
        
        parent::__construct($errorCode, $message, $context, $previous);
    }
    
    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Create from Laravel ValidationException
     */
    public static function fromLaravelValidation(LaravelValidationException $exception): self
    {
        return new self(
            ErrorCode::VALIDATION_ERROR,
            'Validation failed',
            $exception->errors(),
            ['validator' => 'laravel']
        );
    }
    
    /**
     * Create invalid input exception
     */
    public static function invalidInput(string $field, mixed $value, string $reason = '', array $context = []): self
    {
        $message = "Invalid input for field: {$field}";
        if ($reason) {
            $message .= " - {$reason}";
        }
        
        return new self(
            ErrorCode::INVALID_INPUT,
            $message,
            [$field => [$reason ?: 'Invalid value provided']],
            array_merge(['field' => $field, 'value' => $value, 'reason' => $reason], $context)
        );
    }
    
    /**
     * Create missing required field exception
     */
    public static function missingRequired(string|array $fields, array $context = []): self
    {
        $fieldsList = is_array($fields) ? $fields : [$fields];
        $message = 'Missing required field(s): ' . implode(', ', $fieldsList);
        
        $errors = [];
        foreach ($fieldsList as $field) {
            $errors[$field] = ['This field is required'];
        }
        
        return new self(
            ErrorCode::MISSING_REQUIRED_FIELD,
            $message,
            $errors,
            array_merge(['fields' => $fieldsList], $context)
        );
    }
    
    /**
     * Create invalid format exception
     */
    public static function invalidFormat(string $field, mixed $value, string $expectedFormat, array $context = []): self
    {
        return new self(
            ErrorCode::INVALID_FORMAT,
            "Invalid format for field: {$field}. Expected: {$expectedFormat}",
            [$field => ["Invalid format. Expected: {$expectedFormat}"]],
            array_merge([
                'field' => $field,
                'value' => $value,
                'expected_format' => $expectedFormat
            ], $context)
        );
    }
    
    /**
     * Create invalid length exception
     */
    public static function invalidLength(
        string $field,
        mixed $value,
        ?int $min = null,
        ?int $max = null,
        array $context = []
    ): self {
        $length = is_string($value) ? strlen($value) : (is_countable($value) ? count($value) : null);
        
        $message = "Invalid length for field: {$field}";
        $errorMessage = 'Invalid length';
        
        if ($min !== null && $max !== null) {
            $message .= ". Expected between {$min} and {$max}";
            $errorMessage .= ". Expected between {$min} and {$max}";
        } elseif ($min !== null) {
            $message .= ". Expected at least {$min}";
            $errorMessage .= ". Expected at least {$min}";
        } elseif ($max !== null) {
            $message .= ". Expected at most {$max}";
            $errorMessage .= ". Expected at most {$max}";
        }
        
        if ($length !== null) {
            $message .= ", got {$length}";
            $errorMessage .= ", got {$length}";
        }
        
        return new self(
            ErrorCode::INVALID_LENGTH,
            $message,
            [$field => [$errorMessage]],
            array_merge([
                'field' => $field,
                'value' => $value,
                'length' => $length,
                'min' => $min,
                'max' => $max
            ], $context)
        );
    }
    
    /**
     * Convert exception to array for API response
     */
    public function toArray(): array
    {
        $baseArray = parent::toArray();
        $baseArray['errors'] = $this->errors;
        
        return $baseArray;
    }
}
