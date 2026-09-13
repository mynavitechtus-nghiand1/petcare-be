<?php

namespace App\Support;

/**
 * Helper for generating examination title in ja/en format (search_title.md).
 * Uses lang files (resources/lang/{ja,en}/examination_title.php) for translations.
 */
class ExaminationTitleHelper
{
    /**
     * Health check examination title
     */
    public static function healthCheckTitle(int $fiscalYear, int $numberOfTimes, string $healthCheckType): array
    {
        return [
            'ja' => __('examination_title.health_check', [
                'fiscal_year' => $fiscalYear,
                'number_of_times' => $numberOfTimes,
                'health_check_type' => __('examination_title.health_check_type.' . $healthCheckType, [], 'ja'),
            ], 'ja'),
            'en' => __('examination_title.health_check', [
                'fiscal_year' => $fiscalYear,
                'number_of_times' => $numberOfTimes,
                'health_check_type' => __('examination_title.health_check_type.' . $healthCheckType, [], 'en'),
            ], 'en'),
        ];
    }

    /**
     * Health check XML report title
     */
    public static function healthCheckXmlTitle(int $fiscalYear): array
    {
        $replace = ['fiscal_year' => $fiscalYear];

        return [
            'ja' => __('examination_title.health_check_xml', $replace, 'ja'),
            'en' => __('examination_title.health_check_xml', $replace, 'en'),
        ];
    }

    /**
     * Stress check XML report title
     */
    public static function stressCheckXmlTitle(int $fiscalYear): array
    {
        $replace = ['fiscal_year' => $fiscalYear];

        return [
            'ja' => __('examination_title.stress_check_xml', $replace, 'ja'),
            'en' => __('examination_title.stress_check_xml', $replace, 'en'),
        ];
    }
}
