<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Service for converting CSV files from various encodings to UTF-8-BOM
 * Supports:
 * - UTF-8 variants: UTF-8, UTF-8-BOM
 * - UTF-16 variants: UTF-16LE-BOM, UTF-16LE, UTF-16BE-BOM, UTF-16BE
 * - Japanese: Shift-JIS (SJIS), CP932, EUC-JP, ISO-2022-JP
 * - Chinese: GB2312, GBK, GB18030, Big5, Big5-HKSCS
 * - Korean: EUC-KR, ISO-2022-KR
 * - Windows code pages: Windows-1250 to Windows-1258, CP1252
 * - ISO-8859 series: ISO-8859-1 to ISO-8859-15
 * - Cyrillic: KOI8-R, KOI8-U, Windows-1251
 * - Thai: TIS-620, Windows-874
 * - Other: ASCII, ISO-8859-1 (Latin-1)
 */
class CsvEncodingConverterService
{
    /**
     * UTF-8 BOM marker
     */
    private const UTF8_BOM = "\xEF\xBB\xBF";

    /**
     * UTF-16LE BOM marker
     */
    private const UTF16LE_BOM = "\xFF\xFE";

    /**
     * UTF-16BE BOM marker
     */
    private const UTF16BE_BOM = "\xFE\xFF";

    /**
     * Convert CSV file content from various encodings to UTF-8-BOM
     * 
     * IMPORTANT: This method never throws exceptions to ensure other services don't break.
     * If conversion fails, it returns the original file content.
     * 
     * Flow:
     * 1. Validate input is not empty (return original if empty)
     * 2. Fast path: If already UTF-8-BOM, return as-is
     * 3. Detect encoding (BOM first, then mb_detect_encoding)
     * 4. Remove existing BOM (if any)
     * 5. Try to convert to UTF-8 (without BOM)
     * 6. If conversion succeeds: Add UTF-8-BOM and return
     * 7. If conversion fails: Log warning and return original file
     * 
     * @param string $fileContent Original file content
     * @return string Converted file content in UTF-8-BOM encoding (or original if conversion fails)
     */
    public function convertToUtf8Bom(string $fileContent): string
    {
        // Step 1: Validate input is not empty - return as-is if empty
        if (empty($fileContent)) {
            Log::warning('CSV encoding conversion: File content is empty, returning original.');
            return $fileContent;
        }

        // Step 2: Fast path - If already UTF-8-BOM, return as-is (optimization)
        if (substr($fileContent, 0, 3) === self::UTF8_BOM && mb_check_encoding($fileContent, 'UTF-8')) {
            return $fileContent;
        }

        $encoding = null;
        try {
            // Step 3: Detect encoding and BOM
            $encoding = $this->detectEncoding($fileContent);
            $hasBom = $this->hasBom($fileContent, $encoding);

            // Step 4: Remove existing BOM if present (we'll add UTF-8-BOM at the end)
            $contentWithoutBom = $this->removeBom($fileContent, $encoding, $hasBom);

            // Step 5: Validate content is not empty after BOM removal
            if (empty($contentWithoutBom)) {
                Log::warning('CSV encoding conversion: Content is empty after removing BOM, returning original.');
                return $fileContent;
            }

            // Step 6: Try to convert to UTF-8 (without BOM)
            $utf8Content = $this->convertToUtf8($contentWithoutBom, $encoding);

            // Step 7: If conversion succeeded, add UTF-8 BOM and return
            if ($utf8Content !== null) {
                return self::UTF8_BOM . $utf8Content;
            }
        } catch (\Exception $e) {
            // Log the error but don't throw exception
            Log::warning('CSV encoding conversion failed', [
                'error' => $e->getMessage(),
                'encoding' => $encoding ?? 'unknown',
            ]);
        }

        // Step 8: If conversion failed, return original file
        // Try to add UTF-8-BOM if file is already UTF-8 (without BOM)
        if (mb_check_encoding($fileContent, 'UTF-8') && substr($fileContent, 0, 3) !== self::UTF8_BOM) {
            Log::info('CSV encoding conversion: File is UTF-8 without BOM, adding BOM.');
            return self::UTF8_BOM . $fileContent;
        }

        Log::warning('CSV encoding conversion: Returning original file without conversion.');
        return $fileContent;
    }

    /**
     * Detect file encoding
     * 
     * @param string $content File content
     * @return string Detected encoding
     */
    private function detectEncoding(string $content): string
    {
        // Check BOM first (most reliable)
        if (substr($content, 0, 2) === self::UTF16LE_BOM) {
            return 'UTF-16LE';
        }
        if (substr($content, 0, 2) === self::UTF16BE_BOM) {
            return 'UTF-16BE';
        }
        if (substr($content, 0, 3) === self::UTF8_BOM) {
            return 'UTF-8';
        }

        // Try mb_detect_encoding for other encodings
        // Order matters: more specific encodings first, then fallback to general ones
        // Note: Only include encodings supported by PHP mb_detect_encoding()
        $encodingList = [
            // UTF variants (should be detected by BOM, but include for safety)
            'UTF-8',
            'UTF-16LE',
            'UTF-16BE',
            
            // Japanese encodings (most common for this project)
            'SJIS',              // Shift-JIS
            'SJIS-win',          // Shift-JIS (Windows)
            'CP932',             // Windows Japanese (most common Windows Japanese encoding)
            'EUC-JP',            // EUC-JP
            'ISO-2022-JP',       // JIS
            
            // Chinese encodings
            'GB2312',            // Simplified Chinese (GB2312)
            'GBK',               // Simplified Chinese (GBK)
            'GB18030',           // Simplified Chinese (GB18030)
            'Big5',              // Traditional Chinese (Big5)
            // Note: Big5-HKSCS may not be supported by mb_detect_encoding, use Big5 as fallback
            
            // Korean encodings
            'EUC-KR',            // Korean (EUC-KR)
            // Note: ISO-2022-KR may not be supported by mb_detect_encoding
            
            // Windows code pages (Windows-125x series)
            'Windows-1250',       // Central European (Czech, Polish, etc.)
            'Windows-1251',       // Cyrillic (Russian, Bulgarian, etc.)
            'Windows-1252',       // Western European (most common Windows encoding)
            'Windows-1253',       // Greek
            'Windows-1254',       // Turkish
            'Windows-1255',       // Hebrew
            'Windows-1256',       // Arabic
            'Windows-1257',       // Baltic
            'Windows-1258',       // Vietnamese
            
            // ISO-8859 series (Latin encodings)
            'ISO-8859-1',        // Latin-1 (Western European)
            'ISO-8859-2',        // Latin-2 (Central European)
            'ISO-8859-3',        // Latin-3 (South European)
            'ISO-8859-4',        // Latin-4 (Northern European)
            'ISO-8859-5',        // Cyrillic
            'ISO-8859-6',        // Arabic
            'ISO-8859-7',        // Greek
            'ISO-8859-8',        // Hebrew
            'ISO-8859-9',        // Turkish
            'ISO-8859-10',       // Nordic
            'ISO-8859-11',       // Thai (TIS-620)
            'ISO-8859-13',       // Baltic
            'ISO-8859-14',       // Celtic
            'ISO-8859-15',       // Latin-9 (Western European with Euro)
            
            // Cyrillic encodings
            'KOI8-R',            // Russian (KOI8-R)
            'KOI8-U',            // Ukrainian (KOI8-U)
            
            // Thai encodings
            'TIS-620',           // Thai (TIS-620)
            'Windows-874',       // Thai (Windows)
            
            // Other common encodings
            'ASCII',             // ASCII (7-bit)
        ];

        // Filter encoding list to only include encodings supported by PHP
        // This prevents ValueError when mb_detect_encoding encounters unsupported encodings
        $supportedEncodings = mb_list_encodings();
        $validEncodingList = [];
        
        foreach ($encodingList as $encoding) {
            // Check exact match (case-sensitive)
            if (in_array($encoding, $supportedEncodings, true)) {
                $validEncodingList[] = $encoding;
                continue;
            }
            
            // Check case-insensitive match
            $encodingLower = strtolower($encoding);
            foreach ($supportedEncodings as $supported) {
                if (strtolower($supported) === $encodingLower) {
                    $validEncodingList[] = $supported; // Use the supported encoding name
                    break;
                }
            }
        }

        // If no valid encodings found, use a minimal safe list
        if (empty($validEncodingList)) {
            $validEncodingList = ['UTF-8', 'SJIS', 'EUC-JP', 'Windows-1252', 'ISO-8859-1'];
        }

        // Use @ to suppress warnings, but ValueError will still be thrown for invalid encodings
        // So we ensure only valid encodings are passed
        try {
            $detected = mb_detect_encoding($content, $validEncodingList, true);
        } catch (\ValueError $e) {
            // Fallback to UTF-8 if detection fails
            Log::warning('CSV encoding detection failed', [
                'error' => $e->getMessage(),
            ]);
            $detected = 'UTF-8';
        }

        // Normalize encoding name (handle aliases and variations)
        $detected = $this->normalizeEncodingName($detected ?: 'UTF-8');

        return $detected;
    }

    /**
     * Normalize encoding name to handle aliases and variations
     * 
     * @param string $encoding Encoding name
     * @return string Normalized encoding name
     */
    private function normalizeEncodingName(string $encoding): string
    {
        // Map common aliases to standard names
        $aliases = [
            'CP932' => 'CP932',           // Keep CP932 as is (Windows Japanese)
            'SJIS-win' => 'SJIS-win',    // Keep SJIS-win as is
            'CP1252' => 'Windows-1252',   // CP1252 -> Windows-1252
            'Windows-874' => 'Windows-874', // Keep as is
        ];

        // Return mapped alias if exists, otherwise return original
        return $aliases[$encoding] ?? $encoding;
    }

    /**
     * Check if content has BOM
     * 
     * @param string $content File content
     * @param string $encoding Detected encoding
     * @return bool True if BOM is present
     */
    private function hasBom(string $content, string $encoding): bool
    {
        if ($encoding === 'UTF-16LE' && substr($content, 0, 2) === self::UTF16LE_BOM) {
            return true;
        }
        if ($encoding === 'UTF-16BE' && substr($content, 0, 2) === self::UTF16BE_BOM) {
            return true;
        }
        if ($encoding === 'UTF-8' && substr($content, 0, 3) === self::UTF8_BOM) {
            return true;
        }

        return false;
    }

    /**
     * Remove BOM from content
     * 
     * @param string $content File content
     * @param string $encoding Detected encoding
     * @param bool $hasBom Whether BOM is present
     * @return string Content without BOM
     */
    private function removeBom(string $content, string $encoding, bool $hasBom): string
    {
        if (!$hasBom) {
            return $content;
        }

        if ($encoding === 'UTF-16LE' || $encoding === 'UTF-16BE') {
            return substr($content, 2);
        }

        if ($encoding === 'UTF-8') {
            return substr($content, 3);
        }

        return $content;
    }

    /**
     * Convert content to UTF-8 (without BOM)
     * 
     * Note: This method returns UTF-8 content WITHOUT BOM.
     * The BOM will be added in convertToUtf8Bom() method to ensure consistent output.
     * 
     * IMPORTANT: This method returns null on failure instead of throwing exceptions
     * to ensure other services don't break.
     * 
     * Best practices:
     * - Validates UTF-8 encoding after conversion
     * - Handles conversion errors gracefully (returns null)
     * - Uses fallback methods (mb_convert_encoding -> iconv)
     * 
     * @param string $content Content to convert
     * @param string $fromEncoding Source encoding
     * @return string|null UTF-8 encoded content (without BOM, validated) or null if conversion fails
     */
    private function convertToUtf8(string $content, string $fromEncoding): ?string
    {
        // If already UTF-8, validate and return (without BOM)
        // Note: BOM will be added later in convertToUtf8Bom()
        if ($fromEncoding === 'UTF-8') {
            // Ensure no BOM is present (should already be removed, but double-check)
            if (substr($content, 0, 3) === self::UTF8_BOM) {
                $content = substr($content, 3);
            }
            
            // Validate UTF-8 encoding
            if (!mb_check_encoding($content, 'UTF-8')) {
                Log::warning('CSV encoding conversion: Input content is not valid UTF-8 encoding.');
                return null;
            }
            
            return $content;
        }

        // Handle UTF-16 encodings
        if ($fromEncoding === 'UTF-16LE') {
            // Convert from UTF-16LE to UTF-8
            $converted = @mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
            if ($converted === false || !mb_check_encoding($converted, 'UTF-8')) {
                Log::warning('CSV encoding conversion: Failed to convert from UTF-16LE to UTF-8.');
                return null;
            }
            return $converted;
        }

        if ($fromEncoding === 'UTF-16BE') {
            // Convert from UTF-16BE to UTF-8
            $converted = @mb_convert_encoding($content, 'UTF-8', 'UTF-16BE');
            if ($converted === false || !mb_check_encoding($converted, 'UTF-8')) {
                Log::warning('CSV encoding conversion: Failed to convert from UTF-16BE to UTF-8.');
                return null;
            }
            return $converted;
        }

        // Handle encoding aliases and special cases
        $actualEncoding = $this->getActualEncodingName($fromEncoding);

        // Try mb_convert_encoding first (supports most encodings)
        $converted = @mb_convert_encoding($content, 'UTF-8', $actualEncoding);
        
        // If mb_convert_encoding fails, try iconv as fallback
        if ($converted === false || !mb_check_encoding($converted, 'UTF-8')) {
            // Check if iconv is available and try it
            if (function_exists('iconv')) {
                $iconvConverted = @iconv($actualEncoding, 'UTF-8//IGNORE', $content);
                if ($iconvConverted !== false && mb_check_encoding($iconvConverted, 'UTF-8')) {
                    $converted = $iconvConverted;
                }
            }
        }

        // If both methods failed or result is invalid UTF-8, return null
        if ($converted === false || !mb_check_encoding($converted, 'UTF-8')) {
            Log::warning('CSV encoding conversion: Failed to convert', [
                'from_encoding' => $fromEncoding,
                'actual_encoding' => $actualEncoding,
            ]);
            return null;
        }

        return $converted;
    }

    /**
     * Get actual encoding name for conversion (handle aliases and unsupported encodings)
     * 
     * @param string $encoding Encoding name
     * @return string Actual encoding name for mb_convert_encoding/iconv
     */
    private function getActualEncodingName(string $encoding): string
    {
        // Map encoding names to their actual conversion names
        // Also handle encodings that may not be supported by mb_detect_encoding but can be converted
        $encodingMap = [
            'CP932' => 'CP932',              // Windows Japanese
            'SJIS-win' => 'SJIS-win',        // Shift-JIS Windows
            'CP1252' => 'Windows-1252',      // Windows Western European
            'Windows-874' => 'Windows-874',  // Windows Thai
            // Handle encodings that may not be in mb_list_encodings but can be converted
            'Big5-HKSCS' => 'Big5',          // Fallback to Big5 if Big5-HKSCS not supported
            'ISO-2022-KR' => 'EUC-KR',       // Fallback to EUC-KR if ISO-2022-KR not supported
            // Keep other encodings as is
        ];

        return $encodingMap[$encoding] ?? $encoding;
    }
}

