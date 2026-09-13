<?php

return [
    'registration' => [
        'subject' => 'YELLOW BRICK - 健康管理登録システム 認証コード',
        'greeting' => 'YELLOW BRICK - 健康管理登録システム',
        'welcome_message' => 'YELLOW BRICKをご利用いただき、誠にありがとうございます。下記の認証コードをアプリに入力して認証してください。',
        'success_message' => '認証完了後、YELLOW BRICKのすべての機能をご利用いただけます。',
        'ignore_message' => 'もしこのメールに心当たりがない場合は、お手数ですがこのメールを無視してください。',
        'salutation' => 'いつもご利用いただきありがとうございます。',
        'sms' => 'お客様の認証コードは {otp_code} です。YELLOW BRICKアプリに上記確認コードを入力して認証を行ってください。',
    ],
    'password_reset' => [
        'subject' => 'YELLOW BRICK - 健康管理登録システム 認証コード',
        'greeting' => 'YELLOW BRICK - 健康管理登録システム',
        'request_message' => 'この度はYELLOW BRICKをご利用いただき、誠にありがとうございます。下記の認証コードをアプリに入力して認証してください。',
        'security_notice' => 'もしこのメールに心当たりがない場合は、お手数ですがこのメールを無視してください。',
        'salutation' => 'いつもご利用いただきありがとうございます。',
        'sms' => 'お客様の認証コードは {otp_code} です。YELLOW BRICKアプリに上記確認コードを入力して認証を行ってください。',
        'subject_en' => 'YELLOW BRICK - Health Management Registration System Verification Code',
        'greeting_en' => 'YELLOW BRICK - Health Management Registration System',
        'request_message_en' => 'Thank you very much for using YELLOW BRICK. Please enter the following verification code into the app to complete the authentication process.',
        'security_notice_en' => 'If you do not recognize this email, please disregard it.',
        'salutation_en' => 'Thank you for always using our service.',
        'sms_en' => 'Your verification code is {otp_code}. Please enter the above verification code into the YELLOW BRICK app to complete the authentication process.',
    ],
    'otp' => [
        'subject' => 'OTP Code Verification - TECHTUS',
        'greeting' => 'Hello!',
        'salutation' => 'Best regards, TECHTUS Team',
    ],
    'login' => [
        'subject' => 'Login Verification - TECHTUS',
        'greeting' => 'Login Verification',
        'detected_message' => 'We detected a login attempt to your TECHTUS account.',
        'security_alert' => 'If you didn\'t attempt to log in, please secure your account immediately and contact our support team.',
        'salutation' => 'Best regards, TECHTUS Security Team',
        'sms' => 'TECHTUS Login: Your verification code is {otp_code}. Expires in 10 minutes. If you didn\'t attempt to log in, ignore this message.',
    ],
];
