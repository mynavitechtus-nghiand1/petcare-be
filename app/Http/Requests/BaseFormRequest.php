<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Enums\ErrorCode;
use Illuminate\Support\Arr;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Override in child classes for authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    abstract public function rules(): array;


    /** 
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute field must be a valid email address.',
            'string' => 'The :attribute field must be a string.',
            'integer' => 'The :attribute field must be an integer.',
            'boolean' => 'The :attribute field must be true or false.',
            'array' => 'The :attribute field must be an array.',
            'min' => 'The :attribute field must be at least :min characters.',
            'max' => 'The :attribute field must not be greater than :max characters.',
            'unique' => 'The :attribute has already been taken.',
            'exists' => 'The selected :attribute is invalid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'email' => 'email address',
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'phone' => 'phone number',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        // Add any custom validation logic here
        $validator->after(function ($validator) {
            $this->customValidation($validator);
        });
    }

    /**
     * Custom validation logic (override in child classes)
     */
    protected function customValidation(Validator $validator): void
    {
        // Override this method in child classes for custom validation
    }

    /**
     * Handle a failed validation attempt for API requests
     * Custom validation error handling with structured format
     */
    protected function failedValidation(Validator $validator): void
    {
        $formattedErrors = $this->formatValidationErrors($validator);
        
        throw ValidationException::withMessages($formattedErrors);
    }

    /**
     * Get validated data with only the specified keys
     */
    public function validatedOnly(array $keys): array
    {
        return collect($this->validated())
            ->only($keys)
            ->toArray();
    }

    /**
     * Get validated data except the specified keys
     */
    public function validatedExcept(array $keys): array
    {
        return collect($this->validated())
            ->except($keys)
            ->toArray();
    }

    /**
     * Check if request is for updating (PUT/PATCH)
     */
    public function isUpdating(): bool
    {
        return in_array($this->method(), ['PUT', 'PATCH']);
    }

    /**
     * Check if request is for creating (POST)
     */
    public function isCreating(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * Get the model ID from route (common pattern for update/delete)
     */
    public function getRouteId(): ?string
    {
        return $this->route('id') ?? $this->route()->parameter('id');
    }

    /**
     * Format validation errors into structured format
     */
    private function formatValidationErrors(Validator $validator): array
    {
        $failedRules = $validator->failed();
        $fieldErrors = $validator->errors()->messages();
        $formattedErrors = [];

        foreach ($fieldErrors as $field => $_) {
            $rules = $this->extractRulesFromFailedRules($field, $failedRules);
            $formattedErrors[$field] = [
                [
                    'code' => sprintf('%s.%s', $field, Arr::first(array_keys($rules))),
                    'value' => $this->input($field),
                    'rules' => $rules
                ]
            ];
        }

        return $formattedErrors;
    }

    /**
     * Extract validation rules from failed rules
     * Convert PascalCase rule names back to snake_case and return first parameter
     */
    private function extractRulesFromFailedRules(string $field, array $failedRules): array
    {
        $rules = [];
        $fieldFailedRules = $failedRules[$field] ?? [];
        
        foreach ($fieldFailedRules as $ruleName => $parameters) {
            // Convert PascalCase to snake_case
            $snakeCaseRule = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $ruleName));
            $rules[$snakeCaseRule] = Arr::first($parameters);
        }
        
        return $rules;
    }
}
