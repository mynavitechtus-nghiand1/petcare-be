<!DOCTYPE html>
<html lang="{{ $language ?? 'ja' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('email.high_stress_advice.subject', [], $language) }}</title>
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
                                {{ __('email.high_stress_advice.auto_sent', [], $language) }}
                            </p>
                            
                            <!-- Greeting -->
                            <div style="line-height: 1.8; color: #333; margin: 0 0 20px 0;">
                                {{ __('email.high_stress_advice.greeting', ['company_name' => $companyName ?? ''], $language) }}<br>
                                {{ __('email.high_stress_advice.greeting_thanks', [], $language) }}
                            </div>
                            
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 2px solid #1e40af;"></td>
                                </tr>
                            </table>
                            
                            <!-- Main intro -->
                            <div style="line-height: 1.8; color: #333;">
                                {{ __('email.high_stress_advice.main_intro', [], $language) }}
                            </div>
                            
                            <!-- Employee Information -->
                            <div>
                                <div style="font-weight: bold; color: #333;">
                                    {{ __('email.high_stress_advice.employee_info_title', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    <strong>{{ __('email.high_stress_advice.employee_name_label', [], $language) }}</strong>{{ $employeeName ?? '' }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    <strong>{{ __('email.high_stress_advice.employee_id_label', [], $language) }}</strong>{{ $employeeCode ?? '' }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    <strong>{{ __('email.high_stress_advice.result_label', [], $language) }}{{ __('email.high_stress_advice.result_value', [], $language) }}</strong> 
                                </div>
                            </div>
                            
                            <!-- Action required -->
                            <div style="line-height: 1.8; color: #333; margin-top: 10px;">
                                {{ __('email.high_stress_advice.action_required', [], $language) }}<br>
                                {{ __('email.high_stress_advice.action_request', [], $language) }}
                            </div>
                            
                            <!-- Action items -->
                            <div>
                                <div style="line-height: 1.8; color: #333;">
                                    <span style="font-weight: bold;">{{ __('email.high_stress_advice.action_1_title', [], $language) }}</span>
                                    {{ __('email.high_stress_advice.action_1_detail', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    <span style="font-weight: bold;">{{ __('email.high_stress_advice.action_2_title', [], $language) }}</span>
                                    {{ __('email.high_stress_advice.action_2_detail', [], $language) }}
                                </div>
                            </div>
                            
                            <!-- Closing -->
                            <div style="line-height: 1.8; color: #333;">
                                {{ __('email.high_stress_advice.closing', [], $language) }}
                            </div>
                            
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 2px solid #1e40af;"></td>
                                </tr>
                            </table>
                            
                            <!-- Attention Section -->
                            <div style="margin-top: 30px;">
                                <div style="font-weight: bold; color: #333; margin-bottom: 5px;">
                                    {{ __('email.high_stress_advice.attention', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.high_stress_advice.warning_1', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.high_stress_advice.warning_2', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.high_stress_advice.support_intro', [], $language) }}
                                </div>
                            </div>
                            
                            <!-- Contact Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #e3f2fd; border-radius: 8px; padding: 15px; margin: 20px 0 0 0;">
                                <tr>
                                    <td>
                                        <div style="font-weight: bold; color: #333;">
                                            {{ __('email.high_stress_advice.contact_info', [], $language) }}
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

