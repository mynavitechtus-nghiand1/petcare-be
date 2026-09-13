<!DOCTYPE html>
<html lang="{{ $language ?? 'ja' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('email.work_restriction.subject', [], $language) }}</title>
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
                                {{ __('email.work_restriction.auto_sent', [], $language) }}
                            </p>
                            
                            <!-- Greeting -->
                            <div style="line-height: 1.8; color: #333; margin: 0 0 20px 0;">
                                {{ __('email.work_restriction.greeting', [
                                    'company_name' => $companyName ?? '',
                                    'contact_person_name' => $contactPersonName ?? ''
                                ], $language) }}
                            </div>
                            
                            <!-- Notification intro -->
                            <div style="line-height: 1.8; color: #333;">
                                {{ __('email.work_restriction.notification_intro', [
                                    'commenter_role' => $commenterRole ?? '',
                                    'commenter_name' => $commenterName ?? ''
                                ], $language) }}
                            </div>
                            
                            <!-- Main message -->
                            <div style="line-height: 1.8; color: #333;">
                                {{ __('email.work_restriction.main_message', [
                                    'employee_name' => $employeeName ?? ''
                                ], $language) }}
                            </div>
                            <div style="line-height: 1.8; color: #333;">
                                {{ __('email.work_restriction.main_message_1', [], $language) }}
                            </div>
                            <!-- Separator -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="border-top: 2px solid #1e40af;"></td>
                                </tr>
                            </table>
                            
                            <!-- Restriction Details -->
                            <div>
                                <div style="font-weight: bold; color: #333;">
                                    {{ __('email.work_restriction.restriction_details_title', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    <strong>{{ __('email.work_restriction.target_employee_label', [], $language) }}</strong>{{ $employeeName ?? '' }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    <strong>{{ __('email.work_restriction.employee_id_label', [], $language) }}</strong>{{ $employeeId ?? '' }}
                                </div>
                            </div>
                            
                            <!-- Restriction note -->
                            <p style="line-height: 1.8; color: #333;">
                                {{ __('email.work_restriction.restriction_note', [
                                    'employee_name' => $employeeName ?? ''
                                ], $language) }}
                                <br>
                                {{ __('email.work_restriction.restriction_note_1', [], $language) }}
                            </p>
                            
                            <!-- Task Redistribution -->
                            <div>
                                <div style="font-weight: bold; color: #333;">
                                    {{ __('email.work_restriction.task_redistribution_title', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.work_restriction.task_redistribution_detail', [], $language) }}
                                </div>
                            </div>
                            
                            <!-- Working Hours Adjustment -->
                            <div>
                                <div style="font-weight: bold; color: #333;">
                                    {{ __('email.work_restriction.working_hours_adjustment_title', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.work_restriction.working_hours_adjustment_detail', [], $language) }}
                                </div>
                            </div>
                            
                            <!-- Support System -->
                            <div>
                                <div style="font-weight: bold; color: #333;">
                                    {{ __('email.work_restriction.support_system_title', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.work_restriction.support_system_detail', [], $language) }}
                                </div>
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
                                    {{ __('email.work_restriction.attention', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.work_restriction.warning_1', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.work_restriction.warning_2', [], $language) }}
                                </div>
                                <div style="line-height: 1.8; color: #333;">
                                    {{ __('email.work_restriction.warning_3', [], $language) }}
                                </div>
                            </div>
                            
                            <!-- Contact Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #e3f2fd; border-radius: 8px; padding: 15px; margin: 20px 0 0 0;">
                                <tr>
                                    <td>
                                        <div style="font-weight: bold; color: #333;">
                                            {{ __('email.work_restriction.contact_info', [], $language) }}
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

