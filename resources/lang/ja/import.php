<?php

return [
    'errors' => [
        // HCK-001: Required field
        'required_field' => [
            'required_field' => ':fieldが必須項目です。:fieldを入力してください。',
            'conditional_email' => ':fieldが必須項目です。:fieldを入力してください。',
            'conditional_phone' => ':fieldが必須項目です。:fieldを入力してください。',
        ],
        // HCK-006: Required selection
        'required_selection' => ':fieldを選択してください。',
        // HCK-007: Duplicate entry
        'duplicate_entry' => ':fieldは既に存在しています。別のものをお試しください。',
        // HCK-074: Employee ID required
        'employee_id_required' => '更新または削除を行うには従業員IDが必要です。',
        // HCK-075: Duplicate employee code
        'duplicate_employee_code' => 'この企業ではすでに従業員コードが使用されています。',
        // HCK-076: Invalid date format
        'invalid_date_format' => '生年月日は YYYY-MM-DD の形式で入力してください。',
        // HCK-077: CSV processing error
        'csv_processing_error' => 'データ処理中にエラーが発生しました。',
        // HCK-078: Company not assigned
        'company_not_assigned' => '企業ID :company_id はお客様のアカウントに割り当てられていません。',
        // HCK-079: Employee not found
        'employee_not_found' => '企業ID :company_id に該当する従業員ID :employee_id が見つかりませんでした。',
        // HCK-080: Invalid value
        'invalid_value' => ':label が正しくありません。',
        // HCK-081: ID not allowed on create
        'id_not_allowed_on_create' => '新規作成時にIDを指定することはできません。',
        // HCK-089: Email and phone both provided
        'email_and_phone_both_provided' => '電話番号とメールアドレスは同時に入力できません。どちらか一方のみ入力してください。',
        // Legacy error codes (for backward compatibility)
        'invalid_format' => [
            'invalid_email' => 'メールアドレスのフォーマットが間違っています。',
            'invalid_phone' => '電話番号のフォーマットが間違っています。',
            'invalid_date' => '生年月日をYYYY-MM-DD形式で入力してください。',
        ],
        'processing_error' => 'データの処理中にエラーが発生しました。データを確認して再度お試しください。',
        'resource_not_found' => [
            'employee_not_found' => '従業員ID :employee_id は 企業ID :company_id に存在しません。',
            'company_not_found' => ':fieldが正しくありません。',
        ],
    ],
];
