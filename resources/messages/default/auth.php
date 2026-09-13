<?php

return [
    'brand_name' => '{brand_name}',
    'brand_display_name' => '{brand_display_name}',
    'welcome' => 'Welcome to {brand_name}!',
    'login_success' => 'You have successfully logged in to {brand_name}.',
    'logout_success' => 'You have successfully logged out from {brand_name}.',
    'registration_success' => 'Your account has been created successfully. Please verify your email to complete the registration.',
    'verification_required' => 'Please verify your email address to continue using {brand_name}.',
    'password_reset_success' => 'Your password has been reset successfully.',
    'account_suspended' => 'Your account has been suspended. Please contact support for assistance.',
    'invalid_credentials' => 'The provided credentials are invalid.',
    'email_not_verified' => 'Please verify your email address before logging in.',
    'account_not_found' => 'No account found with the provided information.',
    'too_many_attempts' => 'Too many login attempts. Please try again later.',
    'session_expired' => 'Your session has expired. Please log in again.',
    'access_denied' => 'You do not have permission to access this resource.',
    'maintenance_mode' => 'The system is currently under maintenance. Please try again later.',
    
    // Password validation messages
    'current_password_required' => 'Current password is required',
    'current_password_incorrect' => 'The current password is incorrect',
    'password_required' => 'Password is required',
    'password_confirmation_mismatch' => 'Password confirmation does not match',
    'password_must_be_different' => 'New password must be different from current password',
    'password_format_invalid' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
    'password_confirmation_required' => 'Password confirmation is required',
    
    'profile' => [
        'update_success' => 'Profile updated successfully',
        'update_failed' => 'Update profile failed',
    ],
    'email_change' => [
        'must_be_different' => 'New email must be different from current email',
        'request_failed' => 'Request email change failed',
        'verify_failed' => 'Verify email change failed',
        'changed_success' => 'Email changed successfully',
        'email' => [
            'already_exists' => 'Email already exists',
        ],
    ],
    'otp' => [
        'invalid_or_expired' => 'Invalid or expired OTP'
    ],
    'birth_date_required' => 'Birth date is required',
    'birth_date_invalid' => 'Birth date must be a valid date',
    'birth_date_future' => 'Birth date must be in the past',
];
