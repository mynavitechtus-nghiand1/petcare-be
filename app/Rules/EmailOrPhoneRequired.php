<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailOrPhoneRequired implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = request()->all();
        
        // Check if either email or phone is provided
        $hasEmail = !empty($data['email']);
        $hasPhone = !empty($data['phone']);
        
        if (!$hasEmail && !$hasPhone) {
            $fail('Either email or phone must be provided.');
        }
    }
}
