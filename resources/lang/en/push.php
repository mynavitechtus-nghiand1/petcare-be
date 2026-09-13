<?php

return [
    'health_check_result_no_e' => [
        'title' => 'Health check-up confirmation results',
        'body' => "Company name: :company_name\nHealth check date: :health_check_date\n\nUpon reviewing the contents of your health check, <b>no abnormalities were found or observations have been noted.</b>\nPlease continue to monitor your current condition and take care in your daily life. Be sure to bring your results sheet when visiting a medical institution. If you feel any abnormalities in your body, consult a doctor immediately.\n\nPlease continue to take care of your health and enjoy your days.",
    ],
    'health_check_result_work_restriction' => [
        'title' => '【Attention Required】Health check-up confirmation results',
        'body' => "Company name: :company_name\nHealth check date: :health_check_date\nYou are marked as work restriction, please check physician's comments.\n\n:physician_comment",
    ],
    'health_check_examination' => [
        'start' => [
            'title' => 'Notification about Health check-up period',
            'body' => "Submission of Health Check-Up Files to :company_name:\n\nUpload Period: :start_date ～ :end_date\nDuring this period, please click the link below to upload your health check-up file for submission to :company_name.\n<a href=\"/?examination_id=:examination_id\">【Go to upload】</a>\n\nIf you are no longer affiliated with :company_name (i.e., you have already resigned), please ignore this notification.\n\nThank you for your cooperation.",
        ],
        'reminder' => [
            'title' => '【Reminder】Notification about Health check-up period',
            'body' => "Submission of Health Check-Up Files to :company_name:\n\nUpload Period: :start_date ～ :end_date\nDuring this period, please click the link below to upload your health check-up file for submission to :company_name.\n<a href=\"/?examination_id=:examination_id\">【Go to upload】</a>\n\nIf you are no longer affiliated with :company_name (i.e., you have already resigned), please ignore this notification.\n\nThank you for your cooperation.",
        ],
        'end' => [
            'title' => '【Reminder】Notification about Health check-up period',
            'body' => "Submission of Health Check-Up Files to :company_name:\n\nUpload Period: :start_date ～ :end_date\n\nThe end of the upload period is approaching. For those who have not yet uploaded, please note that tomorrow is the final day. Kindly click the link below to upload your file as soon as possible\n<a href=\"/?examination_id=:examination_id\">【Go to upload】</a>\n\nIf you are no longer affiliated with :company_name (i.e., you have already resigned), please ignore this notification.\n\nThank you for your cooperation.",
        ],
    ],
    'stress_check_examination' => [
        'start' => [
            'title' => 'Notification about Stresscheck period',
            'body' => "As part of workplace health management, we will conduct a stress check for all employees. Our goal is to confirm everyone's health status and create a comfortable work environment.\nPlease cooperate by completing the stress check during the following period:\n\nStress Check Implementation Period: :start_date - :end_date\n\nDuring this period, please click the following link to perform the stress check:\n<a href=\"/stress-check\">Go to Stress Check Screen</a>\n\nIf you are no longer employed by :company_name (i.e., you have already resigned), please disregard this notice.\nWe appreciate your cooperation.",
        ],
        'reminder' => [
            'title' => '【Remind】Notification about Stresscheck period',
            'body' => "As part of workplace health management, we will conduct a stress check for all employees. Our goal is to confirm everyone's health status and create a comfortable work environment.\nPlease cooperate by completing the stress check during the following period:\n\nStress Check Implementation Period: :start_date - :end_date\n\nDuring this period, please click the following link to perform the stress check:\n<a href=\"/stress-check\">Go to Stress Check Screen</a>\n\nIf you are no longer employed by :company_name (i.e., you have already resigned), please disregard this notice.\nWe appreciate your cooperation.",
        ],
        'end' => [
            'title' => '【Remind】Notification about Stresscheck period',
            'body' => "As part of workplace health management, we will conduct a stress check for all employees. Our goal is to confirm everyone's health status and create a comfortable work environment.\nPlease cooperate by completing the stress check during the following period:\n\nStress Check Implementation Period: :start_date - :end_date\n\nThe deadline for completing the stress check is approaching. Please click the following link to perform the stress check. If you are unable to submit it by the deadline, please contact your company representative for instructions.\n<a href=\"/stress-check\">Go to Stress Check Screen</a>\n\nIf you are no longer employed by :company_name (i.e., you have already resigned), please disregard this notice.\nWe appreciate your cooperation.",
        ],
    ],
    'health_check_comment_e_judgement' => [
        'title' => '【Attention Required】Health check-up confirmation results',
        'body' => ":employee_name\n\nAs a result of​ the health check, ​the following​ abnormalities were ​found.​\nPlease check and take​ appropriate actions such as​visiting a doctor.​\n\n:physician_comment\n\nWork restriction: :work_restriction\n\nNote:\nThose who are currently undergoing treatment may not fit the above diagnoses.​\nPlease submit the health check report to your primary care physician and follow their instructions.​",
    ],
    'announcement' => [
        'title' => 'Announcement',
    ],
];

