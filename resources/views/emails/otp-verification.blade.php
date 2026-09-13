<!DOCTYPE html>
<html lang="{{ $language ?? 'ja' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('email.otp_verification.subject') }}</title>
    @if($language === 'ja')
    <style>
        body { 
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            line-height: 1.6; 
            color: #333; 
            margin: 0; 
            padding: 20px; 
            background-color: #f5f5f5;
        }
    </style>
    @else
    <style>
        body { 
            font-family: Arial, sans-serif;
            line-height: 1.6; 
            color: #333; 
            margin: 0; 
            padding: 20px; 
            background-color: #f5f5f5;
        }
    </style>
    @endif
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px;">
                    <tr>
                        <td>
                            <p style="line-height: 1.6; color: #333; margin: 0 0 15px 0;">{{ __('email.otp_verification.auto_sent') }}</p>
                            
                            <p style="line-height: 1.6; color: #333; margin: 0 0 15px 0;">{{ __('email.otp_verification.thank_you') }}<br>
                            {{ __('email.otp_verification.instruction') }}</p>
                            
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 2px solid #1e40af;"></td>
                                </tr>
                            </table>
                            
                            <!-- Verification Code Label -->
                            <div style="text-align: center; font-weight: bold; margin: 20px 0;">{{ __('email.otp_verification.verification_code') }}</div>
                            
                            <!-- OTP Code Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #1e40af; border-radius: 8px; margin: 20px 0;">
                                <tr>
                                    <td style="color: white; font-size: 24px; font-weight: bold; text-align: center; padding: 20px; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                                        {{ $otpCode }}
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 2px solid #1e40af;"></td>
                                </tr>
                            </table>
                            
                            <!-- Attention Section -->
                            <div style="margin-top: 30px;">
                                <div style="font-weight: bold; margin-bottom: 5px;">{{ __('email.otp_verification.attention') }}</div>
                                <div>{{ __('email.otp_verification.warning_1') }}</div>
                                <div>{{ __('email.otp_verification.warning_2') }}</div>
                                <div>{{ __('email.otp_verification.warning_3') }}</div>
                            </div>
                            
                            <p style="margin-top: 15px;">{{ __('email.otp_verification.thank_you_service') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
