<?php

return [
    'common' => [
        'footer_automated' => 'This is an automated message. Please do not reply to this email.',
        'footer_support' => 'If you have any questions, please contact our support team.',
        'footer_company' => '© {year} {brand_name}. All rights reserved.',
        'button_verify' => 'Verify Account',
        'button_reset' => 'Reset Password',
        'button_login' => 'Log In',
        'otp_code_label' => 'Your verification code is:',
        'expiration_notice' => 'This code will expire in 10 minutes.',
    ],
    'registration' => [
        'title' => 'Welcome to {brand_name}!',
        'header' => 'Account Verification Required',
        'intro' => 'Thank you for registering with {brand_name}. To complete your account setup, please verify your account using the OTP code below.',
        'otp_instructions' => 'Enter the code below in the verification form:',
        'success_note' => 'Once verified, you will have full access to all {brand_name} features.',
        'ignore_note' => 'If you didn\'t create an account with us, please ignore this email.',
        'cta_text' => 'Verify your account now',
    ],
    'password_reset' => [
        'title' => 'Password Reset Request',
        'header' => 'Reset Your Password',
        'intro' => 'We received a request to reset your password for your {brand_name} account.',
        'otp_instructions' => 'Enter the code below in the password reset form:',
        'security_notice' => 'If you didn\'t request this password reset, please ignore this email and consider changing your password immediately.',
        'cta_text' => 'Reset your password now',
    ],
    'login' => [
        'title' => 'Login Verification',
        'header' => 'Login Attempt Detected',
        'intro' => 'We detected a login attempt to your {brand_name} account.',
        'otp_instructions' => 'Enter the code below to complete your login:',
        'security_alert' => 'If you didn\'t attempt to log in, please secure your account immediately and contact our support team.',
        'cta_text' => 'Complete login',
    ],
];
