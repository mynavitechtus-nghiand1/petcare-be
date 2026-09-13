<?php

return [
    'health_check' => 'Fiscal Year :fiscal_year, :number_of_times time(s), :health_check_type',
    'health_check_type' => [
        'general' => 'General Health Checkup',
        'special' => 'Special Health Checkup (Guideline-Based)',
    ],
    'stress_check' => 'Fiscal Year :fiscal_year, :stress_check_type',
    'stress_check_type' => [
        '57_questions' => '57 questions',
        '80_questions' => '80 questions',
    ],
    'health_check_xml' => ':fiscal_year Regular health check',
    'stress_check_xml' => ':fiscal_year Stress check',
];
