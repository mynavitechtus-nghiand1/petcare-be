<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('email.employee_invitation.subject', ['company' => $companyName ?? ''], $language) }}</title>
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
                            <p style="line-height: 1.6; color: #666; margin: 0 0 20px 0; font-size: 12px;">
                                {{ __('email.employee_invitation.auto_sent', [], $language) }}
                            </p>

                            <p style="line-height: 1.8; color: #333; margin: 0 0 10px 0;">
                                {{ __('email.employee_invitation.greeting', ['name' => $fullName ?? ''], $language) }}
                            </p>

                            <p style="line-height: 1.8; color: #333; margin: 0 0 10px 0;">
                                {{ __('email.employee_invitation.main_message', ['company_name' => $companyName ?? ''], $language) }}
                            </p>

                            <div style="line-height: 1.8; color: #333;">
                                {{ __('email.employee_invitation.instruction', [], $language) }}
                            </div>

                            <div style="line-height: 1.8; color: #333;">
                                {{ __('email.employee_invitation.instruction_register_env', [], $language) }}
                            </div>

                            <div style="line-height: 1.8; color: #333; margin-top: 10px;">
                                {{ __('email.employee_invitation.register_smartphone', [], $language) }}
                            </div>
                            <div style="line-height: 1.8; color: #333;">
                                {{ __('email.employee_invitation.register_pc', [], $language) }}
                            </div>

                            <div style="text-align: center; font-weight: bold; margin: 20px 0 8px 0; color: #333;">
                                {{ __('email.employee_invitation.invitation_code_label', [], $language) }}
                            </div>
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #1e40af; border-radius: 8px; margin: 0 0 24px 0;">
                                <tr>
                                    <td style="color: white; font-size: 24px; font-weight: bold; text-align: center; padding: 20px; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                                        {{ $invitationCode ?? '' }}
                                    </td>
                                </tr>
                            </table>

                            <div style="font-weight: bold; color: #333; margin-bottom: 8px;">{{ __('email.employee_invitation.attention', [], $language) }}</div>
                            <div style="line-height: 1.8; color: #333;">{{ __('email.employee_invitation.warning_1', [], $language) }}</div>
                            <div style="line-height: 1.8; color: #333;">{{ __('email.employee_invitation.warning_2', [], $language) }}</div>
                            <div style="line-height: 1.8; color: #333;">{{ __('email.employee_invitation.warning_app_install', [], $language) }}</div>

                            <div style="line-height: 1.8; color: #333; margin-top: 14px; padding-left: 0.5em;">
                                {{ __('email.employee_invitation.ios_link_label', [], $language) }}<a href="{{ $iosAppStoreUrl ?? '#' }}" style="color: #1e40af; text-decoration: none;">{{ $iosAppStoreUrl ?? '#' }}</a>
                            </div>
                            <div style="line-height: 1.8; color: #333; padding-left: 0.5em;">
                                {{ __('email.employee_invitation.android_link_label', [], $language) }}<a href="{{ $androidGooglePlayUrl ?? '#' }}" style="color: #1e40af; text-decoration: none;">{{ $androidGooglePlayUrl ?? '#' }}</a>
                            </div>
                            <div style="line-height: 1.8; color: #333; padding-left: 0.5em;">
                                {{ __('email.employee_invitation.web_link_label', [], $language) }}<a href="{{ $webUrl ?? '#' }}" style="color: #1e40af; text-decoration: none;">{{ $webUrl ?? '#' }}</a>
                            </div>

                            <div style="line-height: 1.8; color: #333; margin-top: 18px;">
                                {{ __('email.employee_invitation.support_intro', [], $language) }}
                            </div>
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #e3f2fd; border-radius: 8px; padding: 15px; margin: 20px 0 0 0;">
                                <tr>
                                    <td>
                                        <div style="font-weight: bold; color: #333;">
                                            {{ __('email.employee_invitation.contact_info', [], $language) }}
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
