<?php

namespace Core\Domain\Validation;

use Core\Domain\Exceptions\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value, string $exceptionMessage = null): bool
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptionMessage ?? "Should not be empty or null");
        }

        return true;
    }

    public static function strMaxLength(string $value, int $length = 255, string $exceptionMessage = null): bool
    {
        if (!empty($value) && mb_strlen($value, 'utf8') > $length) {
            throw new EntityValidationException($exceptionMessage ?? "The value must not be greater than {$length} chatacters");
        }

        return true;
    }

    public static function strMinLength(string $value, int $length = 3, string $exceptionMessage = null): bool
    {
        if (!empty($value) && mb_strlen($value, 'utf8') < $length) {
            throw new EntityValidationException($exceptionMessage ?? "The value must not be least {$length} chatacters");
        }

        return true;
    }
}
