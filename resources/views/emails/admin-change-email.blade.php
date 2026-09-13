<!DOCTYPE html>
<html lang="{{ $language ?? 'ja' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('email.admin_change_email.subject') }}</title>
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
                            <p style="line-height: 1.6; color: #333; margin: 0 0 15px 0;">{{ __('email.admin_change_email.auto_sent') }}</p>
                            
                            @if($language === 'ja')
                                <p style="line-height: 1.6; color: #333; margin: 0 0 15px 0;">{{ $accountType }} {{ $userName }} 様,</p>
                            @else
                                <p style="line-height: 1.6; color: #333; margin: 0 0 15px 0;">Dear {{ $accountType }} {{ $userName }},</p>
                            @endif
                            
                            <p style="line-height: 1.6; color: #333; margin: 0 0 15px 0;">{{ __('email.admin_change_email.notification_message') }}</p>
                            
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 2px solid #1e40af;"></td>
                                </tr>
                            </table>
                            
                            <!-- Email Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                                        <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">{{ __('email.admin_change_email.old_email') }}</div>
                                        <div style="color: #1e40af; font-family: 'Courier New', monospace;">{{ $oldEmail }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">{{ __('email.admin_change_email.new_email') }}</div>
                                        <div style="color: #1e40af; font-family: 'Courier New', monospace;">{{ $newEmail }}</div>
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
                                <div style="font-weight: bold; margin-bottom: 5px;">{{ __('email.admin_change_email.attention') }}</div>
                                <div>{{ __('email.admin_change_email.warning_1') }}</div>
                                <div>{{ __('email.admin_change_email.warning_2') }}</div>
                                <div>{{ __('email.admin_change_email.warning_3') }}</div>
                            </div>
                            
                            <!-- Contact Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #e3f2fd; border-radius: 8px; padding: 15px; margin: 20px 0 0 0;">
                                <tr>
                                    <td>
                                        <div style="font-weight: bold; color: #333;">
                                            {{ __('email.admin_change_email.contact_info', [], $language ?? 'ja') }}
                                        </div>
                                        <div style="color: #1e40af;">
                                            <a href="mailto:{{ $supportEmail }}" style="color: #1e40af; text-decoration: none;">{{ $supportEmail }}</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
