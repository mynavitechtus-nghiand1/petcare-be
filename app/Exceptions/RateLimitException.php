<?php

namespace App\Exceptions;

use App\Enums\ErrorCode;
use App\Exceptions\BaseException;

class RateLimitException extends BaseException
{
    public function __construct(
        ?string $message = null,
        array $context = []
    ) {
        parent::__construct(
            ErrorCode::RATE_LIMIT_EXCEEDED,
            $message ?? ErrorCode::RATE_LIMIT_EXCEEDED->getMessage(),
            $context
        );
    }
}
