<?php

namespace App\Enums;

enum ErrorCode: string
{
    // System Errors (10000-10999)
    case SYSTEM_ERROR = '10000';
    case SYSTEM_MAINTENANCE = '10001';
    case SYSTEM_UNAVAILABLE = '10002';
    
    // Validation Errors (20000-20999)
    case VALIDATION_ERROR = '20000';
    case INVALID_INPUT = '20001';
    case MISSING_REQUIRED_FIELD = '20002';
    case INVALID_FORMAT = '20003';
    case INVALID_LENGTH = '20004';
    case CSV_TEMPLATE_INVALID = '20005';
    case FILE_TYPE_NOT_ALLOWED = '20006';
    
    // Authentication & Authorization Errors (30000-30999)
    case UNAUTHORIZED = '30000';
    case AUTHENTICATION_FAILED = '30001';
    case AUTHORIZATION_FAILED = '30002';
    case INVALID_CREDENTIALS = '30003';
    case TOKEN_EXPIRED = '30004';
    case TOKEN_INVALID = '30005';
    case ACCESS_DENIED = '30006';
    case INSUFFICIENT_PERMISSIONS = '30007';
    case RATE_LIMIT_EXCEEDED = '30008';
    case REGISTRATION_FAILED = '30009';
    case LOGOUT_FAILED = '30010';
    case TOKEN_REFRESH_FAILED = '30011';
    case OTP_SEND_FAILED = '30012';
    case OTP_VERIFICATION_FAILED = '30013';
    case OTP_RESEND_FAILED = '30014';
    case OTP_EXPIRED = '30022';
    case OTP_INVALID = '30023';
    case INVITATION_NOT_FOUND = '30025';
    case INVITATION_ALREADY_USED = '30026';
    case PASSWORD_RESET_REQUEST_FAILED = '30015';
    case PASSWORD_RESET_FAILED = '30016';
    case PASSWORD_CHANGE_FAILED = '30017';
    case USER_PROFILE_RETRIEVAL_FAILED = '30018';
    case NOTIFICATION_STATUS_FAILED = '30019';
    case NOTIFICATION_UPDATE_FAILED = '30020';
    case ACCOUNT_NOT_VERIFIED = '30021';
    case INVALID_CREDENTIALS_LIMIT_EXCEEDED = '30024';
    case ACCOUNT_DELETION_FAILED = '30027';
    case OTP_ACCOUNT_NOT_ELIGIBLE = '30028';
    
    // Resource Errors (40000-40999)
    case RESOURCE_NOT_FOUND = '40000';
    case RESOURCE_ALREADY_EXISTS = '40001';
    case RESOURCE_CONFLICT = '40002';
    case RESOURCE_DELETED = '40003';
    case RESOURCE_LOCKED = '40004';
    case RESOURCE_OUT_OF_DATE = '40005';
    case RESOURCE_NO_COMPLETIONS = '40006';
    
    // Business Logic Errors (50000-50999)
    case BUSINESS_RULE_VIOLATION = '50000';
    case INVALID_OPERATION = '50001';
    case OPERATION_NOT_ALLOWED = '50002';
    case QUOTA_EXCEEDED = '50003';
    case DUPLICATE_ENTRY = '50004';
    
    // External Service Errors (60000-60999)
    case EXTERNAL_SERVICE_ERROR = '60000';
    case EXTERNAL_SERVICE_TIMEOUT = '60001';
    case EXTERNAL_SERVICE_UNAVAILABLE = '60002';
    case PAYMENT_GATEWAY_ERROR = '60003';
    
    // Database Errors (70000-70999)
    case DATABASE_ERROR = '70000';
    case DATABASE_CONNECTION_ERROR = '70001';
    case QUERY_EXECUTION_ERROR = '70002';
    case CONSTRAINT_VIOLATION = '70003';
    
    /**
     * Get error message for the error code
     */
    public function getMessage(): string
    {
        return match($this) {
            // System Errors
            self::SYSTEM_ERROR => 'System error occurred',
            self::SYSTEM_MAINTENANCE => 'System is under maintenance',
            self::SYSTEM_UNAVAILABLE => 'System is temporarily unavailable',
            
            // Validation Errors
            self::VALIDATION_ERROR => 'Validation failed',
            self::INVALID_INPUT => 'Invalid input provided',
            self::MISSING_REQUIRED_FIELD => 'Required field is missing',
            self::INVALID_FORMAT => 'Invalid format provided',
            self::INVALID_LENGTH => 'Invalid length provided',
            self::CSV_TEMPLATE_INVALID => 'CSV template is invalid',
            self::FILE_TYPE_NOT_ALLOWED => 'This file type is not allowed for upload',
            
            // Authentication & Authorization
            self::UNAUTHORIZED => 'Unauthorized access',
            self::AUTHENTICATION_FAILED => 'Authentication failed',
            self::AUTHORIZATION_FAILED => 'Authorization failed',
            self::INVALID_CREDENTIALS => 'Invalid credentials provided',
            self::TOKEN_EXPIRED => 'Access token has expired',
            self::TOKEN_INVALID => 'Invalid access token',
            self::ACCESS_DENIED => 'Access denied',
            self::INSUFFICIENT_PERMISSIONS => 'Insufficient permissions',
            self::RATE_LIMIT_EXCEEDED => 'Rate limit exceeded',
            self::REGISTRATION_FAILED => 'Registration failed',
            self::LOGOUT_FAILED => 'Logout failed',
            self::TOKEN_REFRESH_FAILED => 'Token refresh failed',
            self::OTP_SEND_FAILED => 'Failed to send OTP',
            self::OTP_VERIFICATION_FAILED => 'OTP verification failed',
            self::OTP_RESEND_FAILED => 'Failed to resend OTP',
            self::OTP_EXPIRED => 'OTP has expired',
            self::OTP_INVALID => 'Invalid OTP code',
            self::PASSWORD_RESET_REQUEST_FAILED => 'Password reset request failed',
            self::PASSWORD_RESET_FAILED => 'Password reset failed',
            self::PASSWORD_CHANGE_FAILED => 'Password change failed',
            self::USER_PROFILE_RETRIEVAL_FAILED => 'Failed to retrieve user profile',
            self::NOTIFICATION_STATUS_FAILED => 'Failed to retrieve notification status',
            self::NOTIFICATION_UPDATE_FAILED => 'Failed to update notification',
            self::ACCOUNT_NOT_VERIFIED => 'Account not verified. Please verify your email/phone first.',
            self::INVALID_CREDENTIALS_LIMIT_EXCEEDED => 'Too many failed attempts. Please try again later.',
            self::ACCOUNT_DELETION_FAILED => 'Failed to delete user account',
            self::OTP_ACCOUNT_NOT_ELIGIBLE => 'The verification session has expired or is no longer valid. Please start again.',
            
            // Resource Errors
            self::RESOURCE_NOT_FOUND => 'Resource not found',
            self::RESOURCE_ALREADY_EXISTS => 'Resource already exists',
            self::RESOURCE_CONFLICT => 'Resource conflict detected',
            self::RESOURCE_DELETED => 'Resource has been deleted',
            self::RESOURCE_LOCKED => 'Resource is locked',
            self::RESOURCE_OUT_OF_DATE => 'Resource is out of date',
            
            // Business Logic
            self::BUSINESS_RULE_VIOLATION => 'Business rule violation',
            self::INVALID_OPERATION => 'Invalid operation',
            self::OPERATION_NOT_ALLOWED => 'Operation not allowed',
            self::QUOTA_EXCEEDED => 'Quota exceeded',
            self::DUPLICATE_ENTRY => 'Duplicate entry detected',
            
            // External Services
            self::EXTERNAL_SERVICE_ERROR => 'External service error',
            self::EXTERNAL_SERVICE_TIMEOUT => 'External service timeout',
            self::EXTERNAL_SERVICE_UNAVAILABLE => 'External service unavailable',
            self::PAYMENT_GATEWAY_ERROR => 'Payment gateway error',
            
            // Database
            self::DATABASE_ERROR => 'Database error occurred',
            self::DATABASE_CONNECTION_ERROR => 'Database connection failed',
            self::QUERY_EXECUTION_ERROR => 'Query execution failed',
            self::CONSTRAINT_VIOLATION => 'Database constraint violation',
            self::INVITATION_NOT_FOUND => 'Invitation not found',
            self::INVITATION_ALREADY_USED => 'Invitation already used',
            self::RESOURCE_NO_COMPLETIONS => 'Resource has no completions',
        };
    }
    
    /**
     * Get HTTP status code for the error
     */
    public function getHttpStatus(): int
    {
        return match($this) {
            // 500 Internal Server Error
            self::SYSTEM_ERROR,
            self::DATABASE_ERROR,
            self::DATABASE_CONNECTION_ERROR,
            self::QUERY_EXECUTION_ERROR,
            self::EXTERNAL_SERVICE_ERROR => 500,
            
            // 503 Service Unavailable
            self::SYSTEM_MAINTENANCE,
            self::SYSTEM_UNAVAILABLE,
            self::EXTERNAL_SERVICE_UNAVAILABLE => 503,
            
            // 504 Gateway Timeout
            self::EXTERNAL_SERVICE_TIMEOUT => 504,
            
            // 400 Bad Request
            self::INVALID_INPUT,
            self::MISSING_REQUIRED_FIELD,
            self::INVALID_FORMAT,
            self::INVALID_LENGTH,
            self::CSV_TEMPLATE_INVALID,
            self::REGISTRATION_FAILED,
            self::BUSINESS_RULE_VIOLATION,
            self::INVALID_OPERATION,
            self::PASSWORD_RESET_REQUEST_FAILED,
            self::PASSWORD_RESET_FAILED,
            self::PASSWORD_CHANGE_FAILED,
            self::USER_PROFILE_RETRIEVAL_FAILED,
            self::NOTIFICATION_STATUS_FAILED,
            self::NOTIFICATION_UPDATE_FAILED,
            self::ACCOUNT_NOT_VERIFIED,
            self::LOGOUT_FAILED,
            self::TOKEN_REFRESH_FAILED,
            self::ACCOUNT_DELETION_FAILED,
            self::OTP_SEND_FAILED,
            self::OTP_VERIFICATION_FAILED,
            self::OTP_RESEND_FAILED,
            self::OTP_EXPIRED,
            self::OTP_INVALID,
            self::OTP_ACCOUNT_NOT_ELIGIBLE,
            self::INVITATION_NOT_FOUND,
            self::INVITATION_ALREADY_USED,
            self::RESOURCE_OUT_OF_DATE,
            self::RESOURCE_NO_COMPLETIONS,
            self::FILE_TYPE_NOT_ALLOWED,
            self::CONSTRAINT_VIOLATION => 400,
            
            // 401 Unauthorized
            self::UNAUTHORIZED,
            self::AUTHENTICATION_FAILED,
            self::INVALID_CREDENTIALS,
            self::TOKEN_EXPIRED,
            self::TOKEN_INVALID,
            
            self::NOTIFICATION_UPDATE_FAILED => 401,
            
            // 403 Forbidden
            self::AUTHORIZATION_FAILED,
            self::ACCESS_DENIED,
            self::INSUFFICIENT_PERMISSIONS,
            self::OPERATION_NOT_ALLOWED => 403,
            
            // 404 Not Found
            self::RESOURCE_NOT_FOUND,
            self::RESOURCE_DELETED => 404,
            
            // 409 Conflict
            self::RESOURCE_ALREADY_EXISTS,
            self::RESOURCE_CONFLICT,
            self::DUPLICATE_ENTRY => 409,
            
            // 423 Locked
            self::RESOURCE_LOCKED => 423,
            
            // 422 Unprocessable Entity
            self::VALIDATION_ERROR => 422,
            
            // 429 Too Many Requests
            self::QUOTA_EXCEEDED => 429,
            self::RATE_LIMIT_EXCEEDED => 429,
            self::INVALID_CREDENTIALS_LIMIT_EXCEEDED => 429,
            
            // 500 Internal Server Error
            self::PAYMENT_GATEWAY_ERROR => 500,
        };
    }
    
    public function getSeverity(): string
    {
        return match($this) {
            // Critical - System failures
            self::SYSTEM_ERROR,
            self::DATABASE_ERROR,
            self::DATABASE_CONNECTION_ERROR,
            self::SYSTEM_UNAVAILABLE => 'critical',
            
            // High - Service issues
            self::EXTERNAL_SERVICE_ERROR,
            self::EXTERNAL_SERVICE_UNAVAILABLE,
            self::PAYMENT_GATEWAY_ERROR,
            self::QUERY_EXECUTION_ERROR => 'high',
            
            // Medium - Business logic issues
            self::BUSINESS_RULE_VIOLATION,
            self::CONSTRAINT_VIOLATION,
            self::EXTERNAL_SERVICE_TIMEOUT,
            self::UNAUTHORIZED,
            self::AUTHENTICATION_FAILED,
            self::AUTHORIZATION_FAILED,
            self::REGISTRATION_FAILED,
            self::LOGOUT_FAILED,
            self::TOKEN_REFRESH_FAILED,
            self::OTP_SEND_FAILED,
            self::OTP_VERIFICATION_FAILED,
            self::OTP_RESEND_FAILED,
            self::OTP_EXPIRED,
            self::OTP_INVALID,
            self::OTP_ACCOUNT_NOT_ELIGIBLE,
            self::PASSWORD_RESET_REQUEST_FAILED,
            self::PASSWORD_RESET_FAILED,
            self::PASSWORD_CHANGE_FAILED,
            self::USER_PROFILE_RETRIEVAL_FAILED,
            self::NOTIFICATION_STATUS_FAILED,
            self::NOTIFICATION_UPDATE_FAILED,
            self::ACCOUNT_NOT_VERIFIED,
            self::INVITATION_NOT_FOUND,
            self::INVITATION_ALREADY_USED,
            self::INVALID_CREDENTIALS_LIMIT_EXCEEDED,
            self::ACCOUNT_DELETION_FAILED => 'medium',
            
            // Low - User input issues
            self::VALIDATION_ERROR,
            self::INVALID_INPUT,
            self::MISSING_REQUIRED_FIELD,
            self::INVALID_FORMAT,
            self::INVALID_LENGTH,
            self::CSV_TEMPLATE_INVALID,
            self::FILE_TYPE_NOT_ALLOWED,
            self::RESOURCE_NOT_FOUND,
            self::RESOURCE_OUT_OF_DATE,
            self::RESOURCE_NO_COMPLETIONS,
            self::RESOURCE_DELETED => 'low',
            
            // Info - Operational
            self::SYSTEM_MAINTENANCE,
            self::RESOURCE_ALREADY_EXISTS,
            self::DUPLICATE_ENTRY,
            self::QUOTA_EXCEEDED,
            self::RATE_LIMIT_EXCEEDED => 'info',
            
            default => 'medium'
        };
    }
}
