<?php

return [
    'errors' => [
        // HCK-001: Required fields (merged with HCK-006)
        'required_field' => ':fieldNameが必須項目です。:fieldNameを入力してください。',
        
        // HCK-077: CSV processing error
        'csv_processing_error' => 'データ処理中にエラーが発生しました。データを確認のうえ、再度お試しください。',
        
        // HCK-078: Company not assigned
        'company_not_assigned' => '企業ID :company_id はお客様のアカウントに割り当てられていません。',
        
        // HCK-079: Employee not found
        'employee_not_found' => '企業ID :company_id に該当する従業員ID :employee_id が見つかりませんでした。',
        
        // HCK-080: Invalid value/format
        'invalid_format' => ':fieldNameが正しくありません。',
        'invalid_value' => ':fieldNameが正しくありません。',
        
        // HCK-081: ID not allowed on create
        'id_not_allowed_on_create' => '新規作成時にIDを指定することはできません。',
        
        // HCK-084: Examination not found
        'examination_not_found' => '企業ID :company_id に該当するストレスチェックID :stress_check_id が見つかりませんでした。',
        
        // HCK-084: Examination not found by criteria (from CSV data)
        'examination_not_found_by_criteria' => '指定されたキー情報に一致する既存のストレスチェックレコードが存在しないため、新規作成できません。CSVの内容をご確認ください。',
        
        // HCK-056: Cannot update submitted record
        'cannot_update_submitted_record' => 'このレコードは既に提出済みのため、更新できません。CSV の内容をご確認ください。',
        
        // HCK-077: Record already exists
        'record_already_exists' => '当該従業員のストレスチェックデータは既に管理者側で登録済みのため、新規作成はできません。',
    ],
    
    // Error log header
    'error_log' => [
        'error_message_header' => 'エラーメッセージ',
    ],
];

