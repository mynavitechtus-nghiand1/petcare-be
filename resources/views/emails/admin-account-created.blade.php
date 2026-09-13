<!DOCTYPE html>
<html lang="{{ $language ?? 'ja' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('email.admin_account_created.subject', [], $language) }}</title>
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
                            <!-- Auto-sent notice -->
                            <p style="line-height: 1.6; color: #666; margin: 0 0 20px 0; font-size: 12px;">
                                {{ __('email.admin_account_created.auto_sent', [], $language) }}
                            </p>
                            
                            <!-- Greeting -->
                            <p style="line-height: 1.8; color: #333;">
                                {{ __('email.admin_account_created.greeting', ['account_type' => $accountType, 'name' => $fullName], $language) }}
                            </p>
                            
                            <!-- Welcome Message -->
                            <p style="line-height: 1.8; color: #333;">
                                {{ __('email.admin_account_created.welcome_message', [], $language) }}
                                <br>
                                {{ __('email.admin_account_created.welcome_message_1', [], $language) }}
                            </p>
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 2px solid #1e40af;"></td>
                                </tr>
                            </table>
                            
                            <!-- Companies Section -->
                            @if(!empty($companyNames) && count(array_filter($companyNames)) > 0)
                            <div>
                                <div style="font-weight: bold; color: #333;">
                                    {{ __('email.admin_account_created.companies_title', ['account_type' => $accountType], $language) }}
                                </div>
                                @foreach($companyNames as $companyName)
                                    @if(!empty($companyName))
                                    <div style="line-height: 1.8; color: #333;">- {{ $companyName }}</div>
                                    @endif
                                @endforeach
                                <div style="line-height: 1.8; color: #666; font-size: 13px;">
                                    {{ __('email.admin_account_created.company_inaccuracy_notice', ['support_email' => $supportEmail], $language) }}
                                </div>
                            </div>
                            
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 1px solid #e9ecef;"></td>
                                </tr>
                            </table>
                            @endif
                            
                            <!-- Login Information Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin: 20px 0;">
                                <tr>
                                    <td>
                                        <div style="font-weight: bold; color: #495057;">
                                            {{ __('email.admin_account_created.login_info_title', [], $language) }}
                                        </div>
                                        
                                        <div style="padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                                            <div style="font-weight: bold; color: #495057; font-size: 13px;">
                                                {{ __('email.admin_account_created.login_email_label', ['loginEmail' => $loginEmail], $language) }}
                                            </div>
                                        </div>
                                        
                                        <div style="padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                                            <div style="font-weight: bold; color: #495057; font-size: 13px;">
                                                {{ __('email.admin_account_created.initial_password_label', ['loginPassword' => $loginPassword], $language) }}
                                            </div>
                                        </div>
                                        
                                        <div style="padding: 8px 0;">
                                            <div style="font-weight: bold; color: #495057; font-size: 13px;">
                                                {!! __('email.admin_account_created.my_page_url_label', ['loginUrl' => '<a href="' . e($loginUrl) . '" style="color: #1e40af;">' . e($loginUrl) . '</a>'], $language) !!}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 2px solid #1e40af;"></td>
                                </tr>
                            </table>
                            
                            <!-- Important Notice Section -->
                            <div style="margin-top: 30px;">
                                <div style="font-weight: bold; color: #333; margin-bottom: 5px;">
                                    {{ __('email.admin_account_created.attention', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.admin_account_created.warning_1', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.admin_account_created.warning_2', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.admin_account_created.warning_3', [], $language) }}
                                </div>
                            </div>
                            
                            <!-- Contact Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #e3f2fd; border-radius: 8px; padding: 15px; margin: 20px 0 0 0;">
                                <tr>
                                    <td>
                                        <div style="font-weight: bold; color: #333;">
                                            {{ __('email.admin_account_created.contact_info', [], $language) }}
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
