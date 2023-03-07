<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;

class DomainValidationUnitTest extends TestCase
{
    public function test_not_null()
    {
        $value = 'teste';

        $this->assertTrue(DomainValidation::notNull($value));
    }

    public function test_not_null_exception()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Should not be empty or null');

        $value = '';

        DomainValidation::notNull($value);
    }

    public function test_not_null_exception_message()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('message test');

        $value = '';

        DomainValidation::notNull($value, 'message test');
    }

    public function test_str_max_length()
    {
        $value = 'Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Lorem';

        $this->assertTrue(DomainValidation::strMaxLength($value));
    }

    public function test_str_max_length_exception()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The value must not be greater than 255 chatacters');

        $value = 'Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Loremm';

        DomainValidation::strMaxLength($value);
    }

    public function test_str_max_length_exception_message()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('limite máxim de 10');

        $value = 'Lorem Ipsum';

        DomainValidation::strMaxLength($value, 10, 'limite máxim de 10');
    }

    public function test_str_min_length()
    {
        $value = 'Lor';

        $this->assertTrue(DomainValidation::strMaxLength($value));
    }

    public function test_str_min_length_exception()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The value must not be greater than 255 chatacters');

        $value = 'Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Loremm';

        DomainValidation::strMaxLength($value);
    }

    public function test_str_min_length_exception_message()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('limite máxim de 10');

        $value = 'Lorem Ipsum';

        DomainValidation::strMaxLength($value, 10, 'limite máxim de 10');
    }
}
